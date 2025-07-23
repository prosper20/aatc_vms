<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
// use Illuminate\Support\Facades\Response;
// use Barryvdh\DomPDF\Facade\Pdf;
// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// use PhpOffice\PhpSpreadsheet\Style\Alignment;
// use PhpOffice\PhpSpreadsheet\Style\Border;
// use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReceptionHistoryController extends Controller
{
    public function export(Request $request)
    {
        // Validate the request
        $request->validate([
            'export_format' => 'required|in:csv,excel,pdf',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'include_checked_in' => 'boolean',
            'include_checked_out' => 'boolean',
            'include_pending' => 'boolean',
        ]);

        // Build the query with relationships
        $query = Visit::with(['visitor', 'staff'])
            ->orderBy('created_at', 'desc');

        // Apply date and time filters
        $this->applyDateTimeFilters($query, $request);

        // Apply status filters
        $this->applyStatusFilters($query, $request);

        $visits = $query->orderBy('visit_date', 'desc')->get();

        $format = $request->export_format;

        $exportData = $visits->map(function ($visit) {
            return [
                'Name'         => $visit->visitor->name ?? '',
                'Company'      => $visit->visitor->organization ?? '',
                'Visit Purpose'=> $visit->reason ?? '',
                'In'           => $visit->checked_in_at ? Carbon::parse($visit->checked_in_at)->format('Y-m-d H:i') : '',
                'Out'          => $visit->checked_out_at ? Carbon::parse($visit->checked_out_at)->format('Y-m-d H:i') : '',
                'Host Name'    => $visit->staff->name ?? '',
                'Floor/Venue'  => $visit->floor_of_visit ?? '',
                'Phone'        => $visit->visitor->phone ?? '',
                'Email'        => $visit->visitor->email ?? '',
                'Visit Date'   => $visit->visit_date ? Carbon::parse($visit->visit_date)->format('Y-m-d') : '',
                // 'Status'       => ucfirst($visit->status ?? ''),
            ];
        });

        if ($format === 'pdf') {
            try {
                $pdf = Pdf::loadView('exports.visitor_history_pdf', ['data' => $exportData]);
                return response($pdf->output(), 200)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="visitor_history.pdf"');

            } catch (\Throwable $e) {
                \Log::error('Export error: ' . $e->getMessage());
                return response()->json([
                    'error' => 'Export failed. Please try again or contact support.',
                    'details' => $e->getMessage(), // Temporary for debugging
                ], 500);
            }
        }

        return response()->json([
            'success' => true,
            'format' => $format,
            'data' => $exportData,
            'message' => 'Export data ready',
        ]);
    }

    private function applyDateTimeFilters($query, Request $request)
    {
        if ($request->filled('start_date')) {
            $startDateTime = $request->start_date;

            if ($request->filled('start_time')) {
                $startDateTime .= ' ' . $request->start_time;
            } else {
                $startDateTime .= ' 00:00:00';
            }

            $query->where('created_at', '>=', Carbon::parse($startDateTime));
        }

        if ($request->filled('end_date')) {
            $endDateTime = $request->end_date;

            if ($request->filled('end_time')) {
                $endDateTime .= ' ' . $request->end_time;
            } else {
                $endDateTime .= ' 23:59:59';
            }

            $query->where('created_at', '<=', Carbon::parse($endDateTime));
        }
    }

    private function applyStatusFilters($query, Request $request)
    {
        $statusConditions = [];

        if ($request->boolean('include_checked_in')) {
            $statusConditions[] = function ($q) {
                $q->where('is_checked_in', true)->where('is_checked_out', false);
            };
        }

        if ($request->boolean('include_checked_out')) {
            $statusConditions[] = function ($q) {
                $q->where('is_checked_out', true);
            };
        }

        if ($request->boolean('include_pending')) {
            $statusConditions[] = function ($q) {
                $q->where('status', 'approved')
                  ->where('is_checked_in', false);
            };
        }

        // if ($request->boolean('include_pending')) {
        //     $statusConditions[] = function ($q) {
        //         $q->where('status', 'pending')
        //           ->orWhere(function ($subQ) {
        //               $subQ->where('status', 'approved')
        //                    ->where('is_checked_in', false);
        //           });
        //     };
        // }

        if (!empty($statusConditions)) {
            $query->where(function ($q) use ($statusConditions) {
                foreach ($statusConditions as $condition) {
                    $q->orWhere($condition);
                }
            });
        }
    }
}



    // private function exportToCsv($visits, $filename)
    // {
    //     $headers = [
    //         'Content-Type' => 'text/csv',
    //         'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
    //     ];

    //     $callback = function() use ($visits) {
    //         $file = fopen('php://output', 'w');

    //         // Add CSV headers
    //         fputcsv($file, [
    //             'Name',
    //             'Company',
    //             'Visit Purpose',
    //             'In',
    //             'Out',
    //             'Host Name',
    //             'Floor/Venue',
    //             'Phone',
    //             'Email',
    //             'Visit Date',
    //             'Status'
    //         ]);

    //         // Add data rows
    //         foreach ($visits as $visit) {
    //             fputcsv($file, [
    //                 $visit->visitor->name ?? '',
    //                 $visit->visitor->organization ?? '',
    //                 $visit->reason ?? '',
    //                 $visit->checked_in_at ? Carbon::parse($visit->checked_in_at)->format('Y-m-d H:i:s') : '',
    //                 $visit->checked_out_at ? Carbon::parse($visit->checked_out_at)->format('Y-m-d H:i:s') : '',
    //                 $visit->staff->name ?? '',
    //                 $visit->floor_of_visit ?? '',
    //                 $visit->visitor->phone ?? '',
    //                 $visit->visitor->email ?? '',
    //                 $visit->visit_date,
    //                 ucfirst($visit->status)
    //             ]);
    //         }

    //         fclose($file);
    //     };

    //     return Response::stream($callback, 200, $headers);
    // }

    // private function exportToExcel($visits, $filename)
    // {
    //     $spreadsheet = new Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     // Set title
    //     $sheet->setTitle('Visitor History');

    //     // Headers
    //     $headers = [
    //         'A1' => 'Name', 'B1' => 'Company', 'C1' => 'Visit Purpose', 'D1' => 'In',
    //         'E1' => 'Out', 'F1' => 'Host Name', 'G1' => 'Floor/Venue', 'H1' => 'Phone',
    //         'I1' => 'Email', 'J1' => 'Visit Date', 'K1' => 'Status'
    //     ];

    //     // Set headers
    //     foreach ($headers as $cell => $value) {
    //         $sheet->setCellValue($cell, $value);
    //     }

    //     // Style headers
    //     $headerRange = 'A1:K1';
    //     $sheet->getStyle($headerRange)->applyFromArray([
    //         'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    //         'fill' => [
    //             'fillType' => Fill::FILL_SOLID,
    //             'startColor' => ['rgb' => '07AF8B']
    //         ],
    //         'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    //         'borders' => [
    //             'allBorders' => ['borderStyle' => Border::BORDER_THIN]
    //         ]
    //     ]);

    //     // Add data
    //     $row = 2;
    //     foreach ($visits as $visit) {
    //         $sheet->setCellValue("A{$row}", $visit->visitor->name ?? '');
    //         $sheet->setCellValue("B{$row}", $visit->visitor->organization ?? '');
    //         $sheet->setCellValue("C{$row}", $visit->reason ?? '');
    //         $sheet->setCellValue("D{$row}", $visit->checked_in_at ? Carbon::parse($visit->checked_in_at)->format('Y-m-d H:i:s') : '');
    //         $sheet->setCellValue("E{$row}", $visit->checked_out_at ? Carbon::parse($visit->checked_out_at)->format('Y-m-d H:i:s') : '');
    //         $sheet->setCellValue("F{$row}", $visit->staff->name ?? '');
    //         $sheet->setCellValue("G{$row}", $visit->floor_of_visit ?? '');
    //         $sheet->setCellValue("H{$row}", $visit->visitor->phone ?? '');
    //         $sheet->setCellValue("I{$row}", $visit->visitor->email ?? '');
    //         $sheet->setCellValue("J{$row}", $visit->visit_date);
    //         $sheet->setCellValue("K{$row}", ucfirst($visit->status));
    //         $row++;
    //     }

    //     // Auto-size columns
    //     foreach (range('A', 'K') as $column) {
    //         $sheet->getColumnDimension($column)->setAutoSize(true);
    //     }

    //     // Add borders to data
    //     $dataRange = 'A1:K' . ($row - 1);
    //     $sheet->getStyle($dataRange)->applyFromArray([
    //         'borders' => [
    //             'allBorders' => ['borderStyle' => Border::BORDER_THIN]
    //         ]
    //     ]);

    //     // Create writer and save
    //     $writer = new Xlsx($spreadsheet);

    //     $tempFile = tempnam(sys_get_temp_dir(), 'visitor_export_');
    //     $writer->save($tempFile);

    //     return response()->download($tempFile, "{$filename}.xlsx")->deleteFileAfterSend(true);
    // }

    // private function exportToPdf($visits, $filename)
    // {
    //     $data = [
    //         'visits' => $visits,
    //         'exportDate' => now()->format('F d, Y H:i:s'),
    //         'totalVisits' => $visits->count()
    //     ];

    //     $pdf = PDF::loadView('exports.visitor-history-pdf', $data);
    //     $pdf->setPaper('a4', 'landscape');

    //     return $pdf->download("{$filename}.pdf");
    // }

    // /**
    //  * Get summary statistics for the dashboard
    //  */
    // public function getSummaryStats(Request $request)
    // {
    //     $query = Visit::query();

    //     // Apply date filters if provided
    //     if ($request->filled('start_date')) {
    //         $query->whereDate('created_at', '>=', $request->start_date);
    //     }

    //     if ($request->filled('end_date')) {
    //         $query->whereDate('created_at', '<=', $request->end_date);
    //     }

    //     $stats = [
    //         'total_visits' => $query->count(),
    //         'checked_in' => $query->where('is_checked_in', true)->where('is_checked_out', false)->count(),
    //         'checked_out' => $query->where('is_checked_out', true)->count(),
    //         'pending_approval' => $query->where('status', 'pending')->count(),
    //         'rejected' => $query->where('status', 'rejected')->count(),
    //         'vehicle_arrivals' => $query->where('mode_of_arrival', 'vehicle')->count(),
    //         'foot_arrivals' => $query->where('mode_of_arrival', 'foot')->count(),
    //     ];

    //     return response()->json($stats);
    // }

