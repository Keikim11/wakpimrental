@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

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
                    <span class="info-box-text">Mobil Tersedia</span>
                    <span class="info-box-number">
                        {{ $totalMobilTersedia }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1">
                    <i class="fas fa-clipboard-check"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Penyewaan Hari Ini</span>
                    <span class="info-box-number">{{ $penyewaanHariIni }}</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1">
                    <i class="fas fa-clock"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Penyewaan Aktif</span>
                    <span class="info-box-number">{{ $penyewaanAktif }}</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-primary elevation-1">
                    <i class="fas fa-tasks"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Akan Berakhir</span>
                    <span class="info-box-number">{{ $penyewaanAkanBerakhir->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Mobil Tersedia -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Mobil Tersedia</h3>
                    <div class="card-tools">
                        <a href="{{ route('mobils.index') }}" class="btn btn-sm btn-primary">
                            Lihat Semua
                        </a>
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
                                    {{ $mobil->nomor_plat }} | {{ $mobil->status }}
                                </span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Penyewaan Akan Berakhir -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Penyewaan Akan Berakhir</h3>
                    <div class="card-tools">
                        <span class="badge badge-warning">{{ $penyewaanAkanBerakhir->count() }} Penyewaan</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($penyewaanAkanBerakhir->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Pelanggan</th>
                                    <th>Mobil</th>
                                    <th>Kembali</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penyewaanAkanBerakhir as $penyewaan)
                                <tr>
                                    <td>#{{ str_pad($penyewaan->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $penyewaan->pelanggan->nama }}</td>
                                    <td>{{ $penyewaan->mobil->merk }} {{ $penyewaan->mobil->model }}</td>
                                    <td>
                                        <small class="text-danger">
                                            {{ \Carbon\Carbon::parse($penyewaan->tanggal_kembali_rencana)->format('d/m') }}
                                        </small>
                                    </td>
                                    <td>
                                        <a href="{{ route('pengembalians.create.with_penyewaan', $penyewaan->id) }}" 
                                           class="btn btn-sm btn-success">
                                            <i class="fas fa-undo"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center p-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p class="text-muted">Tidak ada penyewaan yang akan berakhir dalam 1 hari ke depan.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions untuk Karyawan -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('penyewaans.create') }}" class="btn btn-app bg-primary">
                                <i class="fas fa-plus"></i>
                                Tambah Penyewaan
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('mobils.index') }}" class="btn btn-app bg-success">
                                <i class="fas fa-car"></i>
                                Kelola Mobil
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('pelanggans.index') }}" class="btn btn-app bg-info">
                                <i class="fas fa-users"></i>
                                Kelola Pelanggan
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('penyewaans.index') }}" class="btn btn-app bg-warning">
                                <i class="fas fa-clipboard-list"></i>
                                Lihat Penyewaan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection