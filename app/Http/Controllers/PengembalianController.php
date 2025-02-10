<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Laravel\SerializableClosure\Support\SelfReference;
use PHPUnit\Framework\SelfDescribing;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        $pengembalian = Pengembalian::all();
        $siswa = FacadesDB::table('siswa')->get();
        
        // Jika request-nya AJAX (pilih siswa di modal)
        if ($request->ajax()) {
            
            // Ambil siswa_id dari request
            $siswa_id = $request->siswa_id;
    
            // Check if siswa_id is not empty
            if (empty($siswa_id)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Siswa ID is required.'
                ], 400);
            }
    
            // Ambil data peminjaman berdasarkan siswa_id dan pastikan pb_status tidak 0
            $peminjamanDetails = FacadesDB::table('tm_peminjaman')
                ->join('td_peminjaman_barang', 'tm_peminjaman.pb_id', '=', 'td_peminjaman_barang.pb_id')
                ->join('tm_barang_inventaris', 'td_peminjaman_barang.br_kode', '=', 'tm_barang_inventaris.br_kode')
                ->where('tm_peminjaman.siswa_id', $siswa_id)
                ->where('td_peminjaman_barang.pdb_status', '!=', 0) // Memastikan pb_status tidak 0
                ->select(
                    'tm_peminjaman.pb_id',
                    'tm_barang_inventaris.br_kode',
                    'tm_barang_inventaris.br_nama',
                )
                ->get();
    
            // Jika tidak ada data, kirim response kosong
            if ($peminjamanDetails->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No data available for this student.'
                ], 200);
            }
    
            return response()->json($peminjamanDetails);
            // Debugging output
dd($peminjamanDetails);

        }
        
    
        return view('superuser.pengembalian.home', compact('pengembalian', 'siswa'));
    }
    


    public function store(Request $request)
    {
        $idTransaksi = Pengembalian::generateTransactionId();
        $request->validate([
            'pb_id' => 'required',
            'kembali_tgl' => 'required',
        ]);
        $siswa = FacadesDB::table('siswa')->get();

        // Check if the items have already been returned
        $alreadyReturned = FacadesDB::table('td_peminjaman_barang')
            ->where('pb_id', $request->pb_id)
            ->where('pdb_status', 0)
            ->exists();

        if ($alreadyReturned) {
            return redirect()->back()->withErrors(['error' => 'Barang sudah dikembalikan.']);
        }

        $pengembalian = Pengembalian::create([
            'kembali_id' => $idTransaksi,
            'user_id' => Auth::id(),
            'pb_id' => $request->pb_id,
            'kembali_tgl' => $request->kembali_tgl,
            'kembali_status' => 1,
        ]);
        // Update the status of the borrowed items directly without using a trigger
        FacadesDB::table('td_peminjaman_barang')
            ->where('pb_id', $request->pb_id)
            ->update(['pdb_status' => 0]);

        FacadesDB::table('tm_peminjaman') 
            ->where('pb_id', $request->pb_id)
            ->update(['pb_status' => 0]);

        FacadesDB::table('tm_barang_inventaris')
            ->whereIn('br_kode', function($query) use ($request) {
            $query->select('br_kode')
                  ->from('td_peminjaman_barang')
                  ->where('pb_id', $request->pb_id);
            })
            ->update(['status_barang' => 1]);

        return redirect()->route('pengembalian');
    }
}