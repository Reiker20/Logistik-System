<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    // GET /api/notifikasi
    public function index()
    {
        $notifikasi = Notifikasi::all();
        return response()->json([
            'status'  => 'Success',
            'message' => 'List of notifikasi',
            'data'    => $notifikasi
        ]);
    }

    // GET /api/notifikasi/{id}
    public function show($id)
    {
        $notifikasi = Notifikasi::find($id);

        if (!$notifikasi) {
            return response()->json([
                'status'  => 'Failed',
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status'  => 'Success',
            'message' => 'Notifikasi ditemukan',
            'data'    => $notifikasi
        ]);
    }

    // DELETE /api/notifikasi/{id}
    public function destroy($id)
    {
        $notifikasi = Notifikasi::find($id);

        if (!$notifikasi) {
            return response()->json([
                'status'  => 'Failed',
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        $notifikasi->delete();

        return response()->json([
            'status'  => 'Success',
            'message' => 'Notifikasi deleted successfully'
        ]);
    }
}
