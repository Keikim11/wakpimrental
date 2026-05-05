@extends('layouts.app')

@section('title', 'Edit Pengembalian Mobil')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Data Pengembalian</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('pengembalians.update', $pengembalian->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="penyewaan_id">Kode Penyewaan</label>
                        <input type="text" class="form-control" value="{{ $pengembalian->penyewaan->kode_sewa }}" readonly>
                        <input type="hidden" name="penyewaan_id" value="{{ $pengembalian->penyewaan_id }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal_kembali_aktual">Tanggal Kembali Aktual</label>
                        <input type="date" class="form-control @error('tanggal_kembali_aktual') is-invalid @enderror" 
                               id="tanggal_kembali_aktual" name="tanggal_kembali_aktual" 
                               value="{{ old('tanggal_kembali_aktual', $pengembalian->tanggal_kembali_aktual ? $pengembalian->tanggal_kembali_aktual->format('Y-m-d') : '') }}" required>
                        @error('tanggal_kembali_aktual')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kondisi_mobil">Kondisi Mobil</label>
                        <select class="form-control @error('kondisi_mobil') is-invalid @enderror" 
                                id="kondisi_mobil" name="kondisi_mobil" required>
                            <option value="">Pilih Kondisi</option>
                            <option value="baik" {{ old('kondisi_mobil', $pengembalian->kondisi_mobil) == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak_ringan" {{ old('kondisi_mobil', $pengembalian->kondisi_mobil) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="rusak_berat" {{ old('kondisi_mobil', $pengembalian->kondisi_mobil) == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                        @error('kondisi_mobil')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="denda">Denda (Rp)</label>
                        <input type="number" class="form-control @error('denda') is-invalid @enderror" 
                               id="denda" name="denda" 
                               value="{{ old('denda', $pengembalian->denda) }}" min="0" step="1000">
                        @error('denda')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                          id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $pengembalian->keterangan) }}</textarea>
                @error('keterangan')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Pengembalian
                </button>
                <a href="{{ route('pengembalians.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto calculate denda based on kondisi mobil
    $('#kondisi_mobil').change(function() {
        const kondisi = $(this).val();
        let denda = 0;

        if (kondisi === 'rusak_ringan') {
            denda = 500000; // Contoh denda untuk rusak ringan
        } else if (kondisi === 'rusak_berat') {
            denda = 2000000; // Contoh denda untuk rusak berat
        }

        $('#denda').val(denda);
    });
});
</script>
@endpush