<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Visitor;
use App\Models\AccessCard;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StaffDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:staff');
    }

    public function index(Request $request)
    {
        $staff = auth('staff')->user();
        $staffId = $staff->id;

        // Get date range for comparison (last 30 days vs previous 30 days)
        $currentPeriodStart = Carbon::now()->subDays(30);
        $previousPeriodStart = Carbon::now()->subDays(60);
        $previousPeriodEnd = Carbon::now()->subDays(30);

        // --- Dashboard Statistics ---

        // Total Invitations (current staff's invitations in last 30 days)
        $totalInvitations = Visit::where('staff_id', $staffId)
            ->where('created_at', '>=', $currentPeriodStart)
            ->count();

        $previousTotalInvitations = Visit::where('staff_id', $staffId)
            ->whereBetween('created_at', [$previousPeriodStart, $previousPeriodEnd])
            ->count();

        $percentageTotalInvitations = $this->calculatePercentageChange($totalInvitations, $previousTotalInvitations);

        // Pending Approval
        $pendingApproval = Visit::where('staff_id', $staffId)
            ->where('status', 'pending')
            ->count();

        $previousPendingApproval = Visit::where('staff_id', $staffId)
            ->where('status', 'pending')
            ->whereBetween('created_at', [$previousPeriodStart, $previousPeriodEnd])
            ->count();

        $percentagePendingApproval = $this->calculatePercentageChange($pendingApproval, $previousPendingApproval);

        // Approved Today
        $approvedToday = Visit::where('staff_id', $staffId)
            ->where('status', 'approved')
            ->whereDate('created_at', Carbon::today())
            ->count();

        $previousApprovedToday = Visit::where('staff_id', $staffId)
            ->where('status', 'approved')
            ->whereDate('created_at', Carbon::yesterday())
            ->count();

        $percentageApproved = $this->calculatePercentageChange($approvedToday, $previousApprovedToday);

        // Cancelled/Denied
        $denied = Visit::where('staff_id', $staffId)
            ->where('status', 'rejected')
            ->where('created_at', '>=', $currentPeriodStart)
            ->count();

        $previousDenied = Visit::where('staff_id', $staffId)
            ->where('status', 'rejected')
            ->whereBetween('created_at', [$previousPeriodStart, $previousPeriodEnd])
            ->count();

        $percentageDenied = $this->calculatePercentageChange($denied, $previousDenied);

        // Staff details
        $fullName = $staff->name ?? '';
        $staffEmail = $staff->email ?? '';
        $staffId = $staff->id ?? '';

        // --- Visit Lists ---

        // Active Visits (with pagination)
        $activeVisits = Visit::with(['visitor', 'staff'])
            ->where('staff_id', $staffId)
            ->whereIn('status', ['pending', 'approved'])
            ->where('visit_date', '>=', Carbon::today())
            ->orderBy('visit_date', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'active_page');

        // Visit History (with pagination)
        $visitHistory = Visit::with(['visitor', 'staff'])
            ->where('staff_id', $staffId)
            ->where(function($query) {
                $query->where('visit_date', '<', Carbon::today())
                      ->orWhere('status', 'rejected')
                      ->orWhere('is_checked_out', true);
            })
            ->orderBy('visit_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'history_page');

        // Floor options for the form
        $floorOptions = [
            'ground' => 'Ground Floor - Reception',
            '1st' => '1st Floor - HR & Admin',
            '2nd' => '2nd Floor - Finance',
            '3rd' => '3rd Floor - IT Department',
            '4th' => '4th Floor - Management',
            '5th' => '5th Floor - Conference Rooms',
        ];

        return view('staff.dashboard', compact(
            'totalInvitations',
            'percentageTotalInvitations',
            'pendingApproval',
            'percentagePendingApproval',
            'approvedToday',
            'percentageApproved',
            'denied',
            'percentageDenied',
            'fullName',
            'staffEmail',
            'staffId',
            'activeVisits',
            'visitHistory',
            'floorOptions'
        ));
    }

    /**
     * Send invitation to guest
     */
    public function sendInvitation(Request $request)
    {
        $request->validate([
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
            'organization' => 'nullable|string|max:255',
            'visit_reason' => 'required|string|max:500',
            'visit_date' => 'required|date|after_or_equal:today',
            'visit_time' => 'required',
            'floor' => 'required|string',
        ]);

        try {
            // Create or find visitor
            $visitor = Visitor::firstOrCreate(
                ['email' => $request->guest_email],
                [
                    'name' => $request->guest_name,
                    'phone' => $request->guest_phone,
                    'organization' => $request->organization,
                ]
            );

            // Create visit
            $visit = Visit::create([
                'visitor_id' => $visitor->id,
                'staff_id' => auth('staff')->id(),
                'visit_date' => $request->visit_date,
                'reason' => $request->visit_reason,
                'status' => 'pending',
                'unique_code' => 'VMS-' . date('Y') . '-' . str_pad(Visit::count() + 1, 3, '0', STR_PAD_LEFT),
                'floor_of_visit' => $request->floor,
            ]);

            // Here you would typically send an email to the visitor
            // Mail::to($visitor->email)->send(new VisitorInvitation($visit));

            return response()->json([
                'success' => true,
                'message' => 'Invitation sent successfully! Your guest will receive an email with the invitation code.',
                'visit' => $visit->load('visitor')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send invitation. Please try again.'
            ], 500);
        }
    }

    /**
     * Cancel visit invitation
     */
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

    /**
     * Edit visit invitation
     */
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

    /**
     * Resubmit denied visit
     */
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

    /**
     * Resend invitation code
     */
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
            // Here you would typically resend the email
            // Mail::to($visit->visitor->email)->send(new VisitorInvitation($visit));

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

    /**
     * Get visit details for editing
     */
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

    /**
     * Calculate percentage change between two values
     */
    private function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? '+100%' : '0%';
        }

        $change = (($current - $previous) / $previous) * 100;
        $sign = $change >= 0 ? '+' : '';

        return $sign . number_format($change, 0) . '%';
    }
}
