<!DOCTYPE html>
<html>
<head>
    <title>Daily Screen Time Report</title>
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
        .highlight {
            font-weight: bold;
            color: #333;
        }
        .usage-list {
            text-align: left;
            padding: 0;
            list-style: none;
        }
        .usage-list li {
            background: #f8f9fa;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
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
        <h2>📊 Daily Screen Time Report</h2>
        <p><strong>Child:</strong> <span class="highlight">{{ $child->first_name }} {{ $child->last_name }}</span></p>
        <p><strong>Date:</strong> <span class="highlight">{{ $reportData['report_date'] }}</span></p>
        <p><strong>Total Screen Time:</strong> <span class="highlight">{{ $reportData['screen_time_minutes'] }} minutes</span></p>

        <h3>📱 App Usage Details:</h3>
<ul class="usage-list">
    @if(is_array($reportData['app_usage_details']))
        @foreach($reportData['app_usage_details'] as $appData)
            <li><strong>{{ ucfirst($appData['app']) }}</strong>: {{ $appData['minutes'] }} minutes</li>
        @endforeach
    @else
        <li>No app usage recorded today.</li>
    @endif
</ul>

        <p>Keep track of your child's screen time and encourage healthy usage habits! 💡</p>

        <p class="footer">Need help? <a href="mailto:support@screentimemonitoring.com">Contact Support</a></p>
    </div>
</body>
</html>
