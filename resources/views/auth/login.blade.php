@extends('layouts.auth')

@section('title', 'Login Parking System')

@section('content')
  <h3>Login Parking System</h3>

  {{-- setiap error disini dia muncul dan dengan desain ini  --}}
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('login.post') }}"> <!--post itu kirim data ke server--->
    @csrf
    <div class="mb-3">
      <label class="form-label">Username</label>
      <input type="text" name="username" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Role</label>
      <select name="role" id="role" class="form-select" onchange="toggleKasirOptions(); toggleKendaraan();">
        <!-- nah ini itu memanggil fungsion itu dua tapi pakek nya satu dulu yang togglekasiroptions nah disana berisi jika dia pilih kasir mana akan menampilakan gate saja nah karena aku disini juga panggil togglekendaraan maka keliatan juga tu 
        jenis kendaraannya tapi kaloo dia udah milih gate keluar maka hilang jenis kendaraannya --->
        <option value="admin">Admin</option>
        <option value="kasir">Kasir</option>
      </select>
    </div>

    {{-- Kasir Options --}}
    <div id="kasir-options" style="display:none;">
      <div class="mb-3">
        <label class="form-label">Gate</label>
        <select name="gate" id="gate" class="form-select" onchange="toggleKendaraan()">
          <option value="masuk">Masuk</option>
          <option value="keluar">Keluar</option>
        </select>
      </div>

      <div id="kendaraan-options" style="display:none;">
        <div class="mb-3">
          <label class="form-label">Jenis Kendaraan</label>
          <select name="jenis_kendaraan" class="form-select">
            <option value="motor">Motor</option>
            <option value="mobil">Mobil</option>
          </select>
        </div>
      </div>
    </div>

    <button type="submit" class="btn btn-primary w-100">Login</button>
  </form>
@endsection


@push('scripts')
<script>
  function toggleKasirOptions() {
    const role = document.getElementById('role').value;
    document.getElementById('kasir-options').style.display = role === 'kasir' ? 'block' : 'none'; // nah ini maksudnya begini ya marsya 
  // kalo misalnya di role itu kamu pilih kasir nanti ada pilihan gate dan jenis kendaraan lagi yang muncul 
  // maksud script itu ambil dulu id dengan nama role nah setelah itu liat ni kalo role kasir maka ambil id dengan kasir-options yang awalnya none jadi block
  }
  function toggleKendaraan() {
    const role = document.getElementById('role').value;
    const gate = document.getElementById('gate') ? document.getElementById('gate').value : null;
    if (role === 'kasir' && gate === 'masuk') {
      document.getElementById('kendaraan-options').style.display = 'block';
    } else {
      document.getElementById('kendaraan-options').style.display = 'none';
    }
  }
  window.onload = function() { //ini saat pertama kali aku bukak udah langsung jalan kedua fungsi ini 
    toggleKasirOptions();
    toggleKendaraan();
  }
</script>
@endpush
