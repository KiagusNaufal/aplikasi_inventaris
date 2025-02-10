<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::all();
        return response()->json($siswa);
    }
    
    public function search(Request $request)
    {
        $query = $request->query('query');
    
        $siswa = Siswa::where('nama', 'LIKE', "%{$query}%")
            ->orWhere('nis', 'LIKE', "%{$query}%")
            ->get();
    
        return response()->json($siswa);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:50',
            'nis' => 'required|string|max:20',
            'kelas_id' => 'required',
            'jurusan_id' => 'required',

        ]);

        $siswa = new Siswa();
        $siswa->nis = $request->nis;
        $siswa->nama = $request->nama;
        $siswa->kelas_id = $request->kelas_id;
        $siswa->jurusan_id = $request->jurusan_id;
        $siswa->save();

        return response()->json([
            'message' => 'Siswa berhasil ditambahkan',
            'data' => $siswa
        ]);
    }

    public function show($id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json([
                'message' => 'Siswa tidak ditemukan'
            ], 404);
        }

        return response()->json($siswa);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:50',
            'nis' => 'required|string|max:20',
            'kelas_id' => 'required',
            'jurusan_id' => 'required',
        ]);

        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json([
                'message' => 'Siswa tidak ditemukan'
            ], 404);
        }

        $siswa->nis = $request->nis;
        $siswa->nama = $request->nama;
        $siswa->kelas_id = $request->kelas_id;
        $siswa->jurusan_id = $request->jurusan_id;
        $siswa->save();

        return response()->json([
            'message' => 'Siswa berhasil diupdate',
            'data' => $siswa
        ]);
    }

    public function destroy($id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json([
                'message' => 'Siswa tidak ditemukan'
            ], 404);
        }

        $siswa->delete();

        return response()->json([
            'message' => 'Siswa berhasil di hapus'
            ]);
}
}