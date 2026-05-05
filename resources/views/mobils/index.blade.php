@extends('layouts.app')

@section('title', 'Manajemen Mobil')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Mobil</h3>
        <div class="card-tools">
            <a href="{{ route('mobils.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Mobil
            </a>
        </div>
    </div>
    <div class="card-body">
        <table id="mobilsTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Merk & Model</th>
                    <th>Nomor Plat</th>
                    <th>Tarif Sewa/Hari</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mobils as $mobil)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $mobil->merk }} {{ $mobil->model }}</td>
                    <td>{{ $mobil->nomor_plat }}</td>
                    <td>Rp {{ number_format($mobil->tarif_sewa_per_hari, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge badge-{{ $mobil->status == 'tersedia' ? 'success' : ($mobil->status == 'disewa' ? 'warning' : 'danger') }}">
                            {{ ucfirst($mobil->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('mobils.edit', $mobil->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('mobils.destroy', $mobil->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Destroy existing DataTable instance if exists
    if ($.fn.DataTable.isDataTable('#mobilsTable')) {
        $('#mobilsTable').DataTable().destroy();
    }
    
    // Initialize DataTable
    var table = $('#mobilsTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
        }
    });
});
</script>
@endpush