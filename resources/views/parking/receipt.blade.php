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
    <tr><td class="label">Plat</td><td>: {{ $trx->plate }}</td></tr>
    <tr><td class="label">Masuk</td><td>: {{ $trx->entry_time->format('d-m-Y H:i') }}</td></tr>
    <tr><td class="label">Keluar</td><td>: {{ $trx->exit_time->format('d-m-Y H:i') }}</td></tr>
    <tr><td class="label">Durasi</td><td>: {{ $trx->duration_hours }} jam</td></tr>
  </table>

  <div class="line"></div>
  <p class="total">Total Bayar: {{ $currency }}</p>
  <div class="line"></div>

  <p class="footer">Terima Kasih<br>Selamat Jalan</p>
@endsection

@section('scripts')
<script>
document.addEventListener("keydown", async function(e) {
    if (e.key === "Enter") {
        e.preventDefault();

        let trxId = {{ $trx->id }};

        try {
            // Cetak nota (Laravel)
            await fetch(`/print-direct/${trxId}`);
            console.log("🖨 Nota dicetak");

            // Buka plang (Laravel → Node → Arduino)
            let res = await fetch(`/open-gate?trxId=${trxId}`);
            let data = await res.json();
            console.log("Gateway response:", data);

            // kembali
             window.location.href = "{{ route('parking.scan') }}";

        } catch (err) {
            console.error("❌ Gagal eksekusi:", err);
        }
    }
});
</script>
@endsection

