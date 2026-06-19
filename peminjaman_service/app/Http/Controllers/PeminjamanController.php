<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Services\RabbitMQProducer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class PeminjamanController extends Controller
{
    public function ambilSemua(): JsonResponse
    {
        $daftarPeminjaman = Peminjaman::all();

        return response()->json([
            'status' => true,
            'pesan' => 'Berhasil mengambil data peminjaman',
            'data' => $daftarPeminjaman,
        ]);
    }

    public function ambilBerdasarkanId(int $id): JsonResponse
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'status' => false,
                'pesan' => 'Peminjaman tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'pesan' => 'Berhasil mengambil data peminjaman',
            'data' => $peminjaman,
        ]);
    }

    public function ambilBerdasarkanPengguna(int $idPengguna): JsonResponse
    {
        $daftarPeminjaman = Peminjaman::where('id_pengguna', $idPengguna)->get();

        return response()->json([
            'status' => true,
            'pesan' => 'Berhasil mengambil data peminjaman pengguna',
            'data' => $daftarPeminjaman,
        ]);
    }

    public function tambah(Request $request): JsonResponse
    {
        $validasi = Validator::make($request->all(), [
            'id_pengguna' => 'required|integer',
            'id_barang' => 'required|integer',
            'jumlah_pinjam' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'catatan' => 'nullable|string',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'pesan' => 'Validasi gagal',
                'kesalahan' => $validasi->errors(),
            ], 422);
        }

        $peminjaman = Peminjaman::create($request->all());

        try {
            $producer = new RabbitMQProducer();
            $producer->kirimPesan(
                env('RABBITMQ_QUEUE', 'antrian_peminjaman'),
                [
                    'kejadian' => 'peminjaman_baru',
                    'id_peminjaman' => $peminjaman->id,
                    'id_pengguna' => $peminjaman->id_pengguna,
                    'id_barang' => $peminjaman->id_barang,
                    'jumlah_pinjam' => $peminjaman->jumlah_pinjam,
                    'status_transaksi' => $peminjaman->status_transaksi,
                    'tanggal_pinjam' => $peminjaman->tanggal_pinjam->toDateString(),
                    'tanggal_kembali' => $peminjaman->tanggal_kembali->toDateString(),
                    'waktu_kejadian' => now()->toIso8601String(),
                ]
            );
        } catch (\Exception $pengecualian) {
            report($pengecualian);
        }

        return response()->json([
            'status' => true,
            'pesan' => 'Peminjaman berhasil dibuat',
            'data' => $peminjaman,
        ], 201);
    }

    public function perbaruiStatus(Request $request, int $id): JsonResponse
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'status' => false,
                'pesan' => 'Peminjaman tidak ditemukan',
            ], 404);
        }

        $validasi = Validator::make($request->all(), [
            'status_transaksi' => 'required|in:menunggu,disetujui,dipinjam,dikembalikan,ditolak,terlambat',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'pesan' => 'Validasi gagal',
                'kesalahan' => $validasi->errors(),
            ], 422);
        }

        $statusSebelumnya = $peminjaman->status_transaksi;
        $peminjaman->status_transaksi = $request->input('status_transaksi');

        if ($request->input('status_transaksi') === 'dikembalikan') {
            $peminjaman->tanggal_dikembalikan = now();
        }

        $peminjaman->save();

        try {
            $producer = new RabbitMQProducer();
            $producer->kirimPesan(
                env('RABBITMQ_QUEUE', 'antrian_peminjaman'),
                [
                    'kejadian' => 'status_berubah',
                    'id_peminjaman' => $peminjaman->id,
                    'id_pengguna' => $peminjaman->id_pengguna,
                    'status_sebelumnya' => $statusSebelumnya,
                    'status_baru' => $peminjaman->status_transaksi,
                    'waktu_kejadian' => now()->toIso8601String(),
                ]
            );
        } catch (\Exception $pengecualian) {
            report($pengecualian);
        }

        return response()->json([
            'status' => true,
            'pesan' => 'Status peminjaman berhasil diperbarui',
            'data' => $peminjaman,
        ]);
    }

    public function perbarui(Request $request, int $id): JsonResponse
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'status' => false,
                'pesan' => 'Peminjaman tidak ditemukan',
            ], 404);
        }

        $validasi = Validator::make($request->all(), [
            'jumlah_pinjam' => 'sometimes|integer|min:1',
            'tanggal_pinjam' => 'sometimes|date',
            'tanggal_kembali' => 'sometimes|date',
            'catatan' => 'nullable|string',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'pesan' => 'Validasi gagal',
                'kesalahan' => $validasi->errors(),
            ], 422);
        }

        $peminjaman->update($request->all());

        return response()->json([
            'status' => true,
            'pesan' => 'Peminjaman berhasil diperbarui',
            'data' => $peminjaman,
        ]);
    }

    public function hapus(int $id): JsonResponse
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'status' => false,
                'pesan' => 'Peminjaman tidak ditemukan',
            ], 404);
        }

        $peminjaman->delete();

        return response()->json([
            'status' => true,
            'pesan' => 'Peminjaman berhasil dihapus',
        ]);
    }
}
