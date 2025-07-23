<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Visitor;
use App\Models\AccessCard;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ReceptionGuestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:receptionist');
    }

    public function index(Request $request)
{
    $receptionist = auth('receptionist')->user();
        $fullName = $receptionist->name ?? '';
        $firstName = explode(' ', trim($fullName))[0];
        $username = $receptionist->username ?? '';
        $receptionistId = $receptionist->id ?? '';

    // Get date range for comparison (last 30 days vs previous 30 days)
    $currentPeriodStart = Carbon::now()->subDays(30);
    $previousPeriodStart = Carbon::now()->subDays(60);
    $previousPeriodEnd = Carbon::now()->subDays(30);


    // Visit History (with pagination)
    $visitHistory = Visit::with(['visitor', 'staff'])
        ->where(function($query) {
            $query->where('visit_date', '<', Carbon::today());
        })
        ->orderBy('visit_date', 'desc')
        ->orderBy('created_at', 'desc')
        ->paginate(10, ['*'], 'history_page');

    // Floor options for the form
    $floorOptions = [
        'ground' => 'Ground Floor',
        'mezzanine' => 'Mezzanine',
        '1st' => 'Floor 1',
        '2nd' => 'Floor 2',
        '3rd' => 'Floor 3',
        '4th' => 'Floor 4',
        '5th' => 'Floor 5',
        '6th' => 'Floor 6',
        '7th' => 'Floor 7',
        '8th' => 'Floor 8',
        '9th' => 'Floor 9',
    ];


    return view('reception.guests', compact(
        'fullName',
        'username',
        'receptionistId',
        'visitHistory',
        'floorOptions',
        'firstName',
        'username'
    ));
}

