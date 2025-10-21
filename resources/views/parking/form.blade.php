@extends('layouts.app')

@section('title', 'Form Karcis Parkir')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">
          <h3 class="text-center mb-4">Form Karcis Parkir</h3>

          {{-- alert jika ada error --}}
          @if ($errors->any())
            <div class="alert alert-danger">
              {{ $errors->first() }}
            </div>
          @endif

          <form method="POST" action="{{ route('parking.generate') }}"> <!--nah nanti ke web dulu baru ke parkingcontroller--->
            @csrf

            {{-- Plat Kendaraan --}}
            <div class="mb-4">
              <label for="plate" class="form-label fs-5 fw-semibold">Plat Kendaraan:</label>
              <input type="text" name="plate" id="plate" 
                  class="form-control form-control-lg text-center" 
                  placeholder="Contoh: DK 1234 AB" required autofocus>

            </div>

            {{-- Jenis Kendaraan otomatis dari session --}}
            <input type="hidden" name="vehicle_type" value="{{ session('jenis_kendaraan') }}">

            {{-- Info gate & kendaraan --}}
            <div class="mb-3">
              <p><strong>Gate:</strong> {{ ucfirst(session('gate')) }}</p><!--nah ucfirst itu membuat huruf pertama besar-->
              <p><strong>Jenis Kendaraan:</strong> {{ ucfirst(session('jenis_kendaraan')) }}</p>
            </div>

            {{-- Tombol --}}
            <div class="d-grid">
              <button type="submit" class="btn btn-primary btn-lg">
                Cetak Karcis Parkir
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
