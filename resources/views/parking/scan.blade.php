@extends('layouts.app')

@section('title', 'Scan Tiket Parkir')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8"> {{-- ukuran card --}}
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">
          <h3 class="text-center mb-4">Scan Tiket Parkir</h3>

          <form action="{{ route('parking.fillFromQr') }}" method="POST">
            @csrf

            {{-- Input QR / Barcode --}}
          <div class="mb-4">
            <label for="qr_raw" class="form-label fs-5 fw-semibold">
              Masukkan / Scan Barcode atau QR Code:
            </label>
            <input type="text"name="qr_raw" id="qr_raw" class="form-control form-control-lg text-center" placeholder="Arahkan scanner ke kolom ini..." autofocus>
          </div>

            {{-- Input Plat Nomor Manual --}}
          <div class="mb-4">
            <label for="plate" class="form-label fs-5 fw-semibold">
              Atau Masukkan Plat Nomor Kendaraan:
            </label>
            <input type="text" name="plate" id="plate" class="form-control form-control-lg text-center" placeholder="Contoh: DK 1234 AB">
          </div>

            {{-- Pesan error kalau ada --}}
            @if(session('error'))
              <div class="alert alert-danger mt-3">
                {{ session('error') }}
              </div>
            @endif

            {{-- Tombol manual submit (opsional) --}}
            <div class="d-grid mt-4">
              <button type="submit" class="btn btn-primary btn-lg">
                Proses Nota
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const qrInput = document.getElementById('qr_raw'); // nah kan qr_raw itu kan id dari input buat id scan, nah sekarang ambil itu id yang hasil scan pindah simpen di qrInput

    // Auto fokus setelah halaman siap
    setTimeout(() => {
        qrInput.focus();
        qrInput.select(); // hapus isi lama kalau ada
    }, 500); // kasih jeda 0.5 detik biar pasti ke-fokus

    // jalankan fungsi setiap kali isi input berubah
    qrInput.addEventListener('input', function () {
        if (this.value.trim() !== '') { // kalo ada yang diinput hapus spasinya
            this.form.submit(); // naah kan kita scan trus langsung masuk ke nota tanpa harus pencet tombol ataupun enter
        }
    });

    // kalau scanner kasih ENTER
    qrInput.addEventListener('keydown', function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            if (this.value.trim() !== '') {
                this.form.submit();
            }
        }
    });

    // kalau scanner mode paste
    qrInput.addEventListener('change', function () {// nah ini kalo paste
        if (this.value.trim() !== '') {// nah hapus spasi nya
            this.form.submit();
        }
    });
});
</script>
@endsection

