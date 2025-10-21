@extends('layouts.app')

@section('title', 'Laporan Parkir')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">

          {{-- Judul --}}
          <h3 class="text-center mb-4">Laporan Transaksi Parkir</h3>

          {{-- Form filter tanggal --}}
          <form method="GET" action="{{ route('parking.report') }}" class="row g-3 align-items-end mb-4 no-print">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Dari Tanggal</label>
              <input type="date" name="start_date" value="{{ $start_date }}" class="form-control form-control-lg">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Sampai Tanggal</label>
              <input type="date" name="end_date" value="{{ $end_date }}" class="form-control form-control-lg">
            </div>
            <div class="col-md-3">
              <button class="btn btn-primary btn-lg w-100">Tampilkan</button>
            </div>
          </form>

          {{-- Tabel Laporan --}}
          <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
              <thead class="table-dark">
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
                    <td colspan="7" class="text-center text-muted">Belum ada transaksi pada periode ini.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          {{-- Total Pemasukan --}}
          <div class="alert alert-info mt-4 fs-5">
            <b>Total Pemasukan: {{ $total }}</b>
          </div>

          {{-- Tombol Cetak --}}
          <form method="GET" action="{{ route('report.export.pdf') }}" id="exportForm">
    <input type="hidden" name="start_date" value="{{ request('start_date') }}"> <!---kirim ulang tanggal awal agar PDF memakai periode yang sama seperti di atas ---->
    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
    <input type="hidden" name="chart_image" id="chartImageInput"> <!---nantinya diisi gambar grafik--->
    <button type="submit" class="btn btn-danger btn-lg">
      Export PDF
    </button>
</form>


          {{-- Diagram Garis --}}
          <div class="mt-5">
            <h4 class="text-center">Grafik Pemasukan</h4>
            <div style="max-width: 700px; height: 350px; margin: 0 auto;">
              <canvas id="incomeChart"></canvas>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
   {{-- Load library Chart.js --}}
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   {{-- Load plugin datalabels --}}
   <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

   {{-- Script Chart --}}
   <script>
      document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('incomeChart'); // ambil id dengan nama incomechart

        const chart = new Chart(ctx, {
          type: 'line', // jenis diagram
          data: {
            labels: {!! json_encode(array_keys($chartData)) !!}, // label sumbu X itu tanggal
            datasets: [{
              label: 'Pemasukan Harian',
              data: {!! json_encode(array_values($chartData), JSON_NUMERIC_CHECK) !!}, //nilai masuk nya per tanggal
              borderColor: 'rgba(75, 192, 192, 1)',
              backgroundColor: 'rgba(75, 192, 192, 0.2)',
              tension: 0.3,
              fill: true,
              borderWidth: 2,
              pointRadius: 4,
              pointHoverRadius: 6
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { 
                display: true,
                position: 'top',
                labels: {
                  boxWidth: 15,
                  font: { size: 12 }
                }
              },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    return 'Rp ' + context.raw.toLocaleString();
                  }
                }
              },
              //label tanggal di atas titik
              datalabels: {
                align: 'top',
                anchor: 'end',
                formatter: function(value, context) {
  if (value === 0) {
    return null; // jangan tampilkan apa-apa kalau value 0
  }
  return context.chart.data.labels[context.dataIndex];
},

                font: {
                  size: 10,
                  weight: 'bold'
                }
              }
            },
            scales: {
              x: {
                ticks: {
                  maxRotation: 0,
                  autoSkip: true,
                  font: { size: 12 }
                }
              },
              y: {
                beginAtZero: true,
                ticks: {
                  callback: function(value) {
                    return 'Rp ' + value.toLocaleString();
                  },
                  font: { size: 12 }
                }
              }
            }
          },
          plugins: [ChartDataLabels] // aktifkan plugin, mengambil gambar grafik saat export
        });

        //sebelum submit form, isi hidden input dengan base64 dari chart
        document.getElementById("exportForm").addEventListener("submit", function(e) {
            let chartImage = chart.toBase64Image();// ambil gambar canvas dalam format data URL base 64
            document.getElementById("chartImageInput").value = chartImage;
        });
      });
   </script>
@endpush
