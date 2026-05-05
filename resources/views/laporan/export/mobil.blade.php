<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Mobil - {{ $bulan }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e3a8a;
        }
        .report-title {
            font-size: 18px;
            margin: 10px 0;
        }
        .details {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .summary {
            margin-top: 30px;
        }
        .summary-table {
            width: 50%;
            margin-left: auto;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            color: #666;
            font-size: 12px;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-success { background-color: #28a745; color: #fff; }
        .badge-danger { background-color: #dc3545; color: #fff; }
        .mobil-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        
        <div class="company-name">RENTAL MOBIL WAKPIM</div>
        <div class="report-title">LAPORAN BERDASARKAN MOBIL</div>
        <div>Periode: {{ \Carbon\Carbon::parse($bulan)->format('F Y') }}</div>
        <div>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</div>
    </div>

    <!-- Informasi Mobil -->
    @if($selectedMobil)
    <div class="mobil-info">
        <h3>Informasi Mobil</h3>
        <p><strong>Merk/Model:</strong> {{ $selectedMobil->merk }} {{ $selectedMobil->model }}</p>
        <p><strong>Nomor Plat:</strong> {{ $selectedMobil->nomor_plat }}</p>
        <p><strong>Tarif per Hari:</strong> Rp {{ number_format($selectedMobil->tarif_sewa_per_hari, 0, ',', '.') }}</p>
        <p><strong>Status:</strong> 
            <span class="badge badge-{{ $selectedMobil->status == 'tersedia' ? 'success' : ($selectedMobil->status == 'disewa' ? 'warning' : 'danger') }}">
                {{ ucfirst($selectedMobil->status) }}
            </span>
        </p>
    </div>
    @endif

    <!-- Details -->
    <div class="details">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Sewa</th>
                    <th>Pelanggan</th>
                    <th>Tanggal Sewa</th>
                    <th>Tanggal Kembali</th>
                    <th>Durasi</th>
                    <th>Total Biaya</th>
                    <th>Denda</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penyewaans as $penyewaan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>#{{ str_pad($penyewaan->id, 6, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $penyewaan->pelanggan->nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($penyewaan->tanggal_sewa)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($penyewaan->tanggal_kembali_rencana)->format('d/m/Y') }}</td>
                    <td>{{ $penyewaan->durasi_sewa }} hari</td>
                    <td>Rp {{ number_format($penyewaan->total_biaya, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($penyewaan->denda, 0, ',', '.') }}</td>
                    <td>
                        @if($penyewaan->status == 'aktif')
                            <span class="badge badge-warning">Aktif</span>
                        @elseif($penyewaan->status == 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @else
                            <span class="badge badge-danger">Batal</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            @if($penyewaans->count() > 0)
            <tfoot>
                <tr>
                    <th colspan="6" class="text-right">Total:</th>
                    <th>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</th>
                    <th>Rp {{ number_format($penyewaans->sum('denda'), 0, ',', '.') }}</th>
                    <th></th>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    <!-- Summary -->
    @if($penyewaans->count() > 0)
    <div class="summary">
        <table class="summary-table">
            <tr>
                <th width="60%">Total Penyewaan</th>
                <td>{{ $penyewaans->count() }} transaksi</td>
            </tr>
            <tr>
                <th>Total Pendapatan</th>
                <td>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Rata-rata per Sewa</th>
                <td>Rp {{ number_format($penyewaans->count() > 0 ? $totalPendapatan / $penyewaans->count() : 0, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
    @else
    <div style="text-align: center; margin: 40px 0;">
        <p>Tidak ada data penyewaan untuk filter yang dipilih.</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dicetak otomatis oleh Sistem Rental Mobil WakPim</p>
        <p>Terima kasih telah menggunakan sistem kami</p>
    </div>
</body>
</html>