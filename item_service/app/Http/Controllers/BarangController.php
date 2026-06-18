<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function ambilSemua(): JsonResponse
    {
        $daftarBarang = Barang::all();

        return response()->json([
            'status' => true,
            'pesan' => 'Berhasil mengambil data barang',
            'data' => $daftarBarang,
        ]);
    }

    public function ambilBerdasarkanId(int $id): JsonResponse
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'status' => false,
                'pesan' => 'Barang tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'pesan' => 'Berhasil mengambil data barang',
            'data' => $barang,
        ]);
    }

    public function tambah(Request $request): JsonResponse
    {
        $validasi = Validator::make($request->all(), [
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'jumlah_tersedia' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'lokasi_penyimpanan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'pesan' => 'Validasi gagal',
                'kesalahan' => $validasi->errors(),
            ], 422);
        }

        $barang = Barang::create($request->all());

        return response()->json([
            'status' => true,
            'pesan' => 'Barang berhasil ditambahkan',
            'data' => $barang,
        ], 201);
    }

    public function perbarui(Request $request, int $id): JsonResponse
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'status' => false,
                'pesan' => 'Barang tidak ditemukan',
            ], 404);
        }

        $validasi = Validator::make($request->all(), [
            'nama_barang' => 'sometimes|string|max:255',
            'kategori' => 'sometimes|string|max:100',
            'jumlah_tersedia' => 'sometimes|integer|min:0',
            'satuan' => 'sometimes|string|max:50',
            'lokasi_penyimpanan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'pesan' => 'Validasi gagal',
                'kesalahan' => $validasi->errors(),
            ], 422);
        }

        $barang->update($request->all());

        return response()->json([
            'status' => true,
            'pesan' => 'Barang berhasil diperbarui',
            'data' => $barang,
        ]);
    }

    public function hapus(int $id): JsonResponse
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'status' => false,
                'pesan' => 'Barang tidak ditemukan',
            ], 404);
        }

        $barang->delete();

        return response()->json([
            'status' => true,
            'pesan' => 'Barang berhasil dihapus',
        ]);
    }

    public function kurangiStok(Request $request, int $id): JsonResponse
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'status' => false,
                'pesan' => 'Barang tidak ditemukan',
            ], 404);
        }

        $jumlah = $request->input('jumlah', 1);

        if ($barang->jumlah_tersedia < $jumlah) {
            return response()->json([
                'status' => false,
                'pesan' => 'Stok tidak mencukupi',
            ], 400);
        }

        $barang->jumlah_tersedia -= $jumlah;
        $barang->save();

        return response()->json([
            'status' => true,
            'pesan' => 'Stok berhasil dikurangi',
            'data' => $barang,
        ]);
    }

    public function tambahStok(Request $request, int $id): JsonResponse
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'status' => false,
                'pesan' => 'Barang tidak ditemukan',
            ], 404);
        }

        $jumlah = $request->input('jumlah', 1);
        $barang->jumlah_tersedia += $jumlah;
        $barang->save();

        return response()->json([
            'status' => true,
            'pesan' => 'Stok berhasil ditambah',
            'data' => $barang,
        ]);
    }
}
