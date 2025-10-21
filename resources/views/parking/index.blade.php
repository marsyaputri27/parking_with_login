@extends('layouts.app')

@section('title', 'Detail Parkir')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">
          <h3 class="text-center mb-4">Detail Transaksi Parkir</h3>

          {{-- Ganti form dengan div biasa --}}
          <div class="no-print">
            <div class="row g-4">
              <div class="col-md-6">
                <label class="form-label fs-5 fw-semibold">Plat Nomor</label>
                <input type="text" class="form-control form-control-lg" 
                       value="{{ $trx->plate ?? old('plate') }}" readonly >
              </div>

              <div class="col-md-6">
                <label class="form-label fs-5 fw-semibold">Jenis Kendaraan</label>
                <input type="text" class="form-control form-control-lg" 
                       value="{{ $trx->vehicle_type ?? old('vehicle') }}" readonly >
              </div>

              <div class="col-md-6">
                <label class="form-label fs-5 fw-semibold">Jam Masuk</label>
                <input type="datetime-local" class="form-control form-control-lg" 
                       value="{{ $trx->entry_time->format('Y-m-d\TH:i') ?? old('entry') }}" readonly >
              </div>

              <div class="col-md-6">
                <label class="form-label fs-5 fw-semibold">Jam Keluar</label>
                <input type="datetime-local" class="form-control form-control-lg" 
                       value="{{ $exit_time->format('Y-m-d\TH:i') ?? old('exit') }}" readonly >
              </div>
            </div>

            {{-- Hasil hitungan --}}
            <div class="mt-4 p-4 border rounded bg-light">
              <p class="fs-5 mb-2"><b>Durasi:</b> {{ $duration_hours ?? optional($trx)->duration_hours ?? 0 }} jam</p>
              <p class="fs-5"><b>Total Bayar:</b> 
                {{ $currency ?? (optional($trx)->amount ? 'Rp '.number_format(optional($trx)->amount, 0, ',', '.') : 'Rp 0') }}
              </p>
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-grid mt-4">
              <a href="{{ route('parking.receipt', $trx->id) }}" class="btn btn-primary btn-lg">
                Lihat Struk
              </a>
            </div>
          </div>
         
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    let inputPlate = document.querySelector(".form-control-lg");

    inputPlate.addEventListener("keydown", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            
            let plate = inputPlate.value; // AMBIL NILAI YANG DIKETIK USER
            console.log("Plate scanned:", plate);

            // Redirect ke controller untuk cari transaksi berdasarkan plate
            window.location.href = "/receipt/search/" + encodeURIComponent(plate);
        }
    });
});
</script>
@endsection

