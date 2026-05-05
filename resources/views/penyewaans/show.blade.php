@extends('layouts.app')

@section('title', 'Detail Penyewaan #' . str_pad($penyewaan->id, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Detail Penyewaan #{{ str_pad($penyewaan->id, 6, '0', STR_PAD_LEFT) }}
        </h3>
        <div class="card-tools">
            <a href="{{ route('penyewaans.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Informasi Penyewaan -->
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Penyewaan</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Kode Sewa</th>
                                <td>#{{ str_pad($penyewaan->id, 6, '0', STR_PAD_LEFT) }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Sewa</th>
                                <td>{{ \Carbon\Carbon::parse($penyewaan->tanggal_sewa)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Kembali Rencana</th>
                                <td>{{ \Carbon\Carbon::parse($penyewaan->tanggal_kembali_rencana)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Durasi Sewa</th>
                                <td>{{ $penyewaan->durasi_sewa }} hari</td>
                            </tr>
                            <tr>
                                <th>Status</th>
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
                            <tr>
                                <th>Total Biaya</th>
                                <td class="font-weight-bold">Rp {{ number_format($penyewaan->total_biaya, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Informasi Pelanggan & Mobil -->
            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Pelanggan & Mobil</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Nama Pelanggan</th>
                                <td>{{ $penyewaan->pelanggan->nama }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $penyewaan->pelanggan->email }}</td>
                            </tr>
                            <tr>
                                <th>Telepon</th>
                                <td>{{ $penyewaan->pelanggan->telepon }}</td>
                            </tr>
                            <tr>
                                <th>Mobil</th>
                                <td>{{ $penyewaan->mobil->merk }} {{ $penyewaan->mobil->model }}</td>
                            </tr>
                            <tr>
                                <th>Nomor Plat</th>
                                <td>{{ $penyewaan->mobil->nomor_plat }}</td>
                            </tr>
                            <tr>
                                <th>Tarif per Hari</th>
                                <td>Rp {{ number_format($penyewaan->mobil->tarif_sewa_per_hari, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Pengembalian -->
        @if($penyewaan->pengembalian)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Pengembalian</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Tanggal Kembali Aktual</th>
                                <td>{{ \Carbon\Carbon::parse($penyewaan->pengembalian->tanggal_kembali_aktual)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Kondisi Mobil</th>
                                <td>{{ $penyewaan->pengembalian->kondisi_mobil }}</td>
                            </tr>
                            <tr>
                                <th>Denda</th>
                                <td class="font-weight-bold text-danger">
                                    Rp {{ number_format($penyewaan->pengembalian->denda, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <th>Total Pembayaran</th>
                                <td class="font-weight-bold text-success">
                                    Rp {{ number_format($penyewaan->total_biaya + $penyewaan->pengembalian->denda, 0, ',', '.') }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="card-footer">
        <div class="btn-group">
            @if($penyewaan->status == 'aktif')
                <a href="{{ route('penyewaans.edit', $penyewaan->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('pengembalians.create.with_penyewaan', $penyewaan->id) }}" 
                   class="btn btn-success">
                    <i class="fas fa-undo"></i> Proses Pengembalian
                </a>
                <form action="{{ route('penyewaans.batal', $penyewaan->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger" 
                        onclick="return confirm('Apakah Anda yakin ingin membatalkan penyewaan ini?')">
                        <i class="fas fa-times"></i> Batalkan
                    </button>
                </form>
            @endif
            <a href="{{ route('penyewaans.invoice', $penyewaan->id) }}" class="btn btn-info" target="_blank">
                <i class="fas fa-print"></i> Cetak Invoice
            </a>
        </div>
    </div>
</div>
@endsection