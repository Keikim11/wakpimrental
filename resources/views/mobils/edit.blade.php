@extends('layouts.app')

@section('title', 'Edit Mobil')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Data Mobil</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('mobils.update', $mobil->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="merk">Merk *</label>
                        <input type="text" class="form-control @error('merk') is-invalid @enderror" 
                               id="merk" name="merk" value="{{ old('merk', $mobil->merk) }}" required>
                        @error('merk')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="model">Model *</label>
                        <input type="text" class="form-control @error('model') is-invalid @enderror" 
                               id="model" name="model" value="{{ old('model', $mobil->model) }}" required>
                        @error('model')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nomor_plat">Nomor Plat *</label>
                        <input type="text" class="form-control @error('nomor_plat') is-invalid @enderror" 
                               id="nomor_plat" name="nomor_plat" value="{{ old('nomor_plat', $mobil->nomor_plat) }}" required>
                        @error('nomor_plat')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tarif_sewa_per_hari">Tarif Sewa per Hari (Rp) *</label>
                        <input type="number" class="form-control @error('tarif_sewa_per_hari') is-invalid @enderror" 
                               id="tarif_sewa_per_hari" name="tarif_sewa_per_hari" 
                               value="{{ old('tarif_sewa_per_hari', $mobil->tarif_sewa_per_hari) }}" required min="0">
                        @error('tarif_sewa_per_hari')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="status">Status *</label>
                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="tersedia" {{ old('status', $mobil->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="disewa" {{ old('status', $mobil->status) == 'disewa' ? 'selected' : '' }}>Disewa</option>
                    <option value="maintenance" {{ old('status', $mobil->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
                @error('status')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                          id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $mobil->deskripsi) }}</textarea>
                @error('deskripsi')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="foto">Foto Mobil</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input @error('foto') is-invalid @enderror" 
                           id="foto" name="foto" accept="image/*">
                    <label class="custom-file-label" for="foto">Pilih file...</label>
                    @error('foto')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <small class="form-text text-muted">
                    Format: JPG, PNG, GIF. Maksimal 2MB.
                    @if($mobil->foto)
                        <br>Foto saat ini: 
                        <a href="{{ asset('images/mobil/' . $mobil->foto) }}" target="_blank">Lihat</a>
                    @endif
                </small>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Mobil
                </button>
                <a href="{{ route('mobils.index') }}" class="btn btn-secondary">
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
    // Preview nama file
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Format input tarif
    $('#tarif_sewa_per_hari').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
</script>
@endpush