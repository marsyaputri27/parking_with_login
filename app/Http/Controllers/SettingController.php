<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function setting()
    {
        // Ambil data pertama (karena setting hanya ada 1 record saja)
        $setting = Setting::first();
        return view('parking.setting', compact('setting'));
    }

    public function update(Request $request) // nah disini itu aku hanya pakek update saja jadi hanya ada satu data di setting 
    {
        // Validasi input
        $request->validate([
            'ticket_type'        => 'required|in:qrcode,barcode',
            'company_name'       => 'nullable|string|max:100',
            'address'            => 'nullable|string|max:255',
            'phone'              => 'nullable|string|max:30',
            'email'              => 'nullable|email',
            'logo'               => 'nullable|image|max:2048',
            'tarif_awal_mobil'   => 'required|integer|min:0',
            'tarif_perjam_mobil' => 'required|integer|min:0',
            'tarif_awal_motor'   => 'required|integer|min:0',
            'tarif_perjam_motor' => 'required|integer|min:0',
        ]);

        // Ambil data setting, kalau belum ada buat baru
        $setting = Setting::first();
        if (!$setting) {
            $setting = Setting::create([]);
        }

        // Data yang akan disimpan
        $data = $request->only([
            'ticket_type',
            'company_name',
            'address',
            'phone',
            'email',
            'tarif_awal_mobil',
            'tarif_perjam_mobil',
            'tarif_awal_motor',
            'tarif_perjam_motor'
        ]);

        // Kalau ada logo diupload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $path;
        }

        // Update data
        $setting->update($data);

        return redirect()->back()->with('success', 'Setting berhasil disimpan!');
    }
}
