<!DOCTYPE html>
<html>
<head>
    <title>Reset Your Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #333;
            font-size: 22px;
        }
        p {
            font-size: 16px;
            color: #555;
            line-height: 1.5;
        }
        .btn {
            display: inline-block;
            background: #007bff;
            color: #ffffff;
            padding: 12px 20px;
            font-size: 16px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            margin-top: 20px;
        }
        @media screen and (max-width: 480px) {
            .container {
                width: 90%;
                padding: 15px;
            }
            h2 {
                font-size: 20px;
            }
            p {
                font-size: 14px;
            }
            .btn {
                font-size: 14px;
                padding: 10px 18px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔒 Password Reset Request</h2>
        <p>We received a request to reset your password. Click the button below to proceed:</p>
        <a href="{{ $resetUrl }}" class="btn">Reset Password</a>
        <p>If you did not request this, please ignore this email. Your account security remains intact.</p>
        <p class="footer">Need help? <a href="mailto:support@screentimemonitoring.com">Contact Support</a></p>
    </div>
</body>
</html>

