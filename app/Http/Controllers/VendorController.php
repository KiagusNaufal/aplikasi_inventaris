<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendor = Vendor::all();
        return view('superuser.referensi.vendor', compact('vendor'));
    }

    public function view()
{
    $vendor = Vendor::all();
    return view('superuser.referensi.vendor', compact('vendor'));
}
    public function store(Request $request)
    {
        try{
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $vendor = new Vendor();
        $vendor->name = $request->name;
        $vendor->save();


        return redirect()->route('vendor');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        return back()->withErrors(['message' => 'Terjadi kesalahan saat menyimpan data.', 'error' => $e->getMessage()])->withInput();
    }
    }

    public function show($id)
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json([
                'message' => 'Vendor tidak ditemukan'
            ], 404);
        }

        return response()->json($vendor);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json([
                'message' => 'Vendor tidak ditemukan'
            ], 404);
        }

        $vendor->name = $request->name;
        $vendor->save();

        return redirect()->route('vendor');
    }

    public function destroy($id)
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json([
                'message' => 'Vendor tidak ditemukan'
            ], 404);
        }

        $vendor->delete();

        return response()->json([
            'message' => 'Vendor berhasil dihapus'
        ]);
    }
}
