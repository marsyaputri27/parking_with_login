<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KasirController extends Controller
{
    // Menampilkan daftar kasir
    public function index()
    {
        $kasirs = User::where('role', 'kasir')->get();
        return view('kasir.index', compact('kasirs'));
    }

    // Form tambah kasir
    public function create()
    {
        return view('kasir.create');
    }

    // Proses simpan kasir baru
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'kasir',
            'active' => true
        ]);

        return redirect()->route('kasir.index')->with('success', 'Kasir berhasil ditambahkan');
    }

    // Form edit kasir
    public function edit(User $kasir)
    {
        return view('kasir.edit', compact('kasir'));
    }

    // Proses update kasir
    public function update(Request $request, User $kasir)
    {
        $request->validate([
            'username' => 'required|unique:users,username,' . $kasir->id,
        ]);

        $kasir->update([
            'username' => $request->username,
            'password' => $request->password ? Hash::make($request->password) : $kasir->password,
        ]);

        return redirect()->route('kasir.index')->with('success', 'Kasir berhasil diupdate');
    }

    // Hapus kasir
    public function destroy(User $kasir)
    {
        $kasir->delete();
        return redirect()->route('kasir.index')->with('success', 'Kasir berhasil dihapus');
    }
}