public function guests(Request $request)
{
    // Only process search/pagination if on history tab
    if ($request->get('tab') === 'history') {
        $query = Visit::with(['visitor', 'staff', 'accessCard'])
            ->where('is_checked_out', true)
            ->orderBy('checked_out_at', 'desc');

        if ($request->get('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('visitor', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('staff', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        $historyVisits = $query->paginate(10)->appends($request->query());
    } else {
        $historyVisits = collect(); // Empty collection for other tabs
    }

    return view('vmc.dashboard', [
        'historyVisits' => $historyVisits,
        'firstName' => auth('receptionist')->user()->first_name
    ]);
}

    public function sendInvitation(Request $request)
{
    // Verify reception is authenticated
    if (!auth('receptionist')->check()) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized - receptionist not authenticated',
        ], 401);
    }

    // Get the authenticated reception member
    $receptionist = auth('receptionist')->user();
    $receptionistId = $receptionist->id;
    $staffId = ""; // to be updpated

    $request->validate([
        'guests' => 'required|array',
        'guests.*.name' => 'required|string|max:255',
        'guests.*.email' => 'required|email|max:255',
        'guests.*.phone' => 'required|string|max:20',
        'guests.*.organization' => 'nullable|string|max:255',
        'guests.*.reason' => 'required|string|max:500',
        'guests.*.date' => 'required|date|after_or_equal:today',
        'guests.*.time' => 'required',
        'guests.*.floor' => 'required|string|max:50',
    ]);

    DB::beginTransaction();

    try {
        $createdVisits = [];

        foreach ($request->guests as $guestData) {
            $visitDateTime = Carbon::createFromFormat(
                'Y-m-d H:i',
                $guestData['date'] . ' ' . $guestData['time']
            );

            // Find or create visitor
            $visitor = Visitor::firstOrCreate(
                ['email' => $guestData['email']],
                [
                    'name' => $guestData['name'],
                    'phone' => $guestData['phone'],
                    'organization' => $guestData['organization'] ?? null,
                ]
            );

            // Generate unique code (similar to storeVisitors but more readable)
            $uniqueCode = strtoupper(
                dechex(time() % 0xFFFF) . '-' .
                dechex(rand(0, 0xFFFF))
            );

            // Create visit record
            $visit = Visit::create([
                'visitor_id' => $visitor->id,
                'staff_id' => $staffId,
                'visit_date' => $visitDateTime,
                'reason' => $guestData['reason'],
                'status' => 'pending',
                'unique_code' => $uniqueCode,
                'floor_of_visit' => $guestData['floor'],
                'checked_in_at' => null,
                'checked_out_at' => null,
                'checkin_by' => null,
                'checkout_by' => null,
                'is_checked_in' => false,
                'is_checked_out' => false,
                'verification_message' => 'Visitor has not arrived at the gate',
            ]);

            $createdVisits[] = $visit;

        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Invitations sent successfully!',
            'data' => [
                'count' => count($createdVisits),
                'visits' => $createdVisits,
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Invitation failed: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Failed to send invitations. Please try again.',
            'error' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}

    public function cancelVisit(Visit $visit)
    {
        // Check if the visit belongs to the current staff
        if ($visit->staff_id !== auth('staff')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        // Only allow cancellation of pending visits
        if ($visit->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending visits can be cancelled'
            ], 400);
        }

        $visit->update(['status' => 'rejected']);

        return response()->json([
            'success' => true,
            'message' => 'Visit invitation cancelled successfully'
        ]);
    }

    public function editVisit(Request $request, Visit $visit)
    {
        // Check if the visit belongs to the current staff
        if ($visit->staff_id !== auth('staff')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        // Only allow editing of pending visits
        if ($visit->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending visits can be edited'
            ], 400);
        }

        $request->validate([
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
            'organization' => 'nullable|string|max:255',
            'visit_reason' => 'required|string|max:500',
            'visit_date' => 'required|date|after_or_equal:today',
            'floor' => 'required|string',
        ]);

        try {
            // Update visitor information
            $visit->visitor->update([
                'name' => $request->guest_name,
                'email' => $request->guest_email,
                'phone' => $request->guest_phone,
                'organization' => $request->organization,
            ]);

            // Update visit information
            $visit->update([
                'visit_date' => $request->visit_date,
                'reason' => $request->visit_reason,
                'floor_of_visit' => $request->floor,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Visit invitation updated successfully',
                'visit' => $visit->load('visitor')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update invitation. Please try again.'
            ], 500);
        }
    }

    public function resubmitVisit(Visit $visit)
    {
        // Check if the visit belongs to the current staff
        if ($visit->staff_id !== auth('staff')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        // Only allow resubmission of rejected visits
        if ($visit->status !== 'rejected') {
            return response()->json([
                'success' => false,
                'message' => 'Only rejected visits can be resubmitted'
            ], 400);
        }

        $visit->update([
            'status' => 'pending',
            'verification_message' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Visit invitation resubmitted successfully'
        ]);
    }

    public function resendCode(Visit $visit)
    {
        // Check if the visit belongs to the current staff
        if ($visit->staff_id !== auth('staff')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        // Only allow resending code for approved visits
        if ($visit->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Only approved visits can have codes resent'
            ], 400);
        }

        try {
            return response()->json([
                'success' => true,
                'message' => 'Invitation code resent successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend code. Please try again.'
            ], 500);
        }
    }

    public function getVisitDetails(Visit $visit)
    {
        // Check if the visit belongs to the current staff
        if ($visit->staff_id !== auth('staff')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'visit' => $visit->load('visitor')
        ]);
    }

    private function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? '+100%' : '0%';
        }

        $change = (($current - $previous) / $previous) * 100;
        $sign = $change >= 0 ? '+' : '';

        return $sign . number_format($change, 0) . '%';
    }

    public function show(Visit $visit)
    {
        $visit->load(['visitor', 'staff', 'accessCard']);

        return view('reception.visit-details', [
            'visit' => $visit,
            'firstName' => auth('receptionist')->user()->first_name,
            'pendingVisits' => Visit::with(['visitor', 'staff'])
                ->where('status', 'pending')
                ->get()
        ]);
    }
}
