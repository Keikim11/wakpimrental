@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <!-- Info boxes -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1">
                    <i class="fas fa-car"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Mobil</span>
                    <span class="info-box-number">
                        {{ $totalMobil }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1">
                    <i class="fas fa-users"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Pelanggan</span>
                    <span class="info-box-number">{{ $totalPelanggan }}</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1">
                    <i class="fas fa-clipboard-list"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Penyewaan Aktif</span>
                    <span class="info-box-number">{{ $penyewaanAktif }}</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1">
                    <i class="fas fa-chart-line"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Penyewaan Bulan Ini</span>
                    <span class="info-box-number">{{ $penyewaanBulanIni }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Charts -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statistik 6 Bulan Terakhir</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="dashboardChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobil Tersedia -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Mobil Tersedia</h3>
                    <div class="card-tools">
                        <span class="badge badge-success">{{ $mobilTersedia }} Mobil</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @foreach($mobilsTersedia as $mobil)
                        <li class="item">
                            <div class="product-img">
                                <i class="fas fa-car fa-2x text-success"></i>
                            </div>
                            <div class="product-info">
                                <a href="{{ route('mobils.show', $mobil->id) }}" class="product-title">
                                    {{ $mobil->merk }} {{ $mobil->model }}
                                    <span class="badge badge-success float-right">Rp {{ number_format($mobil->tarif_sewa_per_hari, 0, ',', '.') }}</span>
                                </a>
                                <span class="product-description">
                                    {{ $mobil->nomor_plat }}
                                </span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('mobils.index') }}" class="uppercase">Lihat Semua Mobil</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions untuk Admin -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('laporan.harian') }}" class="btn btn-app bg-info">
                                <i class="fas fa-chart-bar"></i>
                                Laporan Harian
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('laporan.bulanan') }}" class="btn btn-app bg-success">
                                <i class="fas fa-chart-pie"></i>
                                Laporan Bulanan
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('penyewaans.create') }}" class="btn btn-app bg-warning">
                                <i class="fas fa-plus"></i>
                                Tambah Penyewaan
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('pengembalians.index') }}" class="btn btn-app bg-danger">
                                <i class="fas fa-undo"></i>
                                Data Pengembalian
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(function () {
    'use strict'

    // Dashboard Chart
    var ctx = document.getElementById('dashboardChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartData['labels']),
            datasets: [
                {
                    label: 'Pendapatan (Rp)',
                    data: @json($chartData['pendapatan']),
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    yAxisID: 'y'
                },
                {
                    label: 'Jumlah Penyewaan',
                    data: @json($chartData['penyewaan']),
                    type: 'line',
                    fill: false,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 2,
                    tension: 0.4,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Pendapatan (Rp)'
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Jumlah Penyewaan'
                    },
                    grid: {
                        drawOnChartArea: false
                    },
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