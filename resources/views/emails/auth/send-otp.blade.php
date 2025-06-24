<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twilio Verification Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dddddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #f4f4f4;
            padding: 10px 0;
            text-align: center;
        }
        .header img {
            width: 300px;
            max-width: 100%;
            height: auto;
        }
        .content {
            padding: 20px;
        }
        .content h1 {
            font-size: 20px;
            color: #333;
        }
        .content p {
            font-size: 16px;
            line-height: 1.5;
        }
        .verification-code {
            font-size: 24px;
            font-weight: bold;
            color: #2A52BE;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            font-size: 14px;
            color: #999;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="{{ asset('/img/logo/fractalpay_logo.png') }}" alt="Twilio Logo">
    </div>
    <div class="content">
        <h1>Hi {{ $user->full_name }},</h1>
        <p>Please enter the following verification code to access your FractalPay Account.</p>
        <div class="verification-code">{{ $OTP }}</div>
        <p>In case you were not trying to access your FractalPay Account & are seeing this email, please follow the instructions below:</p>
        <ul>
            <li>Reset your FractalPay password.</li>
            <li>Check if any changes were made to your account & user settings. If yes, revert them immediately.</li>
            <li>If you are unable to access your FractalPay Account then contact Twilio Support.</li>
        </ul>
    </div>
    <div class="footer">
        <p>{{ env('APP_ADDRESS')  }}</p>
    </div>
</div>
</body>
</html>
