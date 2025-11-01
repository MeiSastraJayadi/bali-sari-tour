<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Konfirmasi Pemesanan - Bali Sari Tour</title>
  <style>
    body {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      background-color: #fff;
      margin: 0;
      padding: 0;
      color: #333;
    }
    .container {
      max-width: 600px;
      margin: 30px auto;
      background-color: #fff;
      border: 1px solid #eaeaea;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      overflow: hidden;
    }
    .header {
      text-align: center;
      padding: 30px 30px 20px;
    }
    .logo {
      margin-bottom: 15px;
    }
    .logo img {
      width: 80px;
      height: auto;
    }
    .header h1 {
      margin: 0;
      font-size: 22px;
      color: #333;
      font-weight: 600;
    }
    .content {
      padding: 30px;
    }
    .content h2 {
      font-size: 18px;
      margin-bottom: 15px;
      color: #333;
    }
    .details {
      border-top: 1px solid #eee;
      border-bottom: 1px solid #eee;
      padding: 15px 0;
      margin-bottom: 20px;
    }
    .details p {
      margin: 8px 0;
      font-size: 15px;
      color: #333;
    }
    .details strong {
      color: #000;
    }
    .note {
      background: #f8f8f8;
      border-left: 4px solid #333;
      padding: 10px 15px;
      font-size: 14px;
      color: #333;
      margin-bottom: 20px;
    }
    .btn {
      display: inline-block;
      background-color: #333;
      color: #fff !important;
      text-decoration: none;
      padding: 12px 24px;
      border-radius: 8px;
      font-weight: 600;
      letter-spacing: 0.3px;
      text-align: center;
    }
    .btn:hover {
      background-color: #000;
    }
    .footer {
      text-align: center;
      font-size: 13px;
      color: #666;
      padding: 20px 30px;
      background-color: #f9f9f9;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="logo">
        <img src="https://balisaritour.site/images/logo.png" alt="Bali Sari Tour Logo">
      </div>
      <h1>Permintaan Pemesanan Baru</h1>
    </div>

    <div class="content">
      <h2>Detail Pemesanan</h2>

      <div class="details">
        <p><strong>Kode Reservasi:</strong> {{ $code }}</p>
        <p><strong>Nama Tamu:</strong> {{ $guest_name }}</p>
        <p><strong>Lokasi Penjemputan:</strong> {{ $pickup_location }}</p>
        <p><strong>Tujuan:</strong> {{ $destination }}</p>
        <p><strong>Biaya:</strong> {{ $price }}</p>
      </div>

      <div class="note">
        <strong>Catatan:</strong><br>
        {{ $note }}
      </div>

      <p style="text-align:center; margin-top: 25px;">
        <a href="{{ $confirmation_url }}" class="btn">Konfirmasi Pemesanan</a>
      </p>
    </div>

    <div class="footer">
      Email ini dikirim secara otomatis oleh sistem Bali Sari Tour.<br>
      &copy; {{ date('Y') }} Bali Sari Tour. Semua hak dilindungi.
    </div>
  </div>
</body>
</html>
