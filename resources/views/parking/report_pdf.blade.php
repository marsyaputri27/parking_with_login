<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Parkir</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background: #eee; }
        h2 { text-align: center; margin-bottom: 10px; }
        .total { margin-top: 20px; font-size: 14px; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Laporan Transaksi Parkir</h2>
    @if($date)
        <p>Tanggal: {{ $date }}</p>
    @else
        <p>Semua Tanggal</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Plat</th>
                <th>Masuk</th>
                <th>Keluar</th>
                <th>Durasi (jam)</th>
                <th>Biaya (Rp)</th>
                <th>Waktu Simpan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($list as $trx)
                <tr>
                    <td>{{ $trx->id }}</td>
                    <td>{{ $trx->plate }}</td>
                    <td>{{ $trx->entry_time ? $trx->entry_time->format('d-m-Y H:i') : '-' }}</td>
                    <td>{{ $trx->exit_time ? $trx->exit_time->format('d-m-Y H:i') : 'Belum Keluar' }}</td>
                    <td>{{ $trx->duration_hours ?? '-' }}</td>
                    <td>{{ $trx->amount ? number_format($trx->amount, 0, ',', '.') : '-' }}</td>
                    <td>{{ $trx->created_at ? $trx->created_at->format('d-m-Y H:i') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="total">Total Pemasukan: Rp {{ $total }}</p>

@if(!empty($chartImage))
    <h3 style="text-align:center; margin-top:20px;">Grafik Pemasukan</h3>
    <div style="text-align:center; margin-top:10px;">
        <img src="{{ $chartImage }}" alt="Grafik Pemasukan" style="max-width:100%; height:auto;">
    </div>
@endif

</body>
</html>
