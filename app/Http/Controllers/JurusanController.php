<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusan = Jurusan::all();
        return response()->json($jurusan);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_jurusan' => 'required|string|max:50',
        ]);

        $jurusan = new Jurusan();
        $jurusan->nama_jurusan = $request->nama_jurusan;
        $jurusan->save();

        return response()->json([
            'message' => 'Jurusan berhasil ditambahkan',
            'data' => $jurusan
        ]);
    }

    public function show($id)
    {
        $jurusan = Jurusan::find($id);

        if (!$jurusan) {
            return response()->json([
                'message' => 'Jurusan tidak ditemukan'
            ], 404);
        }

        return response()->json($jurusan);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_jurusan' => 'required|string|max:50',
        ]);

        $jurusan = Jurusan::find($id);

        if (!$jurusan) {
            return response()->json([
                'message' => 'Jurusan tidak ditemukan'
            ], 404);
        }

        $jurusan->nama_jurusan = $request->nama_jurusan;
        $jurusan->save();

        return response()->json([
            'message' => 'Jurusan berhasil diupdate',
            'data' => $jurusan
        ]);
    }

    public function destroy($id)
    {
        $jurusan = Jurusan::find($id);

        if (!$jurusan) {
            return response()->json([
                'message' => 'Jurusan tidak ditemukan'
            ], 404);
        }

        $jurusan->delete();

        return response()->json([
            'message' => 'Jurusan berhasil dihapus'
        ]);
    }

    
}
