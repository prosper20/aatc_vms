{{-- <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Visitor History Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #07AF8B;
            padding-bottom: 10px;
        }

        .header h1 {
            color: #07AF8B;
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .summary {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .summary h3 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 8px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
        }

        th {
            background-color: #07AF8B;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status.pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status.approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status.rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        .verification {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }

        .verification.passed {
            background-color: #d4edda;
            color: #155724;
        }

        .verification.not-verified {
            background-color: #f8d7da;
            color: #721c24;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }

        .page-break {
            page-break-after: always;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Visitor History Report</h1>
        <p>Generated on {{ $exportDate }}</p>
        <p>Total Records: {{ $totalVisits }}</p>
    </div>

    @if($visits->count() > 0)
        <div class="summary">
            <h3>Summary Statistics</h3>
            <p>
                <strong>Total Visits:</strong> {{ $visits->count() }} |
                <strong>Checked In:</strong> {{ $visits->where('is_checked_in', true)->where('is_checked_out', false)->count() }} |
                <strong>Checked Out:</strong> {{ $visits->where('is_checked_out', true)->count() }} |
                <strong>Pending:</strong> {{ $visits->where('status', 'pending')->count() }}
            </p>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Name</th>
                    <th style="width: 10%;">Company</th>
                    <th style="width: 12%;">Visit Purpose</th>
                    <th style="width: 10%;">In</th>
                    <th style="width: 10%;">Out</th>
                    <th style="width: 10%;">Host Name</th>
                    <th style="width: 8%;">Floor/Venue</th>
                    <th style="width: 8%;">Phone</th>
                    <th style="width: 10%;">Email</th>
                    <th style="width: 8%;">Visit Date</th>
                    <th style="width: 6%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($visits as $index => $visit)
                    <tr>
                        <td>{{ $visit->visitor->name ?? '' }}</td>
                        <td>{{ $visit->visitor->organization ?? '' }}</td>
                        <td>{{ $visit->reason ?? '' }}</td>
                        <td>
                            @if($visit->checked_in_at)
                                {{ \Carbon\Carbon::parse($visit->checked_in_at)->format('M d, H:i') }}
                            @endif
                        </td>
                        <td>
                            @if($visit->checked_out_at)
                                {{ \Carbon\Carbon::parse($visit->checked_out_at)->format('M d, H:i') }}
                            @endif
                        </td>
                        <td>{{ $visit->staff->name ?? '' }}</td>
                        <td>{{ $visit->floor_of_visit ?? '' }}</td>
                        <td>{{ $visit->visitor->phone ?? '' }}</td>
                        <td>{{ $visit->visitor->email ?? '' }}</td>
                        <td>{{ $visit->visit_date }}</td>
                        <td>
                            <span class="status {{ $visit->status }}">
                                {{ ucfirst($visit->status) }}
                            </span>
                        </td>
                    </tr>

                    @if(($index + 1) % 30 == 0 && !$loop->last)
                        </tbody>
                        </table>
                        <div class="page-break"></div>
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 12%;">Name</th>
                                    <th style="width: 10%;">Company</th>
                                    <th style="width: 12%;">Visit Purpose</th>
                                    <th style="width: 10%;">In</th>
                                    <th style="width: 10%;">Out</th>
                                    <th style="width: 10%;">Host Name</th>
                                    <th style="width: 8%;">Floor/Venue</th>
                                    <th style="width: 8%;">Phone</th>
                                    <th style="width: 10%;">Email</th>
                                    <th style="width: 8%;">Visit Date</th>
                                    <th style="width: 6%;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <h3>No visitor data found for the selected criteria.</h3>
            <p>Please adjust your filters and try again.</p>
        </div>
    @endif

    <div class="footer">
        <p>Visitor Management System - Generated {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html> --}}

