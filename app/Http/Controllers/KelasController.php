<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();
        return response()->json($kelas);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_kelas' => 'required|string|max:50',
        ]);

        $kelas = new Kelas();
        $kelas->nama_kelas = $request->nama_kelas;
        $kelas->save();

        return response()->json([
            'message' => 'Kelas berhasil ditambahkan',
            'data' => $kelas
        ]);
    }

    public function show($id)
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json([
                'message' => 'Kelas tidak ditemukan'
            ], 404);
        }

        return response()->json($kelas);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_kelas' => 'required|string|max:50',
        ]);

        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json([
                'message' => 'Kelas tidak ditemukan'
            ], 404);
        }

        $kelas->nama_kelas = $request->nama_kelas;
        $kelas->save();

        return response()->json([
            'message' => 'Kelas berhasil diupdate',
            'data' => $kelas
        ]);
    }

    public function destroy($id)
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json([
                'message' => 'Kelas tidak ditemukan'
            ], 404);
        }

        $kelas->delete();

        return response()->json([
            'message' => 'Kelas berhasil dihapus'
        ]);
    }
}
