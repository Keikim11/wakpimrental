@extends('layouts.app')

@section('title', 'Manajemen Penyewaan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Penyewaan</h3>
        <div class="card-tools">
            <a href="{{ route('penyewaans.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Penyewaan
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="penyewaansTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Sewa</th>
                        <th>Pelanggan</th>
                        <th>Mobil</th>
                        <th>Tanggal Sewa</th>
                        <th>Tanggal Kembali</th>
                        <th>Durasi</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Hitung kode sewa yang unik dan berurutan per penyewaan
                        $counter = 1;
                    @endphp
                    @foreach($penyewaans->sortBy('id') as $penyewaan)
                    @php
                        $durasi = \Carbon\Carbon::parse($penyewaan->tanggal_sewa)
                            ->diffInDays($penyewaan->tanggal_kembali_rencana);
                        // Format kode sewa: WP-000001 (WP = WakPim)
                        $kodeSewa = 'WP-' . str_pad($counter, 6, '0', STR_PAD_LEFT);
                        $counter++;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $kodeSewa }}</strong><br>
                            <small class="text-muted">ID: #{{ $penyewaan->id }}</small>
                        </td>
                        <td>
                            <strong>{{ $penyewaan->pelanggan->nama }}</strong><br>
                            <small class="text-muted">{{ $penyewaan->pelanggan->telepon }}</small>
                        </td>
                        <td>
                            <strong>{{ $penyewaan->mobil->merk }} {{ $penyewaan->mobil->model }}</strong><br>
                            <small class="text-muted">{{ $penyewaan->mobil->nomor_plat }}</small>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($penyewaan->tanggal_sewa)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($penyewaan->tanggal_kembali_rencana)->format('d/m/Y') }}</td>
                        <td>{{ $durasi }} hari</td>
                        <td>Rp {{ number_format($penyewaan->total_biaya, 0, ',', '.') }}</td>
                        <td>
                            @if($penyewaan->status == 'aktif')
                                <span class="badge badge-warning">Aktif</span>
                            @elseif($penyewaan->status == 'selesai')
                                <span class="badge badge-success">Selesai</span>
                            @else
                                <span class="badge badge-danger">Batal</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('penyewaans.show', $penyewaan->id) }}" class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($penyewaan->status == 'aktif')
                                    <a href="{{ route('penyewaans.edit', $penyewaan->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('pengembalians.create.with_penyewaan', $penyewaan->id) }}" 
                                       class="btn btn-success btn-sm" title="Pengembalian">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                    <form action="{{ route('penyewaans.batal', $penyewaan->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" title="Batalkan"
                                            onclick="return confirm('Apakah Anda yakin ingin membatalkan penyewaan ini?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
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
    $('#penyewaansTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "order": [[0, 'asc']], // Urut berdasarkan No (urutan)
        "language": {
            "emptyTable": "Tidak ada data penyewaan",
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
        },
        "columnDefs": [
            {
                "targets": [1], // Kolom Kode Sewa
                "render": function(data, type, row, meta) {
                    if (type === 'sort') {
                        // Untuk sorting, gunakan angka dari kode sewa
                        return parseInt(data.split('-')[1]) || 0;
                    }
                    return data;
                }
            }
        ]
    });
});
</script>
@endpush