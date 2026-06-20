<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class NotifikasiController extends Controller
{
    public function ambilSemua(): JsonResponse
    {
        $daftarNotifikasi = Notifikasi::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'pesan' => 'Berhasil mengambil data notifikasi',
            'data' => $daftarNotifikasi,
        ]);
    }

    public function ambilBerdasarkanId(int $id): JsonResponse
    {
        $notifikasi = Notifikasi::find($id);

        if (!$notifikasi) {
            return response()->json([
                'status' => false,
                'pesan' => 'Notifikasi tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'pesan' => 'Berhasil mengambil data notifikasi',
            'data' => $notifikasi,
        ]);
    }

    public function ambilBerdasarkanPengguna(int $idPengguna): JsonResponse
    {
        $daftarNotifikasi = Notifikasi::where('id_pengguna', $idPengguna)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'pesan' => 'Berhasil mengambil notifikasi pengguna',
            'data' => $daftarNotifikasi,
        ]);
    }

    public function ambilBelumDibaca(int $idPengguna): JsonResponse
    {
        $daftarNotifikasi = Notifikasi::where('id_pengguna', $idPengguna)
            ->where('status_baca', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'pesan' => 'Berhasil mengambil notifikasi belum dibaca',
            'data' => $daftarNotifikasi,
            'jumlah_belum_dibaca' => $daftarNotifikasi->count(),
        ]);
    }

    public function tandaiBaca(int $id): JsonResponse
    {
        $notifikasi = Notifikasi::find($id);

        if (!$notifikasi) {
            return response()->json([
                'status' => false,
                'pesan' => 'Notifikasi tidak ditemukan',
            ], 404);
        }

        $notifikasi->status_baca = true;
        $notifikasi->save();

        return response()->json([
            'status' => true,
            'pesan' => 'Notifikasi ditandai sudah dibaca',
            'data' => $notifikasi,
        ]);
    }

    public function tandaiSemuaBaca(int $idPengguna): JsonResponse
    {
        Notifikasi::where('id_pengguna', $idPengguna)
            ->where('status_baca', false)
            ->update(['status_baca' => true]);

        return response()->json([
            'status' => true,
            'pesan' => 'Semua notifikasi ditandai sudah dibaca',
        ]);
    }

    public function tambah(Request $request): JsonResponse
    {
        $validasi = Validator::make($request->all(), [
            'id_pengguna' => 'required|integer',
            'id_peminjaman' => 'nullable|integer',
            'judul' => 'required|string|max:255',
            'isi_pesan' => 'required|string',
            'jenis_notifikasi' => 'in:peminjaman_baru,status_berubah,pengingat_pengembalian,umum',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'pesan' => 'Validasi gagal',
                'kesalahan' => $validasi->errors(),
            ], 422);
        }

        $notifikasi = Notifikasi::create($request->all());

        return response()->json([
            'status' => true,
            'pesan' => 'Notifikasi berhasil ditambahkan',
            'data' => $notifikasi,
        ], 201);
    }

    public function hapus(int $id): JsonResponse
    {
        $notifikasi = Notifikasi::find($id);

        if (!$notifikasi) {
            return response()->json([
                'status' => false,
                'pesan' => 'Notifikasi tidak ditemukan',
            ], 404);
        }

        $notifikasi->delete();

        return response()->json([
            'status' => true,
            'pesan' => 'Notifikasi berhasil dihapus',
        ]);
    }
}
