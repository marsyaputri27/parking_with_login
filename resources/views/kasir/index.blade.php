@extends('layouts.app')

@section('title','Kelola Akun Kasir')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">

      <!---nah ini alart---->
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
        
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
          <h3>Daftar Akun Kasir</h3>
          <a href="{{ route('kasir.create') }}" class="btn btn-primary">Tambah Kasir</a><br><br>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>No</th>
                <th>Username</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($kasirs as $index => $kasir) <!---ini perulangannya--->
              <tr>
                <td>{{ $index+1 }}</td> <!--nah karena mulai nya dari 0 maka tambah satu--->
                <td>{{ $kasir->username }}</td>
                <td>
                  <a href="{{ route('kasir.edit',$kasir->id) }}" class="btn btn-warning btn-sm">Edit</a>
                  <form action="{{ route('kasir.destroy',$kasir->id) }}" method="POST" class="d-inline">
                    <!---nah disini pakek post dulu nanti setelah itu pakek delete---->
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus akun ini?')">Hapus</button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
