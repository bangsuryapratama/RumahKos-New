<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>403 Forbidden</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #111827;
            color: #f9fafb;
            text-align: center;
            padding-top: 100px;
        }
        h1 { font-size: 80px; color: #ef4444; }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        a:hover { background: #2563eb; }
    </style>
</head>
<body>
    <h1>403</h1>
    <p>akses ditolak bro 😅</p>
    <p>lu gak punya izin buat masuk ke halaman ini</p>
    <a href="{{ url('/') }}">balik ke home</a>
</body>
</html>