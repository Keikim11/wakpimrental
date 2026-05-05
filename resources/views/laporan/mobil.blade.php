@extends('layouts.app')

@section('title', 'Laporan Mobil')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Laporan Berdasarkan Mobil</h3>
        <div class="card-tools">
            <a href="{{ route('laporan.export.mobil', request()->all()) }}" class="btn btn-success">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('laporan.mobil') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="mobil">Pilih Mobil</label>
                        <select class="form-control select2" id="mobil" name="mobil">
                            <option value="">-- Semua Mobil --</option>
                            @foreach($mobils as $mobil)
                                <option value="{{ $mobil->id }}" {{ request('mobil') == $mobil->id ? 'selected' : '' }}>
                                    {{ $mobil->merk }} {{ $mobil->model }} - {{ $mobil->nomor_plat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="bulan">Pilih Bulan</label>
                        <input type="month" class="form-control" id="bulan" name="bulan" 
                               value="{{ request('bulan', date('Y-m')) }}" max="{{ date('Y-m') }}">
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
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <a href="{{ route('laporan.mobil') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <!-- Statistik -->
        @if(request('mobil') || request('bulan'))
        <div class="row mb-4">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $totalSewa }}</h3>
                        <p>Total Penyewaan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
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
            <div class="col-lg-4 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>
                            @if($totalSewa > 0)
                                {{ number_format($totalPendapatan / $totalSewa, 0, ',', '.') }}
                            @else
                                0
                            @endif
                        </h3>
                        <p>Rata-rata per Sewa</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Informasi Mobil Terpilih -->
        @if($mobil && $selectedMobil = $mobils->firstWhere('id', $mobil))
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Mobil</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Merk/Model:</strong><br>
                                {{ $selectedMobil->merk }} {{ $selectedMobil->model }}
                            </div>
                            <div class="col-md-3">
                                <strong>Nomor Plat:</strong><br>
                                {{ $selectedMobil->nomor_plat }}
                            </div>
                            <div class="col-md-3">
                                <strong>Tarif per Hari:</strong><br>
                                Rp {{ number_format($selectedMobil->tarif_sewa_per_hari, 0, ',', '.') }}
                            </div>
                            <div class="col-md-3">
                                <strong>Status:</strong><br>
                                <span class="badge badge-{{ $selectedMobil->status == 'tersedia' ? 'success' : ($selectedMobil->status == 'disewa' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($selectedMobil->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Tabel Data -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Sewa</th>
                        <th>Pelanggan</th>
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
                        <td colspan="9" class="text-center">Tidak ada data penyewaan untuk filter yang dipilih.</td>
                    </tr>
                    @endforelse
                </tbody>
                @if($penyewaans->count() > 0)
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-right">Total:</th>
                        <th>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</th>
                        <th>
                            Rp {{ number_format($penyewaans->sum('denda'), 0, ',', '.') }}
                        </th>
                        <th></th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        <!-- Summary -->
        @if($penyewaans->count() > 0)
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ringkasan Laporan</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <td width="60%"><strong>Periode Laporan</strong></td>
                                <td>
                                    @if(request('bulan'))
                                        {{ \Carbon\Carbon::parse(request('bulan'))->format('F Y') }}
                                    @else
                                        Semua Waktu
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Mobil</strong></td>
                                <td>
                                    @if($mobil && $selectedMobil = $mobils->firstWhere('id', $mobil))
                                        {{ $selectedMobil->merk }} {{ $selectedMobil->model }} - {{ $selectedMobil->nomor_plat }}
                                    @else
                                        Semua Mobil
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Total Penyewaan</strong></td>
                                <td>{{ $totalSewa }} transaksi</td>
                            </tr>
                            <tr>
                                <td><strong>Total Pendapatan</strong></td>
                                <td>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Rata-rata per Sewa</strong></td>
                                <td>
                                    Rp {{ number_format($totalSewa > 0 ? $totalPendapatan / $totalSewa : 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });
});
</script>
@endpush