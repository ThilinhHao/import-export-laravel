<!-- resources/views/emails/reset-password.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <p>You are receiving this email because we received a password reset request for your account.</p>
    <p>Please click the button below to reset your password:</p>
    <a href="{{ $resetUrl }}" target="_blank" rel="noopener noreferrer">
        <button style="background-color: #007bff; border: none; color: white; padding: 10px 20px; text-align: center; display: inline-block; font-size: 16px; margin-bottom: 20px; cursor: pointer;">
            Reset Password
        </button>
    </a>
    <p>If you did not request a password reset, no further action is required.</p>
</body>
</html>
