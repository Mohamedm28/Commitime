<!DOCTYPE html>
<html>
<head>
    <title>App Limit Request</title>
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
        .app-name {
            font-weight: bold;
            color: #333;
        }
        .time-limit {
            font-weight: bold;
            color: #e63946;
        }
        .button-container {
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            width: 80%;
            max-width: 250px;
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
            .button {
                width: 100%;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>App Limit Request</h2>
        <p>Your child has requested a time limit for the app: <span class="app-name">{{ $app_name }}</span></p>
        <p>Requested Time Limit: <span class="time-limit">{{ $time_limit }} minutes</span></p>
        <div class="button-container">
            <a href="{{ $approve_url }}" class="button">Approve Request</a>
        </div>
    </div>
</body>
</html>
