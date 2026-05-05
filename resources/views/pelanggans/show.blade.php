@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Pelanggan</h3>
        <div class="card-tools">
            <a href="{{ route('pelanggans.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Nama Lengkap</th>
                        <td>{{ $pelanggan->nama }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $pelanggan->email }}</td>
                    </tr>
                    <tr>
                        <th>Telepon</th>
                        <td>{{ $pelanggan->telepon }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">No. KTP</th>
                        <td>{{ $pelanggan->no_ktp }}</td>
                    </tr>
                    <tr>
                        <th>Total Sewa</th>
                        <td>
                            <span class="badge badge-info">
                                {{ $riwayatSewa->count() }}x
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Daftar</th>
                        <td>{{ $pelanggan->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-12">
                <label><strong>Alamat:</strong></label>
                <p class="border p-3 rounded">{{ $pelanggan->alamat }}</p>
            </div>
        </div>

        <!-- Riwayat Penyewaan -->
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="mb-3">
                    <i class="fas fa-history"></i> Riwayat Penyewaan
                    <span class="badge badge-primary">{{ $riwayatSewa->count() }}</span>
                </h5>
                
                @if($riwayatSewa->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Mobil</th>
                                <th>Tanggal Sewa</th>
                                <th>Tanggal Kembali</th>
                                <th>Total Biaya</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayatSewa as $sewa)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $sewa->mobil->merk }} {{ $sewa->mobil->model }}</td>
                                <td>{{ \Carbon\Carbon::parse($sewa->tanggal_sewa)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($sewa->tanggal_kembali_rencana)->format('d/m/Y') }}</td>
                                <td>Rp {{ number_format($sewa->total_biaya, 0, ',', '.') }}</td>
                                <td>
                                    @if($sewa->status == 'aktif')
                                        <span class="badge badge-warning">Aktif</span>
                                    @elseif($sewa->status == 'selesai')
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-danger">Batal</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Belum ada riwayat penyewaan.
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="btn-group">
            <a href="{{ route('pelanggans.edit', $pelanggan->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('pelanggans.riwayat', $pelanggan->id) }}" class="btn btn-info">
                <i class="fas fa-history"></i> Lihat Riwayat Lengkap
            </a>
        </div>
    </div>
</div>
@endsection