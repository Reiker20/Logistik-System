<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class PeminjamanController extends Controller
{
    // URL service lain (sesuaikan port)
    private $userServiceUrl  = 'http://localhost:8001';
    private $itemServiceUrl  = 'http://localhost:8002';

    // GET /api/peminjaman
    public function index()
    {
        $peminjaman = Peminjaman::all();
        return response()->json([
            'status'  => 'Success',
            'message' => 'List of peminjaman',
            'data'    => $peminjaman
        ]);
    }

    // POST /api/peminjaman
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'         => 'required|integer',
            'item_id'         => 'required|integer',
            'jumlah'          => 'required|integer|min:1',
            'tanggal_pinjam'  => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'Failed',
                'message' => $validator->errors()
            ], 422);
        }

        // Cek user ke User Service
        $userResponse = Http::get("{$this->userServiceUrl}/api/users/{$request->user_id}");
        if ($userResponse->failed() || $userResponse['status'] === 'Failed') {
            return response()->json([
                'status'  => 'Failed',
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        // Cek item ke Item Service
        $itemResponse = Http::get("{$this->itemServiceUrl}/api/items/{$request->item_id}");
        if ($itemResponse->failed() || $itemResponse['status'] === 'Failed') {
            return response()->json([
                'status'  => 'Failed',
                'message' => 'Item tidak ditemukan'
            ], 404);
        }

        // Cek stok item
        $item = $itemResponse['data'];
        if ($item['stok'] < $request->jumlah) {
            return response()->json([
                'status'  => 'Failed',
                'message' => 'Stok item tidak mencukupi'
            ], 400);
        }

        // Kurangi stok item
        Http::put("{$this->itemServiceUrl}/api/items/{$request->item_id}", [
            'stok' => $item['stok'] - $request->jumlah
        ]);

        // Buat kode peminjaman otomatis
        $kode = 'PJM-' . strtoupper(uniqid());

        // Simpan peminjaman
        $peminjaman = Peminjaman::create([
            'kode_pinjam'     => $kode,
            'user_id'         => $request->user_id,
            'item_id'         => $request->item_id,
            'jumlah'          => $request->jumlah,
            'tanggal_pinjam'  => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'status'          => 'dipinjam',
        ]);

        return response()->json([
            'status'  => 'Success',
            'message' => 'Peminjaman berhasil dibuat',
            'data'    => $peminjaman
        ], 201);
    }

    // GET /api/peminjaman/{id}
    public function show($id)
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'status'  => 'Failed',
                'message' => 'Peminjaman tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status'  => 'Success',
            'message' => 'Peminjaman ditemukan',
            'data'    => $peminjaman
        ]);
    }

    // PUT /api/peminjaman/{id}
    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'status'  => 'Failed',
                'message' => 'Peminjaman tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:dipinjam,dikembalikan',
            'tanggal_kembali' => 'sometimes|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'Failed',
                'message' => $validator->errors()
            ], 422);
        }

        // Kalau status dikembalikan, kembalikan stok item
        if ($request->status === 'dikembalikan' && $peminjaman->status === 'dipinjam') {
            $itemResponse = Http::get("{$this->itemServiceUrl}/api/items/{$peminjaman->item_id}");
            if ($itemResponse->successful()) {
                $item = $itemResponse['data'];
                Http::put("{$this->itemServiceUrl}/api/items/{$peminjaman->item_id}", [
                    'stok' => $item['stok'] + $peminjaman->jumlah
                ]);
            }
        }

        $peminjaman->update($request->only(['status', 'tanggal_kembali']));

        return response()->json([
            'status'  => 'Success',
            'message' => 'Peminjaman updated successfully',
            'data'    => $peminjaman
        ]);
    }

    // DELETE /api/peminjaman/{id}
    public function destroy($id)
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'status'  => 'Failed',
                'message' => 'Peminjaman tidak ditemukan'
            ], 404);
        }

        $peminjaman->delete();

        return response()->json([
            'status'  => 'Success',
            'message' => 'Peminjaman deleted successfully'
        ]);
    }
}