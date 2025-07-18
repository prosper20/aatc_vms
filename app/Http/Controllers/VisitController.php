<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\VisitApprovedEmail;
use Illuminate\Support\Facades\Log;

use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\Writer\PngWriter;



class VisitController extends Controller
{
    public function approve(Visit $visit): JsonResponse
    {
        // $visit->update(['status' => 'approved']);
        // return redirect()->route('sm.dashboard')->with('success', 'Visit approved successfully');

        try {

            // Dispatch email sending as a background job (non-blocking)
            // Using Laravel's queue system for better performance
            // dispatch(function() use ($visit) {
            //     Http::post(route('send.email'), [
            //         'visit_id' => $visit->id,
            //         'type' => 'approved',
            //     ]);
            // })->onQueue('emails');

             // Generate QR code using Endroid QRCode (pure PHP)
            $qrCode = new EndroidQrCode($visit->unique_code);
            $qrCode->setSize(200);
            $qrCode->setMargin(10);

            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            $qrCodeBase64 = base64_encode($result->getString());

            // Send email
            Mail::to($visit->visitor->email)->send(new VisitApprovedEmail($visit, $qrCodeBase64));


            // Generate QR code
            // $qrCodeSvg = QrCode::size(200)->generate($visit->unique_code);

            // $imageData = Browsershot::html($qrCodeSvg)
            // ->windowSize(320, 320)
            // ->setScreenshotType('png')
            // ->waitUntilNetworkIdle()
            // ->screenshot();

            // $qrCodeBase64 = base64_encode($imageData);

            // // Send email
            // Mail::to($visit->visitor->email)->send(new VisitApprovedEmail($visit, $qrCodeBase64));

            // Update the visit status
            $visit->update(['status' => 'approved']);

            return response()->json([
                'success' => true,
                'message' => 'Visit approved successfully!',
                'visit_id' => $visit->id
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error approving visit: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to approve visit. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }

        // try {
        //     // 1. Update visit status
        //     $visit->update(['status' => 'approved']);

        //     // 2. Generate QR Code
        //     // $qrCodePath = public_path('qrcodes/' . $visit->unique_code . '.png');
        //     // QrCode::format('png')->size(300)->generate($visit->unique_code, $qrCodePath);

        //     // 3. Fetch required values from related models
        //     $visitor_name = $visit->visitor->name;
        //     $visitor_email = $visit->visitor->email;

        //     $host_name = $visit->staff ? $visit->staff->name : 'Unknown Host';

        //     $visit_date = \Carbon\Carbon::parse($visit->visit_date)->format('d-m-Y');
        //     $visit_time = \Carbon\Carbon::parse($visit->visit_date)->format('H:i');

        //     $visit_location = $visit->floor_of_visit ?? 'Unknown';
        //     $visit_purpose = $visit->reason ?? 'Not specified';
        //     $unique_code = $visit->unique_code;

        //     $qr_code_image_url = asset('qrcodes/' . $visit->unique_code . '.png');

        //     // 4. Send email
        //     Mail::send('visit-approved', [
        //         'visitor_name' => $visitor_name,
        //         'host_name' => $host_name,
        //         'visit_date' => $visit_date,
        //         'visit_time' => $visit_time,
        //         'visit_location' => $visit_location,
        //         'visit_purpose' => $visit_purpose,
        //         'unique_code' => $unique_code,
        //         'qr_code_image' => $qr_code_image_url,
        //     ], function ($message) use ($visitor_email) {
        //         $message->to($visitor_email)
        //                 ->subject('Appointment Approved - Visitor Management System');
        //     });

        //     return redirect()->route('sm.dashboard')->with('success', 'Visit approved successfully');

        // } catch (\Throwable $e) {
        //     \Log::error('Visit approval failed: ' . $e->getMessage());
        //     return response()->json(['error' => 'Internal server error.'], 500);
        // }
    }

public function deny(Visit $visit): RedirectResponse
{
    $visit->update(['status' => 'rejected']);
    return redirect()->route('sm.dashboard')->with('success', 'Visit denied successfully');
}

public function pending(Request $request)
{
    $visitors = Visit::with(['visitor', 'staff'])
        ->where('status', 'pending')
        ->latest()
        ->get();

    $pendingCount = $visitors->count();

    if ($request->ajax()) {
        return response()->json([
            'html' => View::make('cso.partials.visitor-list', compact('visitors'))->render(),
            'pendingCount' => $pendingCount,
        ]);
    }

    return back();
}

}
