<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container { padding: 20px; }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header img {
            height: 60px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            text-align: right;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h3 {
            margin: 0 0 8px;
            font-size: 14px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        table th {
            background: #f0f0f0;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 15px;
        }
        .footer {
            border-top: 2px solid #000;
            padding-top: 10px;
            font-size: 11px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">

    <!-- Header -->
    <div class="header">
        <div>
            <img src="{{ public_path('images/logo.png') }}" alt="Company Logo">
        </div>
        <div>
            <h1>INVOICE</h1>
            <p>Invoice #: {{ $invoice_number }}</p>
            <p>Tanggal: {{ $date }}</p>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="section">
        <h3>Info Pelanggan</h3>
        <p><strong>Nama Tamu:</strong> {{ $customer['name'] }}</p>
        <p><strong>Telepon:</strong> {{ $customer['phone'] }}</p>
    </div>

    <!-- Trip Details -->
    <div class="section">
        <h3>Detail Perjalanan</h3>
        <table>
            <tr>
                <th>Nama Sopir</th>
                <td>{{ $trip['driver'] }}</td>
            </tr>
            <tr>
                <th>Tempat Penjemputan</th>
                <td>{{ $trip['pickup'] }}</td>
            </tr>
            <tr>
                <th>Tujuan</th>
                <td>{{ $trip['destination'] }}</td>
            </tr>
            <tr>
                <th>Tanggal Berangkat</th>
                <td>{{ $trip['date'] }}</td>
            </tr>
        </table>
    </div>

    <!-- Price -->
    <div class="section">
        <h3>Pembayaran</h3>
        <p class="total">Total: Rp {{ number_format($trip['price'], 0, ',', '.') }}</p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Terima kasih telah menggunakan layanan {{ $company['name'] }}.</p>
    </div>

</div>
</body>
</html>
