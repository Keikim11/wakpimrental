@extends('layouts.app')

@section('title', 'Proses Pengembalian')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Proses Pengembalian Mobil</h3>
    </div>
    <div class="card-body">
        <!-- Informasi Penyewaan -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Penyewaan</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Kode Sewa</th>
                                        <td>#{{ str_pad($penyewaan->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Pelanggan</th>
                                        <td>{{ $penyewaan->pelanggan->nama }}</td>
                                    </tr>
                                    <tr>
                                        <th>Telepon</th>
                                        <td>{{ $penyewaan->pelanggan->telepon }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Sewa</th>
                                        <td>{{ \Carbon\Carbon::parse($penyewaan->tanggal_sewa)->format('d/m/Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Mobil</th>
                                        <td>{{ $penyewaan->mobil->merk }} {{ $penyewaan->mobil->model }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nomor Plat</th>
                                        <td>{{ $penyewaan->mobil->nomor_plat }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Kembali Rencana</th>
                                        <td>{{ \Carbon\Carbon::parse($penyewaan->tanggal_kembali_rencana)->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Durasi Sewa</th>
                                        <td>{{ $penyewaan->durasi_sewa }} hari</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Pengembalian -->
        <form action="{{ route('pengembalians.store') }}" method="POST" id="pengembalianForm">
    @csrf
    <input type="hidden" name="penyewaan_id" value="{{ $penyewaan->id }}">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal_kembali_aktual">Tanggal Kembali Aktual *</label>
                        <input type="datetime-local" class="form-control @error('tanggal_kembali_aktual') is-invalid @enderror" 
                               id="tanggal_kembali_aktual" name="tanggal_kembali_aktual" 
                               value="{{ old('tanggal_kembali_aktual', \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}" required>
                        @error('tanggal_kembali_aktual')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kondisi_mobil">Kondisi Mobil saat Dikembalikan *</label>
                        <select class="form-control @error('kondisi_mobil') is-invalid @enderror" 
                                id="kondisi_mobil" name="kondisi_mobil" required>
                            <option value="" disabled selected>-- Pilih Kondisi --</option>
                            <option value="baik" {{ old('kondisi_mobil') == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak_ringan" {{ old('kondisi_mobil') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="rusak_berat" {{ old('kondisi_mobil') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                        @error('kondisi_mobil')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="catatan">Catatan (Opsional)</label>
                <textarea class="form-control @error('catatan') is-invalid @enderror" 
                          id="catatan" name="catatan" rows="3" 
                          placeholder="Catatan tentang kondisi mobil atau hal lainnya">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Informasi Denda -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Denda</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" onclick="hitungDenda()">
                                    <i class="fas fa-calculator"></i> Hitung Denda
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="dendaInfo">
                                <p class="text-muted">Klik tombol "Hitung Denda" untuk melihat perhitungan denda.</p>
                            </div>
                            <input type="hidden" name="denda" id="denda" value="0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ringkasan -->
             <div class="row mt-4">
        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Ringkasan</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <td><strong>Total Biaya Sewa</strong></td>
                            <td>Rp {{ number_format($penyewaan->total_biaya, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Denda</strong></td>
                            <td id="dendaDisplay">Rp 0</td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td><strong>Total Pembayaran</strong></td>
                            <td id="totalPembayaranDisplay">
                                Rp {{ number_format($penyewaan->total_biaya, 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Pengembalian
        </button>
        <a href="{{ route('penyewaans.show', $penyewaan->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function hitungDenda() {
    const tanggalKembaliAktual = document.getElementById('tanggal_kembali_aktual');
    const kondisiMobil = document.getElementById('kondisi_mobil');
    const penyewaanId = {{ $penyewaan->id }};
    
    // Cek apakah elemen ada
    if (!tanggalKembaliAktual || !kondisiMobil) {
        console.error('Element not found: tanggal_kembali_aktual or kondisi_mobil');
        alert('Gagal mengakses elemen form. Silakan refresh halaman.');
        return;
    }
    
    const tanggalValue = tanggalKembaliAktual.value;
    const kondisiValue = kondisiMobil.value;
    
    if (!tanggalValue || !kondisiValue) {
        alert('Harap isi tanggal kembali aktual dan kondisi mobil terlebih dahulu.');
        return;
    }

    // Show loading
    const dendaInfo = document.getElementById('dendaInfo');
    if (dendaInfo) {
        dendaInfo.innerHTML = '<p class="text-center"><i class="fas fa-spinner fa-spin"></i> Menghitung denda...</p>';
    }
    
    // Hitung denda via API
    fetch(`/api/hitung-denda/${penyewaanId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            tanggal_kembali_aktual: tanggalValue,
            kondisi_mobil: kondisiValue
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            updateDendaDisplay(data);
        } else {
            showErrorMessage('Gagal menghitung denda: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Terjadi kesalahan saat menghitung denda. Silakan coba lagi.');
    });
}

function updateDendaDisplay(data) {
    // Format tampilan denda
    let dendaInfoHTML = `
        <table class="table table-bordered">
            <tr>
                <td width="60%">Tanggal Kembali Rencana</td>
                <td>${data.tanggal_kembali_rencana || 'N/A'}</td>
            </tr>
            <tr>
                <td>Tanggal Kembali Aktual</td>
                <td>${data.tanggal_kembali_aktual || 'N/A'}</td>
            </tr>
            <tr>
                <td>Keterlambatan</td>
                <td>${data.hari_terlambat || 0} hari</td>
            </tr>
            <tr>
                <td>Denda Keterlambatan</td>
                <td>Rp ${parseFloat(data.denda_keterlambatan || 0).toLocaleString('id-ID')}</td>
            </tr>
            <tr>
                <td>Denda Kerusakan</td>
                <td>Rp ${parseFloat(data.denda_kerusakan || 0).toLocaleString('id-ID')}</td>
            </tr>
            <tr class="font-weight-bold">
                <td>Total Denda</td>
                <td>Rp ${parseFloat(data.total_denda || 0).toLocaleString('id-ID')}</td>
            </tr>
        </table>
    `;
    
    // Update elemen HTML dengan pengecekan null
    const dendaInfo = document.getElementById('dendaInfo');
    const dendaInput = document.getElementById('denda');
    const dendaDisplay = document.getElementById('dendaDisplay');
    const totalPembayaran = document.getElementById('totalPembayaran');
    
    if (dendaInfo) dendaInfo.innerHTML = dendaInfoHTML;
    if (dendaInput) dendaInput.value = data.total_denda || 0;
    
    // Update tampilan denda
    if (dendaDisplay) {
        dendaDisplay.textContent = 'Rp ' + parseFloat(data.total_denda || 0).toLocaleString('id-ID');
    }
    
    // Update total pembayaran
    if (totalPembayaran) {
        const totalBiaya = parseFloat({{ $penyewaan->total_biaya ?? 0 }});
        const totalPembayaranValue = totalBiaya + parseFloat(data.total_denda || 0);
        totalPembayaran.textContent = 'Rp ' + totalPembayaranValue.toLocaleString('id-ID');
    }
}

function showErrorMessage(message) {
    const dendaInfo = document.getElementById('dendaInfo');
    if (dendaInfo) {
        dendaInfo.innerHTML = `<p class="text-danger">${message}</p>`;
    } else {
        alert(message);
    }
}

// Event listeners dengan pengecekan elemen
document.addEventListener('DOMContentLoaded', function() {
    const tanggalField = document.getElementById('tanggal_kembali_aktual');
    const kondisiField = document.getElementById('kondisi_mobil');
    
    if (tanggalField && kondisiField) {
        // Auto hitung denda when fields change
        tanggalField.addEventListener('change', function() {
            if (kondisiField.value) {
                hitungDenda();
            }
        });

        kondisiField.addEventListener('change', function() {
            if (tanggalField.value) {
                hitungDenda();
            }
        });
        
        // Hitung denda saat halaman dimuat jika data sudah ada
        if (tanggalField.value && kondisiField.value) {
            setTimeout(hitungDenda, 1000);
        }
    } else {
        console.error('Form elements not found on page load');
    }
});

// Fallback untuk menampilkan alert jika ada error
window.addEventListener('error', function(e) {
    if (e.message.includes("Cannot set properties of null")) {
        console.error('DOM Element not found:', e);
        // Coba hitung denda dengan cara manual
        manualCalculateDenda();
    }
});

// Fungsi fallback untuk kalkulasi manual
function manualCalculateDenda() {
    const tanggalKembaliAktual = document.getElementById('tanggal_kembali_aktual');
    const kondisiMobil = document.getElementById('kondisi_mobil');
    
    if (!tanggalKembaliAktual || !kondisiMobil) return;
    
    const tarifPerHari = {{ $penyewaan->mobil->tarif_sewa_per_hari ?? 0 }};
    const tanggalRencana = '{{ $penyewaan->tanggal_kembali_rencana ?? "" }}';
    const tanggalAktual = tanggalKembaliAktual.value;
    const kondisi = kondisiMobil.value;
    
    if (!tanggalAktual || !kondisi) return;
    
    // Hitung manual di client side
    const dateRencana = new Date(tanggalRencana);
    const dateAktual = new Date(tanggalAktual);
    const hariTerlambat = Math.max(0, Math.floor((dateAktual - dateRencana) / (1000 * 60 * 60 * 24)));
    const dendaKeterlambatan = hariTerlambat * (tarifPerHari * 0.1);
    
    let dendaKerusakan = 0;
    if (kondisi === 'rusak_ringan') dendaKerusakan = 500000;
    if (kondisi === 'rusak_berat') dendaKerusakan = 2000000;
    
    const totalDenda = dendaKeterlambatan + dendaKerusakan;
    
    // Update display secara manual
    const totalBiaya = {{ $penyewaan->total_biaya ?? 0 }};
    const totalPembayaran = totalBiaya + totalDenda;
    
    alert(`Perhitungan manual:\nKeterlambatan: ${hariTerlambat} hari\nDenda: Rp ${totalDenda.toLocaleString('id-ID')}\nTotal Bayar: Rp ${totalPembayaran.toLocaleString('id-ID')}`);
}
</script>
@endpush