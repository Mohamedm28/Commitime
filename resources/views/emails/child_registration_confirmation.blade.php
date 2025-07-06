<!DOCTYPE html>
<html>
<head>
    <title>Child Registration Confirmation</title>
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
        .child-name {
            font-weight: bold;
            color: #333;
        }
        .alert {
            color: #e63946;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            margin-top: 20px;
        }
        /* Mobile Optimization */
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
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Child Registration Confirmation</h2>
        <p>Hello,</p>
        <p>Your child, <span class="child-name">{{ $child->first_name }} {{ $child->last_name }}</span>, has successfully registered on our platform.</p>
        <p class="alert">If you did not authorize this registration, please contact us immediately.</p>
        <p>Thank you,</p>
        <p><strong>Screen Time Monitoring Team</strong></p>
        <p class="footer">Need help? <a href="mailto:support@screentimemonitoring.com">Contact Support</a></p>
    </div>
</body>
</html>
