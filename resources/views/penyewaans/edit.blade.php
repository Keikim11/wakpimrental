@extends('layouts.app')

@section('title', 'Edit Penyewaan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Penyewaan #{{ str_pad($penyewaan->id, 6, '0', STR_PAD_LEFT) }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('penyewaans.update', $penyewaan->id) }}" method="POST" id="penyewaanForm">
            @csrf
            @method('PUT')
            
            <!-- Informasi yang tidak bisa diubah -->
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Beberapa informasi tidak dapat diubah karena penyewaan sedang aktif.
            </div>

            <!-- Informasi Pelanggan -->
            <div class="row">
                <div class="col-12">
                    <h5 class="mb-3"><i class="fas fa-user"></i> Informasi Pelanggan</h5>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="pelanggan_id">Pelanggan</label>
                        <select class="form-control select2 @error('pelanggan_id') is-invalid @enderror" 
                                id="pelanggan_id" name="pelanggan_id" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($pelanggans as $pelanggan)
                                <option value="{{ $pelanggan->id }}" 
                                    {{ old('pelanggan_id', $penyewaan->pelanggan_id) == $pelanggan->id ? 'selected' : '' }}>
                                    {{ $pelanggan->nama }} - {{ $pelanggan->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('pelanggan_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informasi Mobil -->
            <div class="row mt-4">
                <div class="col-12">
                    <h5 class="mb-3"><i class="fas fa-car"></i> Informasi Mobil</h5>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="mobil_id">Pilih Mobil *</label>
                        <select class="form-control select2 @error('mobil_id') is-invalid @enderror" 
                                id="mobil_id" name="mobil_id" required>
                            <option value="">-- Pilih Mobil --</option>
                            @foreach($mobils as $mobil)
                                <option value="{{ $mobil->id }}" 
                                    data-tarif="{{ $mobil->tarif_sewa_per_hari }}"
                                    {{ old('mobil_id', $penyewaan->mobil_id) == $mobil->id ? 'selected' : '' }}>
                                    {{ $mobil->merk }} {{ $mobil->model }} - {{ $mobil->nomor_plat }} 
                                    (Rp {{ number_format($mobil->tarif_sewa_per_hari, 0, ',', '.') }}/hari)
                                </option>
                            @endforeach
                        </select>
                        @error('mobil_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tarif Sewa per Hari</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" class="form-control" id="tarif_per_hari" 
                                   value="{{ number_format($penyewaan->mobil->tarif_sewa_per_hari, 0, ',', '.') }}" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Waktu Sewa -->
            <div class="row mt-4">
                <div class="col-12">
                    <h5 class="mb-3"><i class="fas fa-calendar"></i> Informasi Waktu Sewa</h5>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tanggal_sewa">Tanggal Sewa</label>
                        <input type="date" class="form-control bg-light" 
                               value="{{ $penyewaan->tanggal_sewa->format('Y-m-d') }}" readonly>
                        <small class="form-text text-muted">Tanggal sewa tidak dapat diubah</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tanggal_kembali_rencana">Tanggal Kembali Rencana *</label>
                        <input type="date" class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror" 
                               id="tanggal_kembali_rencana" name="tanggal_kembali_rencana" 
                               value="{{ old('tanggal_kembali_rencana', $penyewaan->tanggal_kembali_rencana->format('Y-m-d')) }}" 
                               min="{{ $penyewaan->tanggal_sewa->format('Y-m-d') }}" required>
                        @error('tanggal_kembali_rencana')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="durasi_sewa">Durasi Sewa (hari) *</label>
                        <input type="number" class="form-control @error('durasi_sewa') is-invalid @enderror" 
                               id="durasi_sewa" name="durasi_sewa" 
                               value="{{ old('durasi_sewa', $penyewaan->durasi_sewa) }}" min="1" required>
                        @error('durasi_sewa')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informasi Biaya -->
            <div class="row mt-4">
                <div class="col-12">
                    <h5 class="mb-3"><i class="fas fa-calculator"></i> Informasi Biaya</h5>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Total Biaya</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" class="form-control font-weight-bold" id="total_biaya" 
                                   value="{{ number_format($penyewaan->total_biaya, 0, ',', '.') }}" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Penyewaan
                </button>
                <a href="{{ route('penyewaans.show', $penyewaan->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
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

    // Calculate total cost when mobil or duration changes
    function calculateCost() {
        const tarifPerHari = parseFloat($('#mobil_id option:selected').data('tarif')) || 0;
        const durasi = parseInt($('#durasi_sewa').val()) || 0;
        const totalBiaya = tarifPerHari * durasi;
        
        $('#total_biaya').val(totalBiaya.toLocaleString('id-ID'));
    }

    $('#mobil_id').on('change', function() {
        const tarif = $(this).find('option:selected').data('tarif') || 0;
        $('#tarif_per_hari').val(tarif.toLocaleString('id-ID'));
        calculateCost();
    });

    $('#durasi_sewa').on('input', function() {
        calculateCost();
    });

    // Calculate duration when return date changes
    $('#tanggal_kembali_rencana').on('change', function() {
        const startDate = new Date('{{ $penyewaan->tanggal_sewa->format('Y-m-d') }}');
        const endDate = new Date($(this).val());
        
        if (endDate > startDate) {
            const diffTime = Math.abs(endDate - startDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            $('#durasi_sewa').val(diffDays);
            calculateCost();
        }
    });
});
</script>
@endpush