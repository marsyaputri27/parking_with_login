<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParkingTransaction;
use Carbon\Carbon; // library tanggal
use SimpleSoftwareIO\QrCode\Facades\QrCode; // untuk qr code
use Milon\Barcode\DNS1D; // untuk barcode
use App\Models\Setting;

class ParkingController extends Controller
{
    private function formatRupiah($number)// buat fungsi private untuk rupiah
    {
        return 'Rp ' . number_format($number, 0, ',', '.'); // ubah format angka ke rupiah
    }

    public function index()
{
    
    return view('parking.index');
     
}

        public function form()
    {
        // Halaman form input
        return view('parking.form');
    }

        public function scan()
    {
        // Halaman form input
        return view('parking.scan');
    }

    // Tampilkan form input kendaraan yang hanya isi plat kendaraan 
    public function showForm()
    {
        return view('parking.form');
    }

    // Simpan data awal setelah membuat form disini setelah itu nanti lanjut membuat qrcode
    // nah store berfungsi untuk menyimpan data yang sudah kita isi di file form dan simpan ke database sebelum di buatkan QR atau barcode
    public function store(Request $request)
    {
        $request->validate([
            'plate'        => 'required|string|max:20', // plate wajib diisi dengan string max 20
            'vehicle_type' => 'required|in:mobil,motor',// diisi dengan mobil atau motor
        ]);

       $parking = ParkingTransaction::create([ 
            'plate'         => strtoupper($request->plate), // strtoupper itu artinya konsisten pakek huruf kapital
            'vehicle_type'  => strtolower($request->vehicle_type),//strtolower itu konsisten dengan huruf kecil
            'entry_time'    => now(),//gunakan jam sekarang
            'qr_code'       => null,
            'duration_hours'=> 0,   // default
            'amount'        => 0,   // default
        ]);

        // setelah simpan, redirect ke receipt pake ID
        return redirect()->route('parking.receipt', ['id' => $parking->id]);
    }

    // Generate QR code setelah input data + simpan DB
public function generateQr(Request $request)
{
    // Validasi input
    $request->validate([
        'plate'        => 'required|string|max:20',
        'vehicle_type' => 'required|in:mobil,motor',
    ]);

    // Simpan transaksi baru
    $trx = ParkingTransaction::create([
        'plate'         => strtoupper($request->plate),
        'vehicle_type'  => strtolower($request->vehicle_type),
        'entry_time'    => now(),
        'duration_hours'=> 0,
        'amount'        => 0,
    ]);

    // panggil setting agar nanti bisa sesuaikan pengguna mau barcode atau qrcode
    $setting = Setting::first();

    $barcode1D = null;
    $qrcode    = null;

    if ($setting && $setting->ticket_type === 'barcode') {
        // Barcode 1D langsung di tampilakan dalam bentuk html menggunakan grtBarcodeHTML
        $barcode1D = (new DNS1D)->getBarcodeHTML((string)$trx->id, 'C39');
        
    } else {
        // QR Code — kita pastikan output sama-sama ID transaksi
        $qrcode = QrCode::size(150)->generate((string)$trx->id);
    }

    // Kirim ke view
    return view('parking.qrcode', [
        'trx'       => $trx,
        'barcode1D' => $barcode1D,
        'qrcode'    => $qrcode,
        'setting'   => $setting,
    ]);
}

//ini saat scan
public function fillFromQr(Request $request)
{
    $raw   = trim($request->qr_raw);
    $plate = trim($request->plate);

    $trx = null;

    if (!empty($raw)) { // cek apakah $raw isi
        // Kalau pakai QR/barcode -> ambil ID
        $id  = preg_replace('/[^0-9]/', '', $raw); //bersihkan semua karakter selain angka 0-9
        $trx = ParkingTransaction::find($id);// misalnya aku scan trus id nya 8 nanti di cariin di data base nya nanti kelihatan di index.blade.php
    } elseif (!empty($plate)) {
        // Kalau pakai plat nomor -> ambil transaksi terakhir dengan plat tsb
        $trx = ParkingTransaction::where('plate', $plate)->latest()->first();
    } else {
        return back()->with('error', "Silakan scan tiket atau masukkan plat nomor!");
    }

    if (!$trx) {
        return back()->with('error', "Data transaksi tidak ditemukan.");
    }

    // langsung redirect ke receipt berdasarkan ID transaksi
    return redirect()->route('parking.receipt', ['id' => $trx->id]);
}



    // Tampilkan receipt berdasarkan transaksi ID
public function receipt($id)
{
    $parking = ParkingTransaction::findOrFail($id); // sesuaikan dengan id

    $exitTime = $parking->exit_time ?? now();// jam keluar keluar pakek sekarang

    $hours = $exitTime->diffInHours($parking->entry_time); // jika belum 1 jam maka buletin jadi satu jam
    if ($hours === 0) {
        $hours = 1;
    }

    $biaya = $this->calculateAmount($hours, $parking->vehicle_type); //untuk biaya nya ambil di calculateAmount

    // update data transaksi
    $parking->exit_time      = $exitTime;
    $parking->duration_hours = $hours;
    $parking->amount         = $biaya;
    $parking->save();

    // ambil setting untuk dikirim ke view
    $setting = Setting::first();

    return view('parking.receipt', [
        'trx'            => $parking,
        'exit_time'      => $exitTime,
        'duration_hours' => $hours,
        'currency'       => $this->formatRupiah($biaya),
        'setting'        => $setting, // tambahkan ini
    ]);
}


public function report(Request $request)
{
    $start = $request->get('start_date');
    $end   = $request->get('end_date');

    $query = ParkingTransaction::query();

    if ($start && $end) { // ini kalo dia isiin start dan end
        // tambahkan jam biar data di hari terakhir tetap masuk
        $startDate = Carbon::parse($start)->startOfDay();// ini maksudnya jam 00.00.00
        $endDate   = Carbon::parse($end)->endOfDay();// ini maksudnya jam 23.29.29 jadi semua masuk 
        $query->whereBetween('entry_time', [$startDate, $endDate]);
    } else {
        //kalo diia gak isi tampilin bulan ini sampai akhir 
        $startDate = now()->startOfMonth();
        $endDate   = now()->endOfMonth();
        $query->whereBetween('entry_time', [$startDate, $endDate]);
    }

    $list = $query->orderBy('entry_time', 'asc')->get();

    $total = $list->sum('amount');

    //data untuk chart berdasarkan tanggal
$rawData = $list->groupBy(function ($item) {
    return $item->entry_time->format('Y-m-d');
})->map(function ($day) {
    return $day->sum('amount'); // nah nanti pertanggal nya itu hasil biaya nya di tambahkan
});

//buat array kosong untuk semua tanggal dari start sampai end
$chartData = []; // nanti di chart data ini akan di isi misal tanggal ini berapa penghasilannya 
$period = \Carbon\CarbonPeriod::create($startDate, $endDate); // membuat rentan tanggal secara otomatis misal kita pilih dari tanggal a sampai e, nah nanti otomatis dia liatai tanggal a.b.c.d.dan e gitu
foreach ($period as $date) {
    $key = $date->format('Y-m-d');
    $chartData[$key] = $rawData[$key] ?? 0; // isi bayaran di setiap tanggal nya kalo gak ada 0 aja
}

    return view('parking.report', [
    'list'       => $list,
    'total'      => $this->formatRupiah($total),
    'start_date' => $startDate->toDateString(),
    'end_date'   => $endDate->toDateString(),
    'chartData'  => $chartData, // sekarang array, bukan collection
]);

}

    private function calculateAmount(int $hours, string $vehicle): int // nah fungsi ini private dan mengambil durasi dan juga jenis kendaraan dan int itu artinya fungsi ini akan mengembalikan ke nilai angka
    {
           $setting = \App\Models\Setting::first(); // kita ambil data biaya yang di kenakan di menu setting

    // nah ini ambil tarif di setting dan jika belum isi maka gunakan angka yang ada sesudah ??
    $tarifAwalMobil   = $setting->tarif_awal_mobil ?? 3000;
    $tarifPerJamMobil = $setting->tarif_perjam_mobil ?? 2000;
    $tarifAwalMotor   = $setting->tarif_awal_motor ?? 2000;
    $tarifPerJamMotor = $setting->tarif_perjam_motor ?? 1000;

    if ($vehicle === 'mobil') {
        return $hours <= 2 ? $tarifAwalMobil : $tarifAwalMobil + ($hours - 2) * $tarifPerJamMobil;// 2 jam awal
    }

    if ($vehicle === 'motor') {
        return $hours <= 2 ? $tarifAwalMotor : $tarifAwalMotor + ($hours - 2) * $tarifPerJamMotor;
    }
    return 0;
    }
}
