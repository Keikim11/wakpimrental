@extends('layouts.app')

@section('title', 'Riwayat Pengembalian')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Riwayat Pengembalian</h3>
        <div class="card-tools">
            <a href="{{ route('pengembalians.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">Tanggal Pengembalian</th>
                        <td>{{ \Carbon\Carbon::parse($pengembalian->tanggal_kembali_aktual)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Kondisi Mobil</th>
                        <td>{{ $pengembalian->kondisi_mobil }}</td>
                    </tr>
                    <tr>
                        <th>Denda</th>
                        <td class="font-weight-bold text-danger">
                            Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <th>Catatan</th>
                        <td>{{ $pengembalian->catatan ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">Kode Sewa</th>
                        <td>#{{ str_pad($pengembalian->penyewaan->id, 6, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <th>Pelanggan</th>
                        <td>{{ $pengembalian->penyewaan->pelanggan->nama }}</td>
                    </tr>
                    <tr>
                        <th>Mobil</th>
                        <td>{{ $pengembalian->penyewaan->mobil->merk }} {{ $pengembalian->penyewaan->mobil->model }}</td>
                    </tr>
                    <tr>
                        <th>Total Biaya Sewa</th>
                        <td class="font-weight-bold">
                            Rp {{ number_format($pengembalian->penyewaan->total_biaya, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Ringkasan Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Biaya Sewa</th>
                                <td>Rp {{ number_format($pengembalian->penyewaan->total_biaya, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Denda</th>
                                <td class="text-danger">Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th class="font-weight-bold">Total Pembayaran</th>
                                <td class="font-weight-bold text-success">
                                    Rp {{ number_format($pengembalian->penyewaan->total_biaya + $pengembalian->denda, 0, ',', '.') }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection