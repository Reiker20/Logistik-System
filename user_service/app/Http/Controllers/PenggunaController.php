<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    public function ambilSemua(): JsonResponse
    {
        $daftarPengguna = Pengguna::all();

        return response()->json([
            'status' => true,
            'pesan' => 'Berhasil mengambil data pengguna',
            'data' => $daftarPengguna,
        ]);
    }

    public function ambilBerdasarkanId(int $id): JsonResponse
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json([
                'status' => false,
                'pesan' => 'Pengguna tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'pesan' => 'Berhasil mengambil data pengguna',
            'data' => $pengguna,
        ]);
    }

    public function tambah(Request $request): JsonResponse
    {
        $validasi = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email',
            'nomor_telepon' => 'nullable|string|max:20',
            'peran' => 'in:admin,peminjam,petugas',
            'kata_sandi' => 'required|string|min:6',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'pesan' => 'Validasi gagal',
                'kesalahan' => $validasi->errors(),
            ], 422);
        }

        $dataMasukan = $request->all();
        $dataMasukan['kata_sandi'] = Hash::make($dataMasukan['kata_sandi']);

        $pengguna = Pengguna::create($dataMasukan);

        return response()->json([
            'status' => true,
            'pesan' => 'Pengguna berhasil ditambahkan',
            'data' => $pengguna,
        ], 201);
    }

    public function perbarui(Request $request, int $id): JsonResponse
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json([
                'status' => false,
                'pesan' => 'Pengguna tidak ditemukan',
            ], 404);
        }

        $validasi = Validator::make($request->all(), [
            'nama_lengkap' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:pengguna,email,' . $id,
            'nomor_telepon' => 'nullable|string|max:20',
            'peran' => 'in:admin,peminjam,petugas',
            'kata_sandi' => 'sometimes|string|min:6',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'pesan' => 'Validasi gagal',
                'kesalahan' => $validasi->errors(),
            ], 422);
        }

        $dataMasukan = $request->all();

        if (isset($dataMasukan['kata_sandi'])) {
            $dataMasukan['kata_sandi'] = Hash::make($dataMasukan['kata_sandi']);
        }

        $pengguna->update($dataMasukan);

        return response()->json([
            'status' => true,
            'pesan' => 'Pengguna berhasil diperbarui',
            'data' => $pengguna,
        ]);
    }

    public function hapus(int $id): JsonResponse
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json([
                'status' => false,
                'pesan' => 'Pengguna tidak ditemukan',
            ], 404);
        }

        $pengguna->delete();

        return response()->json([
            'status' => true,
            'pesan' => 'Pengguna berhasil dihapus',
        ]);
    }
}
