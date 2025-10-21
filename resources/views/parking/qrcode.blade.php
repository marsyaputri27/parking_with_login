@extends('layouts.note')

@section('content')
  @if($setting)
    <div class="ticket-header">
      @if($setting->logo)
        <img src="{{ asset('storage/'.$setting->logo) }}" class="ticket-logo">
      @endif
      <div class="company-info">
        <span class="company-name">Parkir {{ $setting->company_name }}</span>
        <span>{{ $setting->address }}</span><br>
        <span>Telp: {{ $setting->phone }} | Email: {{ $setting->email }}</span>
      </div>
    </div>
  @endif

  <table>
    <tr>
      <td class="label">Plat</td>
      <td>: {{ $trx->plate }}</td>
    </tr>
    <tr>
      <td class="label">Kendaraan</td>
      <td>: {{ $trx->vehicle_type }}</td>
    </tr>
    <tr>
      <td class="label">Masuk</td>
      <td>: {{ $trx->entry_time->format('d-m-Y H:i') }}</td>
    </tr>
  </table>

  <div class="line"></div>

  <div class="barcode">
    <p>Kode Transaksi</p>
    <!---nah ini itu di atur di parkingcontroller kalo setting nya barcode maka muncul lah barcode--->
    <div class="transaksid1">{!! $barcode1D !!}</div>
    <div class="transaksid2">{!! $qrcode !!}</div>
  </div>

  <div class="line"></div>
  <p class="footer">Terima Kasih<br>Simpan tiket ini</p>
@endsection

@section('scripts')
<script>
document.addEventListener("keydown", async function(e) { // pasang pendenganran jika mendengar tombol enter di tekan
  //dimana di tekan itu dalam artian sebelum di lepas
    if (e.key === "Enter") { // pengecekan
        e.preventDefault();

        let trxId = {{ $trx->id }};

        try {
            // Cetak nota (Laravel)
            await fetch(`/print/qr/${trxId}`); // nah ini itu panggil endpoint nya nah setelah itu nanti di browser kan di url tu nanti keliatannya adalah 
            //print/qr/id nya misal id nya itu 45 gitu misalnya 
          
            console.log("QR dicetak");

            // Buka plang (Laravel → Node → Arduino) await fetch itu artinya panggil endpoint
            let res = await fetch(`/open-gate?trxId=${trxId}`); // nah ini itu maksud nya panggil open-gate sesuai dengan id yang masuk 
            let data = await res.json(); // respon dengan json dari hasil objek js 
            console.log("Gateway response:", data);

            window.location.href = "{{ route('parking.form') }}"; // nah ini itu maksudnya ketika saya sudah klik enter maka ada 3 hal yang terjadi print nota,buka gate dan juga kembali ke halaman form

        } catch (err) {
            console.error("❌ Gagal eksekusi:", err);
        }
    }
});
</script>
@endsection