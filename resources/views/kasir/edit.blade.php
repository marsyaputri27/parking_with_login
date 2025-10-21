@extends('layouts.app')

@section('title','Edit Kasir')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">

          {{-- Judul --}}
          <h3 class="text-center mb-4"> Edit Akun Kasir</h3>

          {{-- Form Edit --}}
          <form action="{{ route('kasir.update',$kasir->id) }}" method="POST" class="row g-3"> <!--nah di dlama html itu hanya ada method post dan gate--->
            @csrf @method('PUT')
            <!--di Laravel, ada cara “menyamar” (spoofing) method, jadi laravel menganggap dengan method put bukan post--->
            <div class="col-12">
              <label class="form-label fw-semibold">Username</label>
              <input type="text" name="username" value="{{ $kasir->username }}" class="form-control form-control-lg" required>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Password (Kosongkan jika tidak diubah)</label>
              <input type="password" name="password" class="form-control form-control-lg">
            </div>
            <div class="col-12 text-end">
              <a href="{{ route('kasir.index') }}" class="btn btn-secondary btn-lg">Kembali</a>
              <button type="submit" class="btn btn-primary btn-lg">Update</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
