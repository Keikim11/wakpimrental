@extends('layouts.app')

@section('title', 'Tambah Mobil Baru')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Mobil Baru</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('mobils.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="merk">Merk</label>
                        <input type="text" class="form-control" id="merk" name="merk" value="{{ old('merk') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="model">Model</label>
                        <input type="text" class="form-control" id="model" name="model" value="{{ old('model') }}" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nomor_plat">Nomor Plat</label>
                        <input type="text" class="form-control" id="nomor_plat" name="nomor_plat" value="{{ old('nomor_plat') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tarif_sewa_per_hari">Tarif Sewa per Hari (Rp)</label>
                        <input type="number" class="form-control" id="tarif_sewa_per_hari" name="tarif_sewa_per_hari" value="{{ old('tarif_sewa_per_hari') }}" required min="0">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="disewa" {{ old('status') == 'disewa' ? 'selected' : '' }}>Disewa</option>
                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="foto">Foto Mobil</label>
                <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*">
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('mobils.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection