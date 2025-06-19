<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Mail;
use App\Mail\VisitApprovedEmail;


class VisitController extends Controller
{
    public function approve(Visit $visit): RedirectResponse
    {
        $visit->update(['status' => 'approved']);
        return redirect()->route('sm.dashboard')->with('success', 'Visit approved successfully');

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
    $visit->update(['status' => 'denied']);
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
