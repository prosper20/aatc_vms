<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Visit Confirmed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background-color: #07AF8B;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .header-logo {
            width: 100%;
            max-height: 100px;
            margin: 0 auto 10px;
        }
        .content {
            padding: 25px;
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
        .qr-code {
            text-align: center;
            margin: 25px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border-left: 4px solid #FFCA00;
        }
        .footer {
            background-color: #007570;
            color: white;
            text-align: center;
            font-size: 12px;
            padding: 15px 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="cid:logo_image" alt="Logo" class="header-logo">
            <h2>Your Visit has been Confirmed!</h2>
        </div>

        <div class="content">
            <p>Dear {{ $visit->visitor_name ?? 'Visitor' }},</p>
            <p>Your visit has been confirmed. Here are the visit details:</p>

            <table class="details-table">
                <tr>
                    <td>Visitor Name:</td>
                    <td>{{ $visit->visitor->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Visit Date:</td>
                    <td>{{ \Carbon\Carbon::parse($visit->visit_date)->format('d-m-Y') ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Time:</td>
                    <td>{{ \Carbon\Carbon::parse($visit->visit_date)->format('H:i') ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Purpose:</td>
                    <td>{{ $visit->reason ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Host:</td>
                    <td>{{ $visit->staff->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Unique Code:</td>
                    <td><strong>{{ $visit->unique_code ?? 'N/A' }}</strong></td>
                </tr>
            </table>

            <div class="qr-code">
                <p><strong>Show this QR code at the gate:</strong></p>
                <img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="QR Code" style="width:200px;height:200px;">
            </div>

            <p>Please arrive on time. If you cannot attend, kindly inform your host in advance.</p>
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply.</p>
        </div>
    </div>
</body>
</html>
