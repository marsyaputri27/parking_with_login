<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KasirController;




Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/scan', [ParkingController::class, 'scan'])->name('parking.scan');
Route::get('/index', [ParkingController::class, 'index'])->name('parking.index');        
Route::get('/report', [ParkingController::class, 'report'])->name('parking.report');  
Route::get('/print-direct/{id}', [PrintController::class, 'printReceipt'])->name('print.direct');
Route::get('/receipt-pdf/{id}', [PrintController::class, 'downloadPDF'])->name('receipt.pdf');
Route::get('/form', [ParkingController::class, 'showForm'])->name('parking.form');
Route::post('/generate-qr', [ParkingController::class, 'generateQr'])->name('parking.generate');
Route::get('/print/qr/{id}', [PrintController::class, 'printQr'])->name('print.qr');
Route::post('/fill-from-qr', [ParkingController::class, 'fillFromQr'])->name('parking.fillFromQr');
Route::get('/receipt/{id}', [ParkingController::class, 'receipt'])->name('parking.receipt');
Route::get('/setting', [SettingController::class, 'setting'])->name('parking.setting');
Route::put('/Update', [SettingController::class, 'update'])->name('parking.update');
Route::get('/report/export-pdf', [PrintController::class, 'exportReportPDF'])->name('report.export.pdf');

Route::middleware(['auth'])->group(function () {// ini hanya yang login saja boleh akses
    // Hanya admin yang bisa akses route ini
    Route::middleware(['admin'])->group(function () {
        Route::resource('kasir', KasirController::class);
        // nah route::resource ini itu maksudnya paket lengkap akses 1 controller, jadi laravel tu otomatis isiin rouute ada edit,create dan delate
    });
});

Route::get('/open-gate', function (Request $request) {
    try {
        // Panggil NodeJS bridge di port 4000
        $response = Http::get('http://127.0.0.1:4000/open-gate');
        return $response->json();
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal komunikasi dengan Bridge: ' . $e->getMessage()
        ], 500);
    }
});
