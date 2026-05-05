@extends('layouts.app')

@section('title', 'Data Pengembalian')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Pengembalian Mobil</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="pengembaliansTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Sewa</th>
                        <th>Pelanggan</th>
                        <th>Mobil</th>
                        <th>Tanggal Sewa</th>
                        <th>Tanggal Kembali</th>
                        <th>Kondisi Mobil</th>
                        <th>Denda</th>
                        <th>Waktu Pengembalian</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Hitung kode sewa yang unik dan berurutan per penyewaan
                        $counter = 1;
                    @endphp
                    @foreach($pengembalians->sortBy('id') as $pengembalian)
                    @php
                        // Format kode sewa: WP-000001
                        $kodeSewa = 'WP-' . str_pad($loop->iteration, 6, '0', STR_PAD_LEFT);
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $kodeSewa }}</strong>
                        </td>
                        <td>
                            <strong>{{ $pengembalian->penyewaan->pelanggan->nama ?? 'N/A' }}</strong><br>
                            <small class="text-muted">{{ $pengembalian->penyewaan->pelanggan->telepon ?? '-' }}</small>
                        </td>
                        <td>
                            <strong>{{ $pengembalian->penyewaan->mobil->merk ?? 'N/A' }} {{ $pengembalian->penyewaan->mobil->model ?? '' }}</strong><br>
                            <small class="text-muted">{{ $pengembalian->penyewaan->mobil->nomor_plat ?? '-' }}</small>
                        </td>
                        <td>
                            @if(isset($pengembalian->penyewaan->tanggal_sewa))
                                {{ \Carbon\Carbon::parse($pengembalian->penyewaan->tanggal_sewa)->format('d/m/Y') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if(isset($pengembalian->penyewaan->tanggal_kembali_rencana))
                                {{ \Carbon\Carbon::parse($pengembalian->penyewaan->tanggal_kembali_rencana)->format('d/m/Y') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if($pengembalian->kondisi_mobil == 'baik')
                                <span class="badge badge-success">Baik</span>
                            @elseif($pengembalian->kondisi_mobil == 'rusak_ringan')
                                <span class="badge badge-warning">Rusak Ringan</span>
                            @elseif($pengembalian->kondisi_mobil == 'rusak_berat')
                                <span class="badge badge-danger">Rusak Berat</span>
                            @else
                                <span class="badge badge-secondary">N/A</span>
                            @endif
                            @if($pengembalian->catatan)
                                <br>
                                <small class="text-muted" title="{{ $pengembalian->catatan }}">
                                    <i class="fas fa-sticky-note"></i> Ada catatan
                                </small>
                            @endif
                        </td>
                        <td>
                            @if($pengembalian->denda > 0)
                                <span class="text-danger font-weight-bold">
                                    Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="text-success">Rp 0</span>
                            @endif
                        </td>
                       
                        <td>
                            {{ \Carbon\Carbon::parse($pengembalian->tanggal_kembali_aktual)->format('d/m/Y') }}<br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($pengembalian->tanggal_kembali_aktual)->format('H:i') }}</small>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('pengembalians.show', $pengembalian->id) }}" 
                                   class="btn btn-info btn-sm" 
                                   title="Detail Pengembalian">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('pengembalians.edit', $pengembalian->id) }}" 
                                   class="btn btn-warning btn-sm" 
                                   title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(auth()->user()->role == 'admin')
                                <form action="{{ route('pengembalians.destroy', $pengembalian->id) }}" 
                                      method="POST" 
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            title="Hapus Data"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data pengembalian ini?')">
                                        <i class="fas fa-trash"></i>
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

@push('styles')
<style>
    .btn-group {
        display: flex;
        gap: 2px;
    }
    .btn-group .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endpush

@push('scripts')
<script>
$(function () {
    $('#pengembaliansTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "order": [[1, 'desc']], // Urut berdasarkan Kode Sewa secara descending
        "language": {
            "emptyTable": "Tidak ada data pengembalian",
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
                "targets": [1], // Kolom Kode Sewa (index 1)
                "type": "num", // Tipe data numeric untuk sorting
                "render": function(data, type, row, meta) {
                    if (type === 'sort' || type === 'type') {
                        // Ekstrak angka dari kode sewa (WP-000001 → 1)
                        return parseInt(data.replace('WP-', '')) || 0;
                    }
                    return data; // Tampilkan data asli untuk display
                }
            },
            {
                "targets": [6], // Kolom Denda (index 6)
                "type": "num", // Tipe data numeric
                "render": function(data, type, row, meta) {
                    if (type === 'sort' || type === 'type') {
                        // Ekstrak angka dari format mata uang
                        return parseFloat(data.replace(/[^0-9.-]+/g, "")) || 0;
                    }
                    return data;
                }
            },
            {
                "targets": [9], // Kolom Waktu Pengembalian (index 9)
                "type": "date-eu", // Format tanggal EU untuk sorting
                "render": function(data, type, row, meta) {
                    if (type === 'sort' || type === 'type') {
                        // Konversi format tanggal untuk sorting
                        var dateParts = data.split('<br>')[0].split('/');
                        return dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
                    }
                    return data;
                }
            }
        ]
    });
});
</script>
@endpush