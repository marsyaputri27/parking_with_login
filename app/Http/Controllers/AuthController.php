<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request) // nah ini ketika kita klik login 
    {
        $credentials = $request->only('username', 'password'); // nah ini itu yang di ambil itu cuman 2 ini aja di simpan di credentials

        if (Auth::attempt($credentials)) { // ni identisikasi apakah password itu cocock atau ndak 
            $user = Auth::user();// berdasarkan role nya 
    
            // ADMIN akses penuh
            if ($user->role === 'admin' && $request->role === 'admin') {
                return redirect()->route('parking.report');
            }

            // KASIR
            if ($user->role === 'kasir' && $request->role === 'kasir') {
                if ($request->gate === 'masuk') {
                    // simpan ke session
                    session([
                        'gate' => 'masuk',
                        'jenis_kendaraan' => $request->jenis_kendaraan,
                    ]);
                    return redirect()->route('parking.form');
                } else {
                    session(['gate' => 'keluar']);
                    return redirect()->route('parking.scan');
                }
            }
        }

        return back()->withErrors(['username' => 'Login gagal, periksa username & password.']); // ini kalo tidak sesuai
    }

    public function logout(Request $request)
    {
        Auth::logout();// paling auttentifikasian suruh keluarin user itu 
        $request->session()->invalidate(); // id session invalid
        // $request->session()->regenerateToken();
        session()->flush(); // ini itu untuk bbersihin data
        return redirect()->route(route: 'login');
    }
}
