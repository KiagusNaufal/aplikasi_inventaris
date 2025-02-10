<?php

namespace App\Http\Controllers;

use App\Models\BarangInventaris;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        Log::info('Request Data:', $request->all()); // Logging request

        $query = BarangInventaris::query();

        if ($request->filled('status_barang')) { // Gunakan filled() agar tidak cek NULL
            $query->where('status_barang', $request->input('status_barang'));
        }

        if ($request->filled('kondisi_barang')) {
            $query->where('kondisi_barang', $request->input('kondisi_barang'));
        }

        if ($request->filled('search')) {
            $query->where('br_nama', 'like', '%' . $request->input('search') . '%');
        }
        if ($request->filled('vendor')) {
            $query->whereHas('vendor', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->vendor . '%');
            });
        }

        if ($request->filled('br_tgl_nerima')) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $request->input('br_tgl_nerima'))->format('Y-m-d');
                $query->whereDate('br_tgl_nerima', $date);
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Invalid date format. Please use yyyy-mm-dd.']);
            }
        }

        $barang = collect(); // Initialize an empty collection

        if ($request->filled('status_barang') || $request->filled('kondisi_barang') || $request->filled('search') || $request->filled('br_tgl_nerima')) {
            $barang = $query->paginate(10);

            Log::info('Barang Inventaris Query:', [
                'query' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            if ($barang->isEmpty()) {
                return back()->withErrors(['error' => 'No items found.']);
            }
        } else {
            $barang = $query->paginate(10);
        }

        return view('superuser.laporan.barang', compact('barang'));
    }

    public function generatePDF(Request $request)
    {
        Log::info('Request Data:', $request->all()); // Logging request

        $query = BarangInventaris::query();

        if ($request->filled('status_barang')) {
            $query->where('status_barang', $request->input('status_barang'));
        }
        if ($request->filled('vendor')) {
            $query->whereHas('vendor', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->vendor . '%');
            });
        }

        if ($request->filled('kondisi_barang')) {
            $query->where('kondisi_barang', $request->input('kondisi_barang'));
        }

        if ($request->filled('search')) {
            $query->where('br_nama', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('br_tgl_nerima')) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $request->input('br_tgl_nerima'))->format('Y-m-d');
                $query->whereDate('br_tgl_nerima', $date);
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Invalid date format. Please use yyyy-mm-dd.']);
            }
        }

        $barang = $query->get(); // Ambil semua data

        Log::info('Barang Inventaris Query:', [
            'query' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        $pdf = FacadePdf::loadView('pdf.barang', compact('barang'));

        return $pdf->download('barang-inventaris.pdf');
    }

    public function peminjaman(Request $request)
    {
        $query = Peminjaman::with(['siswa', 'detailPeminjaman.barangInventaris']);

        if ($request->filled('siswa')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->siswa . '%');
            });
        }

        if ($request->filled('pb_status')) {
            $query->where('pb_status', $request->pb_status);
        }

        if ($request->filled('pb_tgl')) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $request->pb_tgl);
                $query->whereDate('pb_tgl', $date);
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Invalid date format. Please use yyyy-mm-dd.']);
            }
        }

        $peminjaman = collect(); // Initialize an empty collection

        $peminjaman = $query->paginate(10);

        return view('superuser.laporan.peminjaman', compact('peminjaman'));
    }
    public function generatePDFPinjaman(Request $request)
    {
        $query = Peminjaman::with(['siswa', 'detailPeminjaman.barangInventaris']);

        if ($request->filled('siswa')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->siswa . '%');
            });
        }

        if ($request->filled('pb_status')) {
            $query->where('pb_status', $request->pb_status);
        }

        if ($request->filled('pb_tgl')) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $request->pb_tgl);
                $query->whereDate('pb_tgl', $date);
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Invalid date format. Please use yyyy-mm-dd.']);
            }
        }

        $peminjaman = $query->get(); // Ambil semua data


        $pdf = FacadePdf::loadView('pdf.pinjaman', compact('peminjaman'));

        return $pdf->download('pinjaman.pdf');
    }


    public function pengembalian(Request $request)
    {
        $query = Pengembalian::with(['peminjaman.siswa', 'peminjaman.detailPeminjaman.barangInventaris']);

        if ($request->filled('siswa')) {
            $query->whereHas('peminjaman.siswa', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->siswa . '%');
            });
        }

        if ($request->filled('kembali_status')) {
            $query->where('kembali_status', $request->kembali_status);
        }
        if ($request->filled('kembali_tgl')) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $request->pb_tgl);
                $query->whereDate('pb_tgl', $date);
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Invalid date format. Please use yyyy-mm-dd.']);
            }
        }
        $pengembalian = collect(); // Initialize an empty collection

        $pengembalian = $query->paginate(10);

        return view('superuser.laporan.pengembalian', compact('pengembalian'));
    }

    public function generatePDFPengembalian(Request $request)
    {
        $query = Pengembalian::with(['peminjaman.siswa', 'peminjaman.detailPeminjaman.barangInventaris']);

        if ($request->filled('siswa')) {
            $query->whereHas('peminjaman.siswa', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->siswa . '%');
            });
        }

        if ($request->filled('kembali_status')) {
            $query->where('kembali_status', $request->kembali_status);
        }
        if ($request->filled('kembali_tgl')) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $request->pb_tgl);
                $query->whereDate('pb_tgl', $date);
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Invalid date format. Please use yyyy-mm-dd.']);
            }
        }
        $pengembalian = $query->get(); // Ambil semua data


        $pdf = FacadePdf::loadView('pdf.pengembalian', compact('pengembalian'));

        return $pdf->download('pengembalian.pdf');
    }

}
