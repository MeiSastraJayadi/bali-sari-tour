<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Reservasi - Bali Sari Tour</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #fff;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 30px;
            text-align: center;
        }

        .logo {
            width: 80px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 20px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .divider {
            width: 60px;
            height: 2px;
            background: #333;
            margin: 12px auto 24px;
        }

        p {
            font-size: 15px;
            line-height: 1.6;
            color: #333;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
            font-weight: 500;
        }

        .footer {
            font-size: 13px;
            color: #555;
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('images/logo.png') }}" alt="Bali Sari Tour Logo" class="logo">

        <h1>Bali Sari Tour</h1>
        <div class="divider"></div>

        <p>Halo,</p>
        <p>Kami dengan senang hati menginformasikan bahwa reservasi Anda dengan kode:</p>
        <h2 style="margin: 8px 0; font-size: 22px; letter-spacing: 1px;">{{ $kode_reservasi }}</h2>
        <p>telah <strong>dikonfirmasi</strong>.</p>

        <p>Terima kasih telah mempercayakan perjalanan Anda bersama <strong>Bali Sari Tour</strong>.</p>

        <a href="{{ url('/reservation') }}" class="button">Lihat Reservasi</a>

        <div class="footer">
            &copy; {{ date('Y') }} Bali Sari Tour — Semua hak cipta dilindungi.
        </div>
    </div>
</body>
</html>
