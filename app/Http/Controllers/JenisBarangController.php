<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisBarangController extends Controller
{
    public function index()
    {
        $jenis_barang = JenisBarang::all();
        return view('superuser.referensi.jenis', compact('jenis_barang'));
    }



    public function generateKode()
    {
        $lastKode = JenisBarang::orderBy('jns_barang_kode', 'desc')->first();
        
        // Ambil angka setelah prefix 'JB' dan ubah ke integer
        $lastNumber = $lastKode ? intval(substr($lastKode->jns_barang_kode, 2)) : 0;
    
        // Tambah 1 dan tetap dalam format string
        $newKode = 'JB' . str_pad((string) ($lastNumber + 1), 3, '0', STR_PAD_LEFT);
    
        return $newKode;
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'jns_barang_nama' => 'required|string|max:50',
        ]);

        $jenis_barang = new JenisBarang();
        $jenis_barang->jns_barang_nama = $request->jns_barang_nama;
        $jenis_barang->jns_barang_kode = $this->generateKode();
        $jenis_barang->save();

        return redirect()->route('jenis-barang')->with('success', 'Jenis Barang berhasil ditambahkan');
    }

    public function show($id)
    {
        $jenis_barang = JenisBarang::find($id);

        if (!$jenis_barang) {
            return response()->json([
                'message' => 'Jenis Barang tidak ditemukan'
            ], 404);
        }

        return response()->json($jenis_barang);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'jns_barang_nama' => 'required|string|max:50',
        ]);

        $jenis_barang = JenisBarang::find($id);

        if (!$jenis_barang) {
            return response()->json([
                'message' => 'Jenis Barang tidak ditemukan'
            ], 404);
        }

        $jenis_barang->jns_barang_nama = $request->jns_barang_nama;
        $jenis_barang->save();

        return response()->json([
            'message' => 'Jenis Barang berhasil diupdate',
            'data' => $jenis_barang
        ]);
    }

    public function destroy($id)
    {
        $jenis_barang = JenisBarang::find($id);

        if (!$jenis_barang) {
            return redirect()->route('jenis-barang')->with('error', 'Jenis Barang tidak ditemukan');
        }

        $jenis_barang->delete();

return redirect()->route('jenis-barang')->with('success', 'Jenis Barang berhasil dihapus');
    }


}
