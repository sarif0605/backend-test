<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Telah Dikirim</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        .message {
            margin-top: 20px;
            font-size: 18px;
            color: #333;
        }
        .code {
            display: inline-block;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            font-size: 20px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Kode OTP {{ $name }}</h2>
        <div class="code">{{ $otpCode }}</div>
        <p class="message">Jangan Bagikan Kode Otp {{ $name }} ke orang lain.</p>
    </div>
</body>
</html>