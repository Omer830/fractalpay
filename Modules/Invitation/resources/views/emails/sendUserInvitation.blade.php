<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation</title>
</head>
<body style="margin: 0; padding: 0; width: 100% !important; height: 100% !important; background-color: #f4f4f4; font-family: 'Arial', sans-serif;">
    <div style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden;">
        <!-- Email Header -->
        <div style="padding: 20px; text-align: center;">
            <img src="{{ asset('img/logo/fractalpay_logo.png') }}" alt="Fractal Pay Logo" style="max-width: 170px; display: block; margin: 0 auto;">
        </div>

        <!-- Divider -->
        <div style="width: 100%; height: 1px; background-color: #ddd; margin: 0;"></div>

        <!-- Email Content -->
        <div style="background-color: #ffffff; padding: 0; margin: 9px 44px 30px 44px; position: relative; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); border-radius: 10px; overflow: hidden; display: flex; flex-direction: column; align-items: center; height: auto;">
            <!-- Background Image Section -->
            <div style="width: 100%; height: 95px; background-image: url('{{ asset('img/Email/Email_Header.png') }}'); background-size: cover; background-position: center; border-top-left-radius: 10px; border-top-right-radius: 10px;"></div>
            
            <!-- Content Section -->
            <div style="padding: 20px; text-align: center;">
                <h2 style="margin: 0 0 15px; font-size: 16px; line-height: 1.4; color: #5B90C5; font-weight: bold;">Hello,</h2>
                <p style="margin: 0 0 15px; font-size: 14px; line-height: 1.5; max-width: 450px; word-wrap: break-word;">You have been invited by <strong style="color: #4783BE; font-weight: bold;">{{$inviterName}}</strong></p>
                <p style="margin: 0 0 15px; font-size: 14px; line-height: 1.5; max-width: 450px; word-wrap: break-word;">Your referral code is: <strong style="color: #4783BE; font-weight: bold;">{{$referralCode}}</strong></p>
                <p style="margin: 0 0 15px; font-size: 14px; line-height: 1.5; max-width: 450px; word-wrap: break-word;">You've been invited by {{$inviterName}} to join Fractal Pay, a platform dedicated to making healthcare management easier.</p>
                <p style="margin: 0 0 15px; font-size: 14px; line-height: 1.5; max-width: 450px; word-wrap: break-word;">Your contributions can be easily tracked from your dashboard, ensuring you see the impact you're making.</p>
                <a href="{{$actionUrl}}" style="background-color: #4682BE; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px; font-weight: bold;">Open Invitation</a>
                <p style="margin-top: 10px; font-size: 14px; line-height: 1.5; max-width: 450px; word-wrap: break-word;">
                    Use it to join Fractal Pay and start making a difference today!<br>
                    Thank you for being a part of our community and<br>
                    helping those in need.<br>
                    Regards, <br>
                    <strong style="color:#4783BE; font-weight: bold;">Fractal Pay</strong>
                </p>
            </div>
        </div>

        <!-- Read Me Section -->
        <div style="background-color: #ffffff; padding: 15px; margin: -24px 44px 30px 44px; text-align: left; font-size: 12px; color: #555; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); border-radius: 10px;">
            <p><strong style="color:#4783BE; font-weight: bold;">Read me</strong></h2>
            <p>If you're having trouble clicking the "Open Invitation" button, copy and paste the URL below into your browser:</p>
            <p><a href="{{$actionUrl}}" style="color: #007bff; text-decoration: none;">{{$actionUrl}}</a></p>
        </div>
    </div>
</body>
</html>
