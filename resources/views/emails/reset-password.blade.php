<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .content p {
            color: #374151;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #2563eb;
        }
        .button-container {
            text-align: center;
        }
        .info-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 16px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 0;
            color: #92400e;
            font-size: 14px;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
        .link {
            color: #3b82f6;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🔐 Reset Password</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Halo,</p>
            
            <p>
                Kami menerima permintaan untuk mereset password akun RumahKos Anda. 
                Klik tombol di bawah untuk membuat password baru:
            </p>

            <div class="button-container">
                <a href="{{ $resetUrl }}" class="button">
                    Reset Password Sekarang
                </a>
            </div>

            <div class="info-box">
                <p>
                    <strong>⚠️ Penting:</strong> Link ini hanya berlaku selama <strong>1 jam</strong> 
                    dan hanya dapat digunakan <strong>satu kali</strong>.
                </p>
            </div>

            <div class="divider"></div>

            <p style="font-size: 14px; color: #6b7280;">
                Jika tombol tidak berfungsi, copy dan paste link berikut ke browser Anda:
            </p>
            
            <p style="font-size: 12px; color: #9ca3af; word-break: break-all;">
                {{ $resetUrl }}
            </p>

            <div class="divider"></div>

            <p style="font-size: 14px; color: #6b7280;">
                <strong>Tidak merasa request reset password?</strong><br>
                Abaikan email ini. Password Anda tetap aman dan tidak akan berubah.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>RumahKos</strong></p>
            <p>Sistem Manajemen Kost Modern</p>
            <p style="margin-top: 15px;">
                Butuh bantuan? Hubungi kami di 
                <a href="mailto:support@rumahkos.com" class="link">support@rumahkos.com</a>
            </p>
            <p style="margin-top: 10px; font-size: 12px;">
                © {{ date('Y') }} RumahKos. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>