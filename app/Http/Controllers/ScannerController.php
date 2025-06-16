<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Visitor;
use App\Models\Staff;

class ScannerController extends Controller
{
    public function index()
    {
        return view('scanner');
    }

    public function verify(Request $request)
    {
        $qrData = $request->input('qr_data');

        $visit = Visit::with('visitor', 'staff')->where('unique_code', $qrData)->first();

        if (!$visit) {
            return response()->json(['status' => 'NOT_FOUND', 'message' => 'Visitor not found']);
        }

        return response()->json([
            'status' => 'FOUND',
            'visitor_id' => $visit->visitor->id,
            'qr_data' => $visit->unique_code,
            'visitor_name' => $visit->visitor->name,
            'company' => $visit->visitor->organization,
            'host_name' => $visit->staff->name,
            'host_id' => $visit->staff->id,
            'purpose' => $visit->reason,
        ]);
    }

    public function checkin(Request $request)
    {
        $visit = Visit::find($request->visitor_id);
        if (!$visit) {
            return response()->json(['success' => false, 'message' => 'Visit not found']);
        }

        $visit->status = 'Checked In';
        $visit->save();

        return response()->json(['success' => true, 'message' => 'Visitor checked in successfully']);
    }

    public function notify(Request $request)
    {
        // This can be expanded to include real-time email or notification
        $staff = Staff::find($request->host_id);

        if (!$staff) {
            return response()->json(['success' => false, 'message' => 'Host not found']);
        }

        return response()->json([
            'success' => true,
            'employee' => $staff->name,
            'email' => $staff->email,
        ]);
    }

    public function searchByCode(Request $request)
    {
        $code = $request->input('code');

        $visit = Visit::with('visitor', 'staff')->where('unique_code', $code)->first();

        if (!$visit) {
            return response()->json(['status' => 'NOT_FOUND', 'message' => 'Code not found.']);
        }

        return response()->json([
            'status' => 'FOUND',
            'visitor_id' => $visit->visitor->id,
            'qr_data' => $visit->unique_code,
            'visitor_name' => $visit->visitor->name,
            'company' => $visit->visitor->organization,
            'host_name' => $visit->staff->name,
            'host_id' => $visit->staff->id,
            'purpose' => $visit->reason,
        ]);
    }
}
