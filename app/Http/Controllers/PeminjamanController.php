<?php

namespace App\Http\Controllers;

use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['siswa', 'detailPeminjaman.barangInventaris'])->get();
        return view('superuser.peminjaman.view', compact('peminjaman'));
    }
    
    public function create()
    {
        return view('superuser.peminjaman.home');
    }


    
    
    public static function generatePbId()
    {
        $currentYear = date('Y'); // Mendapatkan tahun saat ini
        $currentMonth = date('m'); // Mendapatkan bulan saat ini
        $prefix = 'PJ'; // Prefix untuk ID transaksi peminjaman


        $maxUrut = DB::table('tm_peminjaman')
            ->select(DB::raw("IFNULL(MAX(CAST(SUBSTRING(pb_id, 10, 3) AS UNSIGNED)), 0) + 1 AS next_urut"))
            ->whereRaw("SUBSTRING(pb_id, 3, 4) = ?", [$currentYear])
            ->whereRaw("SUBSTRING(pb_id, 7, 2) = ?", [$currentMonth])
            ->value('next_urut');

        // Format ID transaksi peminjaman dengan nomor urut
        return sprintf("%s%s%s%03d", $prefix, $currentYear, $currentMonth, $maxUrut);
    }
    public static function generatePbdId($pbId)
    {
        // Ambil nomor urut terakhir berdasarkan pb_id
        $maxUrut = DB::table('td_peminjaman_barang')
            ->select(DB::raw("IFNULL(MAX(CAST(SUBSTRING(pbd_id, -3) AS UNSIGNED)), 0) + 1 AS next_urut"))
            ->where('pb_id', $pbId)
            ->value('next_urut');
            
            // Format ID detail peminjaman dengan no urut
            return sprintf("%s%03d", $pbId, $maxUrut);
        }
        public function store(Request $request)
        {

            $request->merge([
                'br_kode' => json_decode($request->br_kode, true)
            ]);
        try {
            $validatedData = $request->validate([
                'pb_harus_kembali_tgl' => 'required|date',
                'br_kode' => 'required|array',
                'siswa_id' => 'required|integer',
                'br_kode.*' => 'required|string|max:20',
            ]);
            $existingStatus = DB::table('tm_barang_inventaris')
            ->whereIn('br_kode', $request->br_kode)
            ->pluck('status_barang', 'br_kode');

            foreach ($existingStatus as $status) {
                if ($status == "0") {
                    return back()->withErrors(['message' => 'Barang sedang di pinjam.']);
                }
            }

            $user = Auth::user(); // Mengambil user dari token
            $userId = $user->id;
            $pbId = self::generatePbId();

            $peminjaman = Peminjaman::create([
                'pb_id' => $pbId,
                'user_id' => $userId,
                'siswa_id' => $validatedData['siswa_id'],
                'pb_tgl' => now(),
                'pb_harus_kembali_tgl' => $validatedData['pb_harus_kembali_tgl'],
                'pb_status' => 1,
            ]);

            if (!$peminjaman) {
                return back()->withErrors(['message' => 'Gagal membuat peminjaman.'])->withInput();
            }


            $peminjamanDetail = [];
            foreach ($validatedData['br_kode'] as $brKode) {
                $peminjamanDetail[] = DetailPeminjaman::create([
                    'pbd_id' => self::generatePbdId($pbId),
                    'pb_id' => $pbId,
                    'br_kode' => $brKode,
                    'pdb_tgl' => now(),
                    'pdb_status' => 1,
                ]);

                // Update status barang inventaris menjadi 0
                DB::table('tm_barang_inventaris')
                    ->where('br_kode', $brKode)
                    ->update(['status_barang' => 0]);
            }

            return redirect()->route('pinjaman');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Terjadi kesalahan saat menyimpan data.', 'error' => $e->getMessage()])->withInput();
        }
    }
    public function update($id) 
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            $peminjaman->update([
                'pb_status' =>  '0',
            ]);

            $detailPeminjaman = DetailPeminjaman::where('pb_id', $id)->get();
            foreach ($detailPeminjaman as $detail) {
                $detail->update([
                    'pdb_status' => '0',
                ]);

                // Update status barang inventaris menjadi 1
                DB::table('tm_barang_inventaris')
                    ->where('br_kode', $detail->br_kode)
                    ->update(['status_barang' => 1]);
            }

            return response()->json(['peminjaman' => $peminjaman], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat mengupdate data.', 'message' => $e->getMessage()], 500);
        }
    }
}
