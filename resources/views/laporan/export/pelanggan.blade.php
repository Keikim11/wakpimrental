<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pelanggan - {{ $bulan }}</title>
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
        .report-period {
            font-size: 14px;
            color: #666;
        }
        .filter-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            color: #666;
            font-size: 12px;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
      
        <div class="company-name">RENTAL MOBIL WAKPIM</div>
        <div class="report-title">LAPORAN PELANGGAN</div>
        <div class="report-period">
            Periode: {{ \Carbon\Carbon::parse($bulan)->format('F Y') }}
            @if($selectedPelanggan)
                | Pelanggan: {{ $selectedPelanggan->nama }}
            @endif
        </div>
    </div>

    <!-- Filter Information -->
    <div class="filter-info">
        <strong>Filter:</strong>
        Bulan: {{ \Carbon\Carbon::parse($bulan)->format('F Y') }}
        @if($selectedPelanggan)
            | Pelanggan: {{ $selectedPelanggan->nama }}
        @else
            | Semua Pelanggan
        @endif
        | Total Data: {{ $penyewaans->count() }}
    </div>

    @if($penyewaans->count() > 0)
    <!-- Tabel Data Penyewaan -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Sewa</th>
                <th>Pelanggan</th>
                <th>Mobil</th>
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
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>#{{ str_pad($penyewaan->id, 6, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $penyewaan->pelanggan->nama }}</td>
                <td>{{ $penyewaan->mobil->merk }} {{ $penyewaan->mobil->model }}</td>
                <td>{{ \Carbon\Carbon::parse($penyewaan->tanggal_sewa)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($penyewaan->tanggal_kembali_rencana)->format('d/m/Y') }}</td>
                <td class="text-center">{{ $penyewaan->durasi_sewa }} hari</td>
                <td class="text-right">Rp {{ number_format($penyewaan->total_biaya, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($penyewaan->denda, 0, ',', '.') }}</td>
                <td class="text-center">
                    @if($penyewaan->status == 'aktif')
                        <span style="color: orange;">AKTIF</span>
                    @elseif($penyewaan->status == 'selesai')
                        <span style="color: green;">SELESAI</span>
                    @else
                        <span style="color: red;">BATAL</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="7" class="text-right"><strong>Total:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($penyewaans->sum('denda'), 0, ',', '.') }}</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <!-- Summary -->
    <div class="summary">
        <h3>Ringkasan Laporan</h3>
        <table style="width: 50%;">
            <tr>
                <td width="40%"><strong>Periode Laporan</strong></td>
                <td>{{ \Carbon\Carbon::parse($bulan)->format('F Y') }}</td>
            </tr>
            @if($selectedPelanggan)
            <tr>
                <td><strong>Pelanggan</strong></td>
                <td>{{ $selectedPelanggan->nama }}</td>
            </tr>
            <tr>
                <td><strong>Email Pelanggan</strong></td>
                <td>{{ $selectedPelanggan->email }}</td>
            </tr>
            <tr>
                <td><strong>Telepon</strong></td>
                <td>{{ $selectedPelanggan->telepon }}</td>
            </tr>
            @else
            <tr>
                <td><strong>Jenis Laporan</strong></td>
                <td>Semua Pelanggan</td>
            </tr>
            @endif
            <tr>
                <td><strong>Total Penyewaan</strong></td>
                <td>{{ $penyewaans->count() }} transaksi</td>
            </tr>
            <tr>
                <td><strong>Total Pendapatan</strong></td>
                <td>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Denda</strong></td>
                <td>Rp {{ number_format($penyewaans->sum('denda'), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Keseluruhan</strong></td>
                <td><strong>Rp {{ number_format($totalPendapatan + $penyewaans->sum('denda'), 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- Statistik Status -->
    <div class="summary">
        <h3>Statistik Berdasarkan Status</h3>
        <table style="width: 50%;">
            <tr>
                <td width="40%"><strong>Penyewaan Aktif</strong></td>
                <td>{{ $penyewaans->where('status', 'aktif')->count() }} transaksi</td>
            </tr>
            <tr>
                <td><strong>Penyewaan Selesai</strong></td>
                <td>{{ $penyewaans->where('status', 'selesai')->count() }} transaksi</td>
            </tr>
            <tr>
                <td><strong>Penyewaan Batal</strong></td>
                <td>{{ $penyewaans->where('status', 'batal')->count() }} transaksi</td>
            </tr>
        </table>
    </div>
    @else
    <div class="no-data">
        <h3>Tidak Ada Data</h3>
        <p>Tidak ada data penyewaan untuk periode dan filter yang dipilih.</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->name }} | {{ auth()->user()->role }}</p>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        <p>Rental Mobil WakPim &copy; {{ date('Y') }} - All rights reserved</p>
    </div>

    <!-- Print Button -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" class="btn btn-primary">Cetak Laporan</button>
        <button onclick="window.close()" class="btn btn-secondary">Tutup</button>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            // Uncomment line below untuk auto print
            // window.print();
        }
    </script>
</body>
</html>