<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Verification</title>
    <style>
        .verify-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: black;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Email Verification</h2>
    <p>Please click the following button to verify your email:</p>
    <a href="{{ $temporaryUrl }}" class="verify-button">Verify Email</a>
</body>
</html>

