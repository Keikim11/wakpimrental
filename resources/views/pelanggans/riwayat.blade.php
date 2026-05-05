@extends('layouts.app')

@section('title', 'Riwayat Penyewaan - ' . $pelanggan->nama)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-history"></i> Riwayat Penyewaan - {{ $pelanggan->nama }}
        </h3>
        <div class="card-tools">
            <a href="{{ route('pelanggans.show', $pelanggan->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Detail
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Info Pelanggan -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="info-box bg-light">
                    <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Nama Pelanggan</span>
                        <span class="info-box-number">{{ $pelanggan->nama }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-box bg-light">
                    <span class="info-box-icon bg-success"><i class="fas fa-clipboard-list"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Penyewaan</span>
                        <span class="info-box-number">{{ $riwayatSewa->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Penyewaan -->
        <div class="table-responsive">
            <table id="riwayatTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Sewa</th>
                        <th>Mobil</th>
                        <th>Tanggal Sewa</th>
                        <th>Tanggal Kembali</th>
                        <th>Durasi</th>
                        <th>Total Biaya</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayatSewa as $sewa)
                    @php
                        $durasi = \Carbon\Carbon::parse($sewa->tanggal_sewa)
                            ->diffInDays($sewa->tanggal_kembali_rencana);
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>#{{ str_pad($sewa->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <strong>{{ $sewa->mobil->merk }} {{ $sewa->mobil->model }}</strong><br>
                            <small class="text-muted">{{ $sewa->mobil->nomor_plat }}</small>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($sewa->tanggal_sewa)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($sewa->tanggal_kembali_rencana)->format('d/m/Y') }}</td>
                        <td>{{ $durasi }} hari</td>
                        <td>Rp {{ number_format($sewa->total_biaya, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($sewa->denda, 0, ',', '.') }}</td>
                        <td>
                            @if($sewa->status == 'aktif')
                                <span class="badge badge-warning">Aktif</span>
                            @elseif($sewa->status == 'selesai')
                                <span class="badge badge-success">Selesai</span>
                            @else
                                <span class="badge badge-danger">Batal</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('penyewaans.show', $sewa->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($sewa->status == 'aktif')
                                <a href="{{ route('pengembalians.create.with_penyewaan', $sewa->id) }}" 
                                   class="btn btn-success btn-sm" title="Proses Pengembalian">
                                    <i class="fas fa-undo"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    $('#riwayatTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "order": [[0, 'desc']],
        "language": {
            "emptyTable": "Tidak ada riwayat penyewaan",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
            "infoFiltered": "(disaring dari _MAX_ total data)",
            "lengthMenu": "Tampilkan _MENU_ data",
            "loadingRecords": "Memuat...",
            "processing": "Memproses...",
            "search": "Cari:",
            "zeroRecords": "Tidak ditemukan data yang sesuai",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });
});
</script>
@endpush