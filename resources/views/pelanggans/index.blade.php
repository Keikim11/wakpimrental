@extends('layouts.app')

@section('title', 'Manajemen Pelanggan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Pelanggan</h3>
        <div class="card-tools">
            <a href="{{ route('pelanggans.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pelanggan
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="pelanggansTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>No. KTP</th>
                        <th>Total Sewa</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pelanggans as $pelanggan)
                    @php
                        $totalSewa = \App\Models\Penyewaan::where('pelanggan_id', $pelanggan->id)->count();
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pelanggan->nama }}</td>
                        <td>{{ $pelanggan->email }}</td>
                        <td>{{ $pelanggan->telepon }}</td>
                        <td>{{ $pelanggan->no_ktp }}</td>
                        <td>
                            <span class="badge badge-info">{{ $totalSewa }}x</span>
                        </td>
                        <td>
                            @if($totalSewa > 0)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Baru</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('pelanggans.show', $pelanggan->id) }}" class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('pelanggans.edit', $pelanggan->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('pelanggans.riwayat', $pelanggan->id) }}" class="btn btn-secondary btn-sm" title="Riwayat">
                                    <i class="fas fa-history"></i>
                                </a>
                                <form action="{{ route('pelanggans.destroy', $pelanggan->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
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
    $('#pelanggansTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "emptyTable": "Tidak ada data pelanggan",
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