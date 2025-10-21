@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">

          <h3 class="text-center mb-4">Pengaturan Sistem</h3>

          {{-- Notifikasi sukses --}}
          @if(session('success'))
            <div class="alert alert-success">
              {{ session('success') }}
            </div>
          @endif

          {{-- Form Pengaturan --}}
          <form action="{{ route('parking.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- penting kalau route update pakai PUT, nah jadinya laravel saya menggunakan put  --}}

            {{-- Jenis Tiket --}}
            <div class="mb-4">
              <label class="form-label fs-5 fw-semibold">Jenis Tiket</label>
              <select name="ticket_type" class="form-select form-select-lg">
                <option value="qrcode" {{ $setting && $setting->ticket_type == 'qrcode' ? 'selected' : '' }}>QR Code</option>
                <option value="barcode" {{ $setting && $setting->ticket_type == 'barcode' ? 'selected' : '' }}>Barcode</option>
              </select>
            </div>

            {{-- Nama Perusahaan --}}
            <div class="mb-4">
              <label class="form-label fs-5 fw-semibold">Nama Perusahaan</label>
              <input type="text" name="company_name" class="form-control form-control-lg"
                     value="{{ $setting->company_name ?? '' }}">
            </div>

            {{-- Alamat --}}
            <div class="mb-4">
              <label class="form-label fs-5 fw-semibold">Alamat</label>
              <input type="text" name="address" class="form-control form-control-lg"
                     value="{{ $setting->address ?? '' }}">
            </div>

            {{-- Telepon --}}
            <div class="mb-4">
              <label class="form-label fs-5 fw-semibold">No. Telepon</label>
              <input type="text" name="phone" class="form-control form-control-lg"
                     value="{{ $setting->phone ?? '' }}">
            </div>

            {{-- Email --}}
            <div class="mb-4">
              <label class="form-label fs-5 fw-semibold">Email</label>
              <input type="email" name="email" class="form-control form-control-lg"
                     value="{{ $setting->email ?? '' }}">
            </div>

            {{-- Logo --}}
            <div class="mb-4">
              <label class="form-label fs-5 fw-semibold">Logo</label><br>
              @if($setting && $setting->logo)
                <img src="{{ asset('storage/'.$setting->logo) }}" alt="Logo" class="mb-3 d-block" height="80">
              @endif
              <input type="file" name="logo" class="form-control form-control-lg">
            </div>

            {{-- Tarif Mobil ini memang 2 jam jadi tinggal di sesuaikan saja --}}
            <div class="mb-4">
              <label class="form-label fs-5 fw-semibold">Tarif 2 Jam Pertama (Mobil)</label>
              <input type="number" name="tarif_awal_mobil" class="form-control form-control-lg"
                     value="{{ $setting->tarif_awal_mobil ?? 3000 }}">
            </div>

            <div class="mb-4">
              <label class="form-label fs-5 fw-semibold">Tarif Selanjutnya (Mobil)</label>
              <input type="number" name="tarif_perjam_mobil" class="form-control form-control-lg"
                     value="{{ $setting->tarif_perjam_mobil ?? 2000 }}">
            </div>

            {{-- Tarif Motor --}}
            <div class="mb-4">
              <label class="form-label fs-5 fw-semibold">Tarif 2 Jam Pertama Motor</label>
              <input type="number" name="tarif_awal_motor" class="form-control form-control-lg"
                     value="{{ $setting->tarif_awal_motor ?? 2000 }}">
            </div>

            <div class="mb-4">
              <label class="form-label fs-5 fw-semibold">Tarif Selanjutnya (Motor)</label>
              <input type="number" name="tarif_perjam_motor" class="form-control form-control-lg"
                     value="{{ $setting->tarif_perjam_motor ?? 1000 }}">
            </div>

            {{-- Tombol Simpan --}}
            <div class="d-grid">
              <button type="submit" class="btn btn-primary btn-lg">
                 Simpan Pengaturan
              </button>
            </div>

          </form>
          
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
