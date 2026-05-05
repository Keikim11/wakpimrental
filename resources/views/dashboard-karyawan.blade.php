@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-tie mr-2"></i>
                        Dashboard Karyawan - Selamat Datang, {{ Auth::user()->name }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Simplified Stats -->
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="fas fa-clipboard-list"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Penyewaan Aktif</span>
                    <span class="info-box-number">{{ $penyewaanAktif }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-car"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Mobil Tersedia</span>
                    <span class="info-box-number">{{ $mobilTersedia }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="info-box bg-warning">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Pelanggan</span>
                    <span class="info-box-number">{{ $totalPelanggan }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Bulan Ini</span>
                    <span class="info-box-number">{{ $penyewaanBulanIni }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions untuk Karyawan -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Menu Cepat</h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('penyewaans.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-plus fa-2x mb-2"></i><br>
                                Sewa Baru
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('pelanggans.create') }}" class="btn btn-success btn-block">
                                <i class="fas fa-user-plus fa-2x mb-2"></i><br>
                                Pelanggan Baru
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('penyewaans.index') }}" class="btn btn-info btn-block">
                                <i class="fas fa-list fa-2x mb-2"></i><br>
                                Data Sewa
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('pengembalians.index') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-undo fa-2x mb-2"></i><br>
                                Pengembalian
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('laporan.harian') }}" class="btn btn-danger btn-block">
                                <i class="fas fa-print fa-2x mb-2"></i><br>
                                Laporan
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('mobils.index') }}" class="btn btn-dark btn-block">
                                <i class="fas fa-car fa-2x mb-2"></i><br>
                                Stok Mobil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection