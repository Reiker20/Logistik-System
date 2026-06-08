<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    // GET /api/items — ambil semua item
    public function index()
    {
        $items = Item::all();
        return response()->json([
            'status'  => 'Success',
            'message' => 'List of items',
            'data'    => $items
        ]);
    }

    // POST /api/items — tambah item baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|max:255',
            'kategori'    => 'required|string|max:255',
            'stok'        => 'required|integer|min:0',
            'kondisi'     => 'required|in:baik,rusak,diperbaiki',
            'deskripsi'   => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'Failed',
                'message' => $validator->errors()
            ], 422);
        }

        $item = Item::create($request->only([
            'nama_barang', 'kategori', 'stok', 'kondisi', 'deskripsi'
        ]));

        return response()->json([
            'status'  => 'Success',
            'message' => 'Item created successfully',
            'data'    => $item
        ], 201);
    }

    // GET /api/items/{id} — ambil satu item
    public function show($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json([
                'status'  => 'Failed',
                'message' => 'Item not found'
            ], 404);
        }

        return response()->json([
            'status'  => 'Success',
            'message' => 'Item found',
            'data'    => $item
        ]);
    }

    // PUT /api/items/{id} — update item
    public function update(Request $request, $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json([
                'status'  => 'Failed',
                'message' => 'Item not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_barang' => 'sometimes|string|max:255',
            'kategori'    => 'sometimes|string|max:255',
            'stok'        => 'sometimes|integer|min:0',
            'kondisi'     => 'sometimes|in:baik,rusak,diperbaiki',
            'deskripsi'   => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'Failed',
                'message' => $validator->errors()
            ], 422);
        }

        $item->update($request->only([
            'nama_barang', 'kategori', 'stok', 'kondisi', 'deskripsi'
        ]));

        return response()->json([
            'status'  => 'Success',
            'message' => 'Item updated successfully',
            'data'    => $item
        ]);
    }

    // DELETE /api/items/{id} — hapus item
    public function destroy($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json([
                'status'  => 'Failed',
                'message' => 'Item not found'
            ], 404);
        }

        $item->delete();

        return response()->json([
            'status'  => 'Success',
            'message' => 'Item deleted successfully'
        ]);
    }
}