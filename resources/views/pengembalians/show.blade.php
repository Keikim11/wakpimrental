@extends('layouts.app')

@section('title', 'Detail Pengembalian')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Pengembalian</h3>
        <div class="card-tools">
            <a href="{{ route('pengembalians.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Informasi Penyewaan -->
            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Penyewaan</h3>
                    </div>
                    <div class="card-body">
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
                                <th>Telepon</th>
                                <td>{{ $pengembalian->penyewaan->pelanggan->telepon }}</td>
                            </tr>
                            <tr>
                                <th>Mobil</th>
                                <td>{{ $pengembalian->penyewaan->mobil->merk }} {{ $pengembalian->penyewaan->mobil->model }}</td>
                            </tr>
                            <tr>
                                <th>Nomor Plat</th>
                                <td>{{ $pengembalian->penyewaan->mobil->nomor_plat }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Informasi Pengembalian -->
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Pengembalian</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Tanggal Kembali Aktual</th>
                                <td>{{ \Carbon\Carbon::parse($pengembalian->tanggal_kembali_aktual)->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Kembali Rencana</th>
                                <td>{{ \Carbon\Carbon::parse($pengembalian->penyewaan->tanggal_kembali_rencana)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Kondisi Mobil</th>
                                <td>
                                    @if($pengembalian->kondisi_mobil == 'baik')
                                        <span class="badge badge-success">Baik</span>
                                    @elseif($pengembalian->kondisi_mobil == 'rusak_ringan')
                                        <span class="badge badge-warning">Rusak Ringan</span>
                                    @else
                                        <span class="badge badge-danger">Rusak Berat</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Denda</th>
                                <td class="font-weight-bold text-danger">
                                    Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Pengembalian</th>
                                <td>{{ \Carbon\Carbon::parse($pengembalian->created_at)->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Catatan -->
        @if($pengembalian->catatan)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Catatan</h3>
                    </div>
                    <div class="card-body">
                        <p>{{ $pengembalian->catatan }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Ringkasan Pembayaran -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Ringkasan Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <td width="60%"><strong>Total Biaya Sewa</strong></td>
                                <td>Rp {{ number_format($pengembalian->penyewaan->total_biaya, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Denda</strong></td>
                                <td>Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="font-weight-bold" style="font-size: 1.1em;">
                                <td><strong>Total Pembayaran</strong></td>
                                <td class="text-success">
                                    Rp {{ number_format($pengembalian->penyewaan->total_biaya + $pengembalian->denda, 0, ',', '.') }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="btn-group">
            <a href="{{ route('pengembalians.edit', $pengembalian->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('pengembalians.destroy', $pengembalian->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" 
                    onclick="return confirm('Apakah Anda yakin ingin membatalkan pengembalian ini?')">
                    <i class="fas fa-undo"></i> Batalkan Pengembalian
                </button>
            </form>
        </div>
    </div>
</div>
@endsection