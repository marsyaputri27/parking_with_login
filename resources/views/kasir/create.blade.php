@extends('layouts.app')

@section('title','Tambah Kasir')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">

          {{-- Judul --}}
          <h3 class="text-center mb-4">Tambah Akun Kasir</h3>

          {{-- Form Tambah --}}
          <form action="{{ route('kasir.store') }}" method="POST" class="row g-3">
            @csrf
            <div class="col-12">
              <label class="form-label fw-semibold">Username</label>
              <input type="text" name="username" class="form-control form-control-lg" required>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Password</label>
              <input type="password" name="password" class="form-control form-control-lg" required>
            </div>
            <div class="col-12 text-end">
              <a href="{{ route('kasir.index') }}" class="btn btn-secondary btn-lg">Kembali</a>
              <button type="submit" class="btn btn-success btn-lg">Simpan</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
