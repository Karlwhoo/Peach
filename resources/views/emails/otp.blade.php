<!DOCTYPE html>
<html>
<head>
    <title>Email Verification OTP</title>
</head>
<body>
    <h2>Email Verification</h2>
    <p>Hello {{ $name }},</p>
    <p>Your OTP for email verification is: <strong>{{ $otp }}</strong></p>
    <p>This code will expire in 10 minutes.</p>
    <p>If you didn't request this code, please ignore this email.</p>
    <br>
    <p>Best regards,</p>
    <p>The Apple Peach House Team</p>
</body>
</html> 