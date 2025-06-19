{{-- <!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #07AF8B;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 25px;
        }
        .footer {
            background-color: #007570;
            color: white;
            padding: 15px 20px;
            text-align: center;
            font-size: 12px;
        }
        .qr-code {
            text-align: center;
            margin: 25px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border-left: 4px solid #FFCA00;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .details-table td:first-child {
            font-weight: bold;
            color: #007570;
            width: 30%;
        }
        .highlight {
            background-color: rgba(255, 202, 0, 0.1);
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 3px solid #FFCA00;
        }
        .button {
            display: inline-block;
            background-color: #FFCA00;
            color: #333;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 15px 0;
        }
    </style>
</head>
<body>
<div class='container'>
    <div class='header'>
        <h2>Appointment Approved</h2>
    </div>
    <div class='content'>
        <p>Dear {{ $visitor_name }},</p>
        <p>Your appointment request has been <strong>approved</strong>. Below are your appointment details:</p>
        <table class='details-table'>
            <tr>
                <td><strong>Host:</strong></td>
                <td>{{ $host_name }}</td>
            </tr>
            <tr>
                <td><strong>Date:</strong></td>
                <td>{{ $visit_date }}</td>
            </tr>
            <tr>
                <td><strong>Time:</strong></td>
                <td>{{ $visit_time }}</td>
            </tr>
            <tr>
                <td><strong>Location:</strong></td>
                <td>{{ $visit_location }}</td>
            </tr>
            <tr>
                <td><strong>Purpose:</strong></td>
                <td>{{ $visit_purpose }}</td>
            </tr>
            <tr>
                <td><strong>Unique Code:</strong></td>
                <td><strong>{{ $unique_code }}</strong></td>
            </tr>
        </table>
        <div class='qr-code'>
            <p><strong>Show this QR code at the reception on arrival:</strong></p>
            <img src="{{ $qr_code_image }}" alt='QR Code' style='width: 200px; height: 200px;'>
        </div>
        <p>Please arrive on time. If you are unable to attend, kindly notify your host in advance.</p>
    </div>
    <div class='footer'>
        <p>This is an automated message. Please do not reply.</p>
    </div>
</div>
</body>
</html> --}}

<!-- resources/views/emails/visit-approved.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Visit Approved</title>
</head>
<body>
    <h1>Your Visit has been Approved!</h1>

    <p>Dear Visitor,</p>

    <p>Your visit with the following details has been approved:</p>

    <ul>
        <li>Visitor Name: {{ $visit->visitor_name ?? 'N/A' }}</li>
        <li>Visit Date: {{ $visit->visit_date ?? 'N/A' }}</li>
        <li>Unique Code: {{ $visit->unique_code ?? 'N/A' }}</li>
    </ul>

    <p>
        <img src="{{ asset('qrcodes/' . $visit->unique_code . '.png') }}" alt="QR Code">
    </p>

    <p>Thank you!</p>
</body>
</html>

