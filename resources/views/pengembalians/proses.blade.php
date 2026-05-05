@extends('layouts.app')

@section('title', 'Proses Pengembalian - #' . str_pad($penyewaan->id ?? 0, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-undo"></i> Proses Pengembalian
        </h3>
        <div class="card-tools">
            <a href="{{ route('penyewaans.show', $penyewaan->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Detail Penyewaan
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Informasi Penyewaan -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Penyewaan</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Kode Sewa</th>
                                <td>#{{ str_pad($penyewaan->id, 6, '0', STR_PAD_LEFT) }}</td>
                            </tr>
                            <tr>
                                <th>Pelanggan</th>
                                <td>{{ $penyewaan->pelanggan->nama ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Mobil</th>
                                <td>{{ $penyewaan->mobil->merk ?? 'N/A' }} {{ $penyewaan->mobil->model ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Sewa</th>
                                <td>{{ \Carbon\Carbon::parse($penyewaan->tanggal_sewa)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Kembali Rencana</th>
                                <td>{{ \Carbon\Carbon::parse($penyewaan->tanggal_kembali_rencana)->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Status Pengembalian</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Tanggal Hari Ini</th>
                                <td>{{ $tanggalHariIni->format('d/m/Y') ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Keterlambatan</th>
                                <td>
                                    @if($keterlambatan > 0)
                                        <span class="badge badge-danger">Terlambat {{ $keterlambatan }} hari</span>
                                    @else
                                        <span class="badge badge-success">Tepat waktu</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Denda</th>
                                <td class="font-weight-bold text-danger">
                                    Rp {{ number_format($denda ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <th>Total Biaya + Denda</th>
                                <td class="font-weight-bold text-success">
                                    Rp {{ number_format($penyewaan->total_biaya + $denda, 0, ',', '.') }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Pengembalian -->
        <form action="{{ route('pengembalian.simpan') }}" method="POST" id="formPengembalian">
            @csrf
            <input type="hidden" name="penyewaan_id" value="{{ $penyewaan->id }}">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal_kembali_aktual">Tanggal Kembali Aktual *</label>
                        <input type="date" class="form-control @error('tanggal_kembali_aktual') is-invalid @enderror" 
                               id="tanggal_kembali_aktual" name="tanggal_kembali_aktual" 
                               value="{{ old('tanggal_kembali_aktual', $tanggalHariIni->format('Y-m-d')) }}" required>
                        @error('tanggal_kembali_aktual')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kondisi_mobil">Kondisi Mobil *</label>
                        <select class="form-control @error('kondisi_mobil') is-invalid @enderror" 
                                id="kondisi_mobil" name="kondisi_mobil" required>
                            <option value="">-- Pilih Kondisi --</option>
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

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="denda">Denda (Rp) *</label>
                        <input type="number" class="form-control @error('denda') is-invalid @enderror" 
                               id="denda" name="denda" value="{{ old('denda', $denda) }}" min="0" required>
                        @error('denda')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted" id="infoDenda">
                            @if($keterlambatan > 0)
                                Denda untuk keterlambatan {{ $keterlambatan }} hari
                            @else
                                Tidak ada denda
                            @endif
                        </small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="catatan">Catatan</label>
                        <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                  id="catatan" name="catatan" rows="3" 
                                  placeholder="Catatan kondisi mobil atau informasi lainnya...">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informasi Total -->
            <div class="alert alert-info">
                <h5><i class="fas fa-calculator"></i> Ringkasan Pembayaran</h5>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="60%">Total Biaya Sewa:</td>
                        <td class="text-right">Rp {{ number_format($penyewaan->total_biaya, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Denda:</td>
                        <td class="text-right" id="displayDenda">Rp {{ number_format($denda, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="font-weight-bold">
                        <td>Total yang harus dibayar:</td>
                        <td class="text-right text-success" id="displayTotal">
                            Rp {{ number_format($penyewaan->total_biaya + $denda, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-check-circle"></i> Proses Pengembalian
                </button>
                <a href="{{ route('penyewaans.show', $penyewaan->id) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const totalBiaya = {{ $penyewaan->total_biaya }};
    
    // Hitung denda otomatis ketika tanggal berubah
    $('#tanggal_kembali_aktual').on('change', function() {
        const tanggalKembali = $(this).val();
        const penyewaanId = {{ $penyewaan->id }};
        
        if (tanggalKembali) {
            $.ajax({
                url: '{{ route("pengembalian.hitung-denda") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    penyewaan_id: penyewaanId,
                    tanggal_kembali_aktual: tanggalKembali
                },
                success: function(response) {
                    if (response.success) {
                        $('#denda').val(response.denda);
                        $('#displayDenda').text('Rp ' + response.denda.toLocaleString('id-ID'));
                        $('#displayTotal').text('Rp ' + (totalBiaya + response.denda).toLocaleString('id-ID'));
                        $('#infoDenda').text(response.keterlambatan);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat menghitung denda.');
                }
            });
        }
    });
    
    // Validasi form sebelum submit
    $('#formPengembalian').on('submit', function(e) {
        const kondisi = $('#kondisi_mobil').val();
        if (!kondisi) {
            e.preventDefault();
            alert('Pilih kondisi mobil terlebih dahulu.');
            $('#kondisi_mobil').focus();
        }
    });
});
</script>
@endpush