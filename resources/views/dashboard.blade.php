@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <!-- Header Stats -->
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        Dashboard Overview
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-light">Update: {{ \Carbon\Carbon::now()->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info boxes dengan design modern -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-gradient-info">
                    <i class="fas fa-car"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Mobil</span>
                    <span class="info-box-number">{{ $totalMobil }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-info" style="width: {{ ($mobilTersedia / $totalMobil) * 100 }}%"></div>
                    </div>
                    <small class="text-muted">{{ $mobilTersedia }} tersedia</small>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-gradient-success">
                    <i class="fas fa-users"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Pelanggan</span>
                    <span class="info-box-number">{{ $totalPelanggan }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: 70%"></div>
                    </div>
                    <small class="text-muted">Aktif bulan ini</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-gradient-warning">
                    <i class="fas fa-clipboard-list"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Penyewaan Aktif</span>
                    <span class="info-box-number">{{ $penyewaanAktif }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width: {{ ($penyewaanAktif / max($totalPelanggan, 1)) * 100 }}%"></div>
                    </div>
                    <small class="text-muted">{{ $penyewaanBulanIni }} bulan ini</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-gradient-danger">
                    <i class="fas fa-chart-line"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Pendapatan Bulan Ini</span>
                    <span class="info-box-number">Rp {{ number_format($totalPendapatanBulanIni ?? 0, 0, ',', '.') }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-danger" style="width: 85%"></div>
                    </div>
                    <small class="text-muted">+15% dari bulan lalu</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart Section -->
        <div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Statistik Penyewaan 6 Bulan Terakhir
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="dashboardChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bolt mr-2"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('penyewaans.create') }}" class="btn btn-outline-primary btn-lg btn-block p-3">
                                <i class="fas fa-plus fa-2x mb-2"></i><br>
                                <span class="font-weight-bold">Tambah Penyewaan</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('mobils.index') }}" class="btn btn-outline-success btn-lg btn-block p-3">
                                <i class="fas fa-car fa-2x mb-2"></i><br>
                                <span class="font-weight-bold">Kelola Mobil</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('laporan.bulanan') }}" class="btn btn-outline-info btn-lg btn-block p-3">
                                <i class="fas fa-chart-pie fa-2x mb-2"></i><br>
                                <span class="font-weight-bold">Laporan</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('pengembalians.index') }}" class="btn btn-outline-warning btn-lg btn-block p-3">
                                <i class="fas fa-undo fa-2x mb-2"></i><br>
                                <span class="font-weight-bold">Pengembalian</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Content -->
        <div class="col-lg-4">
            <!-- Mobil Tersedia -->
            <div class="card card-dark card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-car-side mr-2"></i>
                        Mobil Tersedia
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-light">{{ $mobilTersedia }} tersedia</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th>Mobil</th>
                                    <th>Tarif</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mobilsTersedia as $mobil)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-car text-success mr-2"></i>
                                            <div>
                                                <strong class="d-block">{{ $mobil->merk }} {{ $mobil->model }}</strong>
                                                <small class="text-muted">{{ $mobil->nomor_plat }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-nowrap">Rp {{ number_format($mobil->tarif_sewa_per_hari, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge badge-success">Tersedia</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('mobils.index') }}" class="btn btn-sm btn-outline-dark">
                        <i class="fas fa-eye mr-1"></i> Lihat Semua Mobil
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-2"></i>
                        Aktivitas Terbaru
                    </h3>
                </div>
                <div class="card-body">
                    <div class="activity-feed">
                        @foreach($recentActivities as $activity)
                        <div class="feed-item mb-3">
                            <div class="feed-icon {{ $activity['color'] }}">
                                <i class="{{ $activity['icon'] }}"></i>
                            </div>
                            <div class="feed-content">
                                <span class="feed-text">{{ $activity['text'] }}</span>
                                <small class="feed-time text-muted">{{ $activity['time'] }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cogs mr-2"></i>
                        Status Sistem
                    </h3>
                </div>
                <div class="card-body">
                    <div class="system-status">
                        <div class="status-item d-flex justify-content-between align-items-center mb-2">
                            <span>Database</span>
                            <span class="badge badge-success">Online</span>
                        </div>
                        <div class="status-item d-flex justify-content-between align-items-center mb-2">
                            <span>Server</span>
                            <span class="badge badge-success">Normal</span>
                        </div>
                        <div class="status-item d-flex justify-content-between align-items-center mb-2">
                            <span>Backup</span>
                            <span class="badge badge-info">Harian</span>
                        </div>
                        <div class="status-item d-flex justify-content-between align-items-center">
                            <span>Update</span>
                            <span class="badge badge-light">v1.0.0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.info-box {
    border-radius: 10px;
    transition: transform 0.2s;
}
.info-box:hover {
    transform: translateY(-5px);
}
.info-box-icon {
    border-radius: 10px 0 0 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 80px;
}
.bg-gradient-info { background: linear-gradient(45deg, #17a2b8, #6f42c1); }
.bg-gradient-success { background: linear-gradient(45deg, #28a745, #20c997); }
.bg-gradient-warning { background: linear-gradient(45deg, #ffc107, #fd7e14); }
.bg-gradient-danger { background: linear-gradient(45deg, #dc3545, #e83e8c); }

.activity-feed .feed-item {
    display: flex;
    align-items: flex-start;
    padding: 8px 0;
    border-bottom: 1px solid #f8f9fa;
}
.activity-feed .feed-item:last-child {
    border-bottom: none;
}
.feed-icon {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    flex-shrink: 0;
}
.feed-content {
    flex: 1;
}
.feed-text {
    display: block;
    font-size: 0.9em;
}
.feed-time {
    font-size: 0.8em;
}

.system-status .status-item {
    padding: 5px 0;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}
.card-outline {
    border-top: 3px solid;
}
.card-outline.primary { border-top-color: #007bff; }
.card-outline.success { border-top-color: #28a745; }
.card-outline.info { border-top-color: #17a2b8; }
.card-outline.warning { border-top-color: #ffc107; }
.card-outline.danger { border-top-color: #dc3545; }
.card-outline.dark { border-top-color: #343a40; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(function () {
    'use strict'

    // Dashboard Chart dengan gradient
    var ctx = document.getElementById('dashboardChart').getContext('2d');
    var gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(54, 162, 235, 0.8)');
    gradient.addColorStop(1, 'rgba(54, 162, 235, 0.2)');

    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartData['labels']),
            datasets: [
                {
                    label: 'Pendapatan (Rp)',
                    data: @json($chartData['pendapatan']),
                    backgroundColor: gradient,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    borderRadius: 5,
                    yAxisID: 'y'
                },
                {
                    label: 'Jumlah Penyewaan',
                    data: @json($chartData['penyewaan']),
                    type: 'line',
                    fill: false,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 3,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 12,
                            family: "'Segoe UI', Roboto, 'Helvetica Neue', Arial"
                        }
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: {
                        size: 13
                    },
                    bodyFont: {
                        size: 12
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Pendapatan (Rp)',
                        font: {
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Jumlah Penyewaan',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        drawOnChartArea: false,
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    });

    // Auto refresh chart every 30 seconds
    setInterval(function() {
        chart.update();
    }, 30000);
});
</script>
@endpush