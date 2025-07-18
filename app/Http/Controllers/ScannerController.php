<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Visitor;
use App\Models\Staff;
use App\Models\Operative;
use Carbon\Carbon;

class ScannerController extends Controller
{
    public function index()
    {
        // $operatives = Operative::select('id', 'name')->get();
        $operatives = Operative::select('id', 'name')->get()->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->name
            ];
        });
        return view('gate.scanner', compact('operatives'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string'
        ]);

        $qrData = $request->input('qr_data');
        $visit = Visit::with(['visitor', 'staff'])
            ->where('unique_code', $qrData)
            ->first();

        if (!$visit) {
            return response()->json([
                'status' => 'NOT_FOUND',
                'message' => 'Invalid Code',
                'data' => null
            ]);
        }

        // Check if visit is for today and approved
        $today = Carbon::today();
        $visitDate = Carbon::parse($visit->visit_date);
        $isToday = $visitDate->isSameDay($today);
        $isApproved = $visit->status === 'approved';

        $verificationPassed = $isToday && $isApproved;
        $verificationMessage = $verificationPassed
            ? 'Visitor verified successfully!'
            : 'Verification failed: No appointment for today or not approved yet';

        return response()->json([
            'status' => 'FOUND',
            'verification_passed' => $verificationPassed,
            'verification_message' => $verificationMessage,
            'data' => [
                'visitor_id' => $visit->visitor->id,
                'visit_id' => $visit->id,
                'qr_data' => $visit->unique_code,
                'visitor_name' => $visit->visitor->name,
                'company' => $visit->visitor->organization ?? 'N/A',
                'host_name' => $visit->staff->name,
                'host_id' => $visit->staff->id,
                'purpose' => $visit->reason,
                'scheduled_date' => $visitDate->format('M j, Y'),
                'status' => $visit->status
            ]
        ]);
    }

    public function checkin(Request $request)
    {
        $request->validate([
            'visit_id' => 'required|exists:visits,id',
            'mode_of_arrival' => 'required|in:vehicle,foot',
            'plate_number' => 'nullable|string|max:20',
            'vehicle_type' => 'nullable|in:drop-off,wait',
            'verified_by' => 'required|exists:operatives,id'
        ]);

        $visit = Visit::find($request->visit_id);

        if (!$visit) {
            return response()->json([
                'success' => false,
                'message' => 'Visit not found'
            ]);
        }

        // Update visit with gate information
        $visit->update([
            'arrived_at_gate' => now(),
            'verification_passed' => true,
            'verification_message' => 'Security Verification Successful',
            'verified_by' => $request->verified_by,
            'mode_of_arrival' => $request->mode_of_arrival,
            'plate_number' => $request->mode_of_arrival === 'vehicle' ? $request->plate_number : null,
            'vehicle_type' => $request->mode_of_arrival === 'vehicle' ? $request->vehicle_type : null,
            'checked_in_at' => now(),
            'checkin_by' => $request->verified_by,
            'is_checked_in' => true,
        ]);

        $operative = Operative::find($request->verified_by);

        return response()->json([
            'success' => true,
            'message' => "Security Verification Completed",
            'data' => $visit->fresh(['visitor', 'staff'])
        ]);
    }

    public function notify(Request $request)
    {
        $request->validate([
            'host_id' => 'required|exists:staff,id'
        ]);

        $staff = Staff::find($request->host_id);

        if (!$staff) {
            return response()->json([
                'success' => false,
                'message' => 'Host not found'
            ]);
        }

        // Here you can add email notification logic
        // Mail::to($staff->email)->send(new VisitorArrivalNotification($visitor));

        return response()->json([
            'success' => true,
            'message' => "{$staff->name} has been notified of the visitor's arrival",
            'data' => [
                'employee' => $staff->name,
                'email' => $staff->email,
            ]
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        return $this->verify($request->merge(['qr_data' => $request->code]));
    }
}
// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Visit;
// use App\Models\Visitor;
// use App\Models\Staff;

// class ScannerController extends Controller
// {
//     public function index()
//     {
//         return view('scanner');
//     }

//     public function verify(Request $request)
//     {
//         $qrData = $request->input('qr_data');

//         $visit = Visit::with('visitor', 'staff')->where('unique_code', $qrData)->first();

//         if (!$visit) {
//             return response()->json(['status' => 'NOT_FOUND', 'message' => 'Visitor not found']);
//         }

//         return response()->json([
//             'status' => 'FOUND',
//             'visitor_id' => $visit->visitor->id,
//             'qr_data' => $visit->unique_code,
//             'visitor_name' => $visit->visitor->name,
//             'company' => $visit->visitor->organization,
//             'host_name' => $visit->staff->name,
//             'host_id' => $visit->staff->id,
//             'purpose' => $visit->reason,
//         ]);
//     }

//     public function checkin(Request $request)
//     {
//         $visit = Visit::find($request->visitor_id);
//         if (!$visit) {
//             return response()->json(['success' => false, 'message' => 'Visit not found']);
//         }

//         $visit->status = 'Checked In';
//         $visit->save();

//         return response()->json(['success' => true, 'message' => 'Visitor checked in successfully']);
//     }

//     public function notify(Request $request)
//     {
//         // This can be expanded to include real-time email or notification
//         $staff = Staff::find($request->host_id);

//         if (!$staff) {
//             return response()->json(['success' => false, 'message' => 'Host not found']);
//         }

//         return response()->json([
//             'success' => true,
//             'employee' => $staff->name,
//             'email' => $staff->email,
//         ]);
//     }

//     public function searchByCode(Request $request)
//     {
//         $code = $request->input('code');

//         $visit = Visit::with('visitor', 'staff')->where('unique_code', $code)->first();

//         if (!$visit) {
//             return response()->json(['status' => 'NOT_FOUND', 'message' => 'Code not found.']);
//         }

//         return response()->json([
//             'status' => 'FOUND',
//             'visitor_id' => $visit->visitor->id,
//             'qr_data' => $visit->unique_code,
//             'visitor_name' => $visit->visitor->name,
//             'company' => $visit->visitor->organization,
//             'host_name' => $visit->staff->name,
//             'host_id' => $visit->staff->id,
//             'purpose' => $visit->reason,
//         ]);
//     }
// }
