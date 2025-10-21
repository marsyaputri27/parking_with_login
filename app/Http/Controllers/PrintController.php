<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParkingTransaction;
use Mike42\Escpos\Printer; // library sari escpos
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Barryvdh\DomPDF\Facade\Pdf;
use Milon\Barcode\DNS1D;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use lepiaf\SerialPort;
use Carbon\Carbon;


class PrintController extends Controller
{
   public function printReceipt($id) // jalankan fungsi ini berdasarkan id
{
    try {
        $trx = ParkingTransaction::findOrFail($id); // sambungkan berdasarkan id
        $setting = Setting::first();


        $connector = new WindowsPrintConnector("smb://localhost/POS58 Printer");// nama printer
        $printer = new Printer($connector);

        // ========== CETAK HEADER PERUSAHAAN ==========
        if ($setting) {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true); // teks tengah
            $printer->text("Parkir {$setting->company_name} \n");
            $printer->setEmphasis(false); // normal lagi tidak bold
            $printer->text(($setting->address ?? '') . "\n");
            $printer->text("Telp: {$setting->phone} | Email: {$setting->email}\n");
            $printer->feed(1);
        }

        $printer->setJustification(Printer::JUSTIFY_LEFT); // rata kiri
        $printer->text(str_pad("Plat", 15) . ": {$trx->plate}\n"); //str_pad("Plat", 15)teks "Plat" dilebarkan jadi 15 karakter biar rapi
        $printer->text(str_pad("Jenis", 15) . ": {$trx->vehicle_type}\n");
        $printer->text(str_pad("Masuk", 15) . ": {$trx->entry_time}\n");
        $printer->text(str_pad("Keluar", 15) . ": {$trx->exit_time}\n");
        $printer->text(str_pad("Durasi", 15) . ": {$trx->duration_hours} Jam\n");
       

        $printer->text(str_repeat("-", 32) . "\n");

        $printer->setEmphasis(true); // nah ini jadiin bold
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("TOTAL: Rp " . number_format($trx->amount, 0, ',', '.') . "\n");
        // pakek format rupiah yang udah ada di parkingcontroller 
        $printer->setEmphasis(false);// nah ini suud jadiin bold

        $printer->text(str_repeat("-", 32) . "\n");
        $printer->text("Terima kasih \n");
        $printer->text("Selamat Jalan\n\n");

        $printer->cut();
        $printer->close();

        return response()->json(['success' => true, 'message' => 'Struk berhasil dicetak']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

public function printQr($id)
{
    try {
        $trx = ParkingTransaction::findOrFail($id);
        $setting = Setting::first();

        $printerName = env('THERMAL_PRINTER', 'POS58 Printer');
        $connector = new WindowsPrintConnector("smb://localhost/{$printerName}");
        $printer   = new Printer($connector);

        // ===== CETAK HEADER PERUSAHAAN =====
        if ($setting) {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("Parkir {$setting->company_name} \n");
            $printer->setEmphasis(false);
            $printer->text(($setting->address ?? '') . "\n");
            $printer->text("Telp: {$setting->phone} | Email: {$setting->email}\n");
            $printer->feed(1);
        }

        // ===== CETAK DETAIL =====
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text(str_pad("Plat", 12) . ": {$trx->plate}\n");
        $printer->text(str_pad("Kendaraan", 12) . ": {$trx->vehicle_type}\n");
        $printer->text(str_pad("Masuk", 12) . ": {$trx->entry_time->format('d-m-Y H:i')}\n");
        $printer->feed();

        // ===== CETAK TIKET SESUAI SETTING =====
        $printer->setJustification(Printer::JUSTIFY_CENTER);

        if ($setting && $setting->ticket_type === 'barcode') {
            // Cetak BARCODE saja
            $printer->text("Kode Transaksi (BARCODE)\n");
            $printer->setBarcodeHeight(80);
            $printer->setBarcodeWidth(3);
            $printer->barcode("{B{$trx->id}}", Printer::BARCODE_CODE128);
            $printer->feed(2);

        } elseif ($setting && $setting->ticket_type === 'qrcode') {
            // Cetak QR CODE saja
            $printer->text("Kode Transaksi (QR)\n");
            $printer->qrCode((string)$trx->id, Printer::QR_ECLEVEL_L, 6);
            $printer->feed(2);
        }

        // ===== FOOTER =====
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Terima kasih\n");
        $printer->text("Simpan tiket ini\n\n");

        $printer->cut();
        $printer->close();

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil dicetak sesuai setting'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => "Gagal mencetak: " . $e->getMessage()
        ]);
    }
}

//   public function downloadPDF($id)
// {
//     $trx = ParkingTransaction::findOrFail($id);
//     $setting = Setting::first(); // Tambah ini!

//     // buat variabel currency (biar sama seperti di blade)
//     $currency = number_format($trx->amount, 0, ',', '.');

//     // load view receipt.blade.php
//     $pdf = Pdf::loadView('parking.receipt', compact('trx', 'currency', 'setting')); // Sekarang 'setting' ada

//     // langsung download
//     return $pdf->download("receipt-{$trx->plate}.pdf");
// }
public function exportReportPDF(Request $request)
{
    $startDate  = $request->get('start_date');
    $endDate    = $request->get('end_date');
    $chartImage = $request->get('chart_image'); //data url base64 hasil chart.toBase64Image() dari chart browser

    $query = ParkingTransaction::query();
//jika ada rentan tanggal
    if ($startDate && $endDate) {
        $start = Carbon::parse($startDate)->startOfDay();
        $end   = Carbon::parse($endDate)->endOfDay();
        $query->whereBetween('entry_time', [$start, $end]);
    }

    $list = $query->orderBy('entry_time', 'asc')->get();
    $total = $list->sum('amount');

    $pdf = Pdf::loadView('parking.report_pdf', [
        'list' => $list,
        'date' => $startDate && $endDate ? "$startDate s/d $endDate" : "Semua Data",
        'total' => number_format($total, 0, ',', '.'),
        'chartImage' => $chartImage
    ]);

    $filename = $startDate && $endDate 
        ? "laporan-parkir-{$startDate}-sd-{$endDate}.pdf" 
        : "laporan-parkir-semua.pdf";

    return $pdf->download($filename);
}
}