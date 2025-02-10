<?php

namespace App\Http\Controllers;

use App\Models\BarangInventaris;
use App\Models\JenisBarang;
use App\Models\Vendor;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

class BarangInventarisController extends Controller
{
    public function index()
    {
        
        $barang = BarangInventaris::all();
        $vendor = Vendor::all();
        $jenis = JenisBarang::all();
        return view('superuser.barang.home', compact('barang', 'vendor', 'jenis'));
    }

    public function search(Request $request)
{
    $query = $request->query('query');

    $barang = BarangInventaris::where('br_nama', 'LIKE', "%{$query}%")
        ->orWhere('br_kode', 'LIKE', "%{$query}%")
        ->where('status_barang', 1) // Hanya barang tersedia
        ->get();

    return response()->json($barang);
}

    public static function generateBrKode()
    {
        $currentYear = date('Y'); // Mendapatkan tahun saat ini
        $prefix = 'INV'; // Prefix kode barang

        // Mendapatkan nomor urut terakhir untuk tahun saat ini
        $maxKode = FacadesDB::table('tm_barang_inventaris')
            ->select(FacadesDB::raw("IFNULL(MAX(CAST(SUBSTRING(br_kode, 8, 5) AS UNSIGNED)), 0) + 1 AS next_kode"))
            ->whereRaw("SUBSTRING(br_kode, 4, 4) = ?", [$currentYear])
            ->value('next_kode');

        // Mengembalikan format kode barang
        return sprintf("%s%s%05d", $prefix, $currentYear, $maxKode);
    }
    public function store(Request $request)
    {
    if (!Auth::check()) {
        return redirect()->route('login')->withErrors('Anda harus login terlebih dahulu.');
    }

    // Ambil user yang login
    $user = Auth::user();
    $userId = $user->id;
        
        $request->validate([
            'jns_barang_kode' => 'required|string',
            'vendor_id' => 'required|integer',
            'br_nama' => 'required|string|max:50',
            'br_tgl_nerima' => 'required|date',
            'status_barang' => 'required|string|max:2',
            'kondisi_barang' => 'required|string|max:2',
        ]);
        

        try {

            $brKode = BarangInventarisController::generateBrKode();

            $barangInventaris = BarangInventaris::create([
                'br_kode' => $brKode,
                'jns_barang_kode' => 1,
                'user_id' => $userId,
                'vendor_id' => $request->input('vendor_id'),
                'br_nama' => $request->input('br_nama'),
                'br_tgl_nerima' => $request->input('br_tgl_nerima'),
                'br_tgl_entry' => now(),
                'status_barang' => $request->input('status_barang'),
                'kondisi_barang' => $request->input('kondisi_barang'),
            ]);
            return redirect()->route('barang-inventaris', compact('barangInventaris'))->with('success', 'Barang berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menambahkan barang: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)   
    {
        $request->validate([
            'jns_barang_kode' => 'required|string',
            'br_nama' => 'required|string|max:50',
            'br_tgl_nerima' => 'required|date',
            'status_barang' => 'required|string|max:2',
            'kondisi_barang' => 'required|string|max:2',
        ]);

        $barangInventaris = BarangInventaris::findOrFail($id);

        $barangInventaris->update([
            'jns_barang_kode' => $request->input('jns_barang_kode'),
            'br_nama' => $request->input('br_nama'),
            'br_tgl_nerima' => $request->input('br_tgl_nerima'),
            'status_barang' => $request->input('status_barang'),
            'kondisi_barang' => $request->input('kondisi_barang'),
        ]);

        return response()->json($barangInventaris);
    }

    public function destroy($id)
    {
        $barangInventaris = BarangInventaris::findOrFail($id);
        $barangInventaris->delete();

        return response()->json([
            'message' => 'Barang berhasil dihapus'
        ]);
    }
    
}
