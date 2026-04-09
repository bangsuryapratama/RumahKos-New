<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>500 Server Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #111827;
            color: #f9fafb;
            text-align: center;
            padding-top: 100px;
        }
        h1 { font-size: 80px; color: #f59e0b; }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #f59e0b;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        a:hover { background: #d97706; }
    </style>
</head>
<body>
    <h1>500</h1>
    <p>server lagi error 😵</p>
    <p>coba refresh atau balik lagi nanti</p>
    <a href="{{ url()->current() }}">refresh</a>
</body>
</html>