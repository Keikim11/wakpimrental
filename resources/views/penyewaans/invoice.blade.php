<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ str_pad($penyewaan->id, 6, '0', STR_PAD_LEFT) }}</title>

    <style>
        body {
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 30px;
            color: #2c2c2c;
        }

        .invoice-container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        

        .company-name {
            font-size: 26px;
            font-weight: bold;
            color: #1e3a8a;
        }

        .invoice-info {
            text-align: right;
        }

        .invoice-info h2 {
            margin: 0;
            font-size: 22px;
            color: #111827;
        }

        .invoice-info p {
            margin: 4px 0;
            font-size: 14px;
        }

        /* SECTION BOX */
        .section {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .section h3 {
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 18px;
            color: #1e3a8a;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        p {
            margin: 6px 0;
            font-size: 14px;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: #1e3a8a;
            color: white;
            padding: 12px;
            font-size: 14px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        /* TOTAL */
        .total-section {
            margin-top: 25px;
            text-align: right;
        }

        .total-box {
            display: inline-block;
            background: #f1f5f9;
            padding: 15px 25px;
            border-radius: 6px;
            font-size: 18px;
            font-weight: bold;
            color: #1e3a8a;
        }

        /* FOOTER */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
            border-top: 1px dashed #d1d5db;
            padding-top: 15px;
        }

        /* BUTTONS */
        .no-print {
            margin-top: 30px;
            text-align: center;
        }

        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #1e3a8a;
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .invoice-container {
                box-shadow: none;
                border-radius: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
<div class="invoice-container">

    <!-- HEADER -->
    <div class="header">
    <div style="display: flex; align-items: center; gap: 15px;">
        <img src="{{ asset('assets/logo.png') }}" alt="Logo" style="height: 60px;">
        <div class="company-name">RENTAL MOBIL WAKPIM</div>
    </div>

    <div class="invoice-info">
        <h2>INVOICE</h2>
        <p>No: #{{ str_pad($penyewaan->id, 6, '0', STR_PAD_LEFT) }}</p>
        <p>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>
</div>



    <!-- DETAILS -->
    <div class="details-grid">
        <div class="section">
            <h3>Informasi Penyewaan</h3>
            <p><strong>Kode:</strong> #{{ str_pad($penyewaan->id, 6, '0', STR_PAD_LEFT) }}</p>
            <p><strong>Tanggal Sewa:</strong> {{ \Carbon\Carbon::parse($penyewaan->tanggal_sewa)->format('d/m/Y') }}</p>
            <p><strong>Tanggal Kembali:</strong> {{ \Carbon\Carbon::parse($penyewaan->tanggal_kembali_rencana)->format('d/m/Y') }}</p>
            <p><strong>Durasi:</strong> {{ $penyewaan->durasi_sewa }} hari</p>
        </div>

        <div class="section">
            <h3>Pelanggan</h3>
            <p><strong>Nama:</strong> {{ $penyewaan->pelanggan->nama }}</p>
            <p><strong>Email:</strong> {{ $penyewaan->pelanggan->email }}</p>
            <p><strong>Telepon:</strong> {{ $penyewaan->pelanggan->telepon }}</p>
            <p><strong>No KTP:</strong> {{ $penyewaan->pelanggan->no_ktp }}</p>
        </div>
    </div>

    <!-- MOBIL -->
    <div class="section">
        <h3>Informasi Mobil</h3>
        <p><strong>Mobil:</strong> {{ $penyewaan->mobil->merk }} {{ $penyewaan->mobil->model }}</p>
        <p><strong>Plat:</strong> {{ $penyewaan->mobil->nomor_plat }}</p>
        <p><strong>Tarif / Hari:</strong> Rp {{ number_format($penyewaan->mobil->tarif_sewa_per_hari, 0, ',', '.') }}</p>
    </div>

    <!-- BIAYA -->
    <div class="section">
        <h3>Rincian Biaya</h3>
        <table>
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sewa Mobil</td>
                    <td>{{ $penyewaan->durasi_sewa }} hari</td>
                    <td>Rp {{ number_format($penyewaan->mobil->tarif_sewa_per_hari, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($penyewaan->total_biaya, 0, ',', '.') }}</td>
                </tr>
                @if($penyewaan->denda > 0)
                <tr>
                    <td>Denda</td>
                    <td>1</td>
                    <td>Rp {{ number_format($penyewaan->denda, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($penyewaan->denda, 0, ',', '.') }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- TOTAL -->
    <div class="total-section">
        <div class="total-box">
            TOTAL: Rp {{ number_format($penyewaan->total_biaya + $penyewaan->denda, 0, ',', '.') }}
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>Terima kasih telah menggunakan jasa Rental Mobil WakPim</p>
        <p>Invoice ini sah dan diproses secara sistem</p>
    </div>

    <!-- BUTTON -->
    <div class="no-print">
        <button class="btn btn-primary" onclick="window.print()">Cetak Invoice</button>
        <button class="btn btn-secondary" onclick="window.close()">Tutup</button>
    </div>

</div>
</body>
</html>
