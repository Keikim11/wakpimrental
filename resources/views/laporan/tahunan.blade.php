@extends('layouts.app')

@section('title', 'Laporan Tahunan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Laporan Tahunan</h3>
        <div class="card-tools">
            <a href="{{ route('laporan.export.tahunan', ['tahun' => $tahun]) }}" class="btn btn-success">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('laporan.tahunan') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tahun">Pilih Tahun</label>
                        <select class="form-control" id="tahun" name="tahun">
                            @for($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $totalPenyewaan }}</h3>
                        <p>Total Penyewaan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                        <p>Total Pendapatan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>Rp {{ number_format($totalDenda, 0, ',', '.') }}</h3>
                        <p>Total Denda</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>Rp {{ number_format($totalPendapatan + $totalDenda, 0, ',', '.') }}</h3>
                        <p>Total Keseluruhan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Grafik Pendapatan Bulanan</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="pendapatanChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Grafik Jumlah Penyewaan Bulanan</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="penyewaanChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Penyewaan -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Penyewaan Aktif</span>
                        <span class="info-box-number">{{ $penyewaanAktif }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Penyewaan Selesai</span>
                        <span class="info-box-number">{{ $penyewaanSelesai }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-danger"><i class="fas fa-times-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Penyewaan Batal</span>
                        <span class="info-box-number">{{ $penyewaanBatal }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Sewa</th>
                        <th>Pelanggan</th>
                        <th>Mobil</th>
                        <th>Tanggal Sewa</th>
                        <th>Tanggal Kembali</th>
                        <th>Durasi</th>
                        <th>Total Biaya</th>
                        <th>Denda</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penyewaans as $penyewaan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>#{{ str_pad($penyewaan->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $penyewaan->pelanggan->nama }}</td>
                        <td>{{ $penyewaan->mobil->merk }} {{ $penyewaan->mobil->model }}</td>
                        <td>{{ \Carbon\Carbon::parse($penyewaan->tanggal_sewa)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($penyewaan->tanggal_kembali_rencana)->format('d/m/Y') }}</td>
                        <td>{{ $penyewaan->durasi_sewa }} hari</td>
                        <td>Rp {{ number_format($penyewaan->total_biaya, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($penyewaan->denda, 0, ',', '.') }}</td>
                        <td>
                            @if($penyewaan->status == 'aktif')
                                <span class="badge badge-warning">Aktif</span>
                            @elseif($penyewaan->status == 'selesai')
                                <span class="badge badge-success">Selesai</span>
                            @else
                                <span class="badge badge-danger">Batal</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data penyewaan untuk tahun ini.</td>
                    </tr>
                    @endforelse
                </tbody>
                @if($penyewaans->count() > 0)
                <tfoot>
                    <tr>
                        <th colspan="7" class="text-right">Total:</th>
                        <th>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</th>
                        <th>Rp {{ number_format($totalDenda, 0, ',', '.') }}</th>
                        <th></th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Charts
    var pendapatanCtx = document.getElementById('pendapatanChart').getContext('2d');
    var pendapatanChart = new Chart(pendapatanCtx, {
        type: 'bar',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json($chartData['pendapatan']),
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    var penyewaanCtx = document.getElementById('penyewaanChart').getContext('2d');
    var penyewaanChart = new Chart(penyewaanCtx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Jumlah Penyewaan',
                data: @json($chartData['penyewaan']),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush