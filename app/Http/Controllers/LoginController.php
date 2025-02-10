<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);
    
        // Cari user berdasarkan nama
        $user = User::where('name', $request->name)->first();
    
        if ($user && $user->password === md5($request->password)) {
            // Login pengguna
            Auth::login($user);
    
            // Redirect ke halaman yang diinginkan jika login berhasil
            return redirect()->intended('dashboard')->with('success', 'Login berhasil');
        }
    
        // Jika login gagal
        return back()->withErrors([
            'error' => 'Nama atau password salah.',
        ]);
    }

    public function view()
    {
        return view('dashboard');
    }
}
