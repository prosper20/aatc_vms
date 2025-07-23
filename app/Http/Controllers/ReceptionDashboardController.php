<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Visit;
// use App\Models\AccessCard;
// use Carbon\Carbon;

// class ReceptionDashboardController extends Controller
// {
//     public function __construct()
//     {
//         $this->middleware('auth:receptionist');
//     }

//     public function index()
// {
//     $today = Carbon::today();
//     $tomorrow = Carbon::tomorrow();

//     // --- Card Counts ---
//     $expectedTodayCount = Visit::whereDate('visit_date', $today)
//         ->where('status', 'approved')
//         ->count();

//     $checkedInCount = Visit::where('is_checked_in', true)
//         ->whereDate('checked_in_at', $today)
//         ->count();

//     $checkedOutCount = Visit::where('is_checked_out', true)
//         ->whereDate('checked_out_at', $today)
//         ->count();

//     $cardsAvailable = AccessCard::where('is_issued', false)->count();

//     // $cardsIssuedCount = AccessCard::whereNotNull('issued_at')->count();
//     $cardsIssuedCount = AccessCard::where('is_issued', true)->count();
//     $receptionist = auth('receptionist')->user();
//     $fullName = $receptionist->name ?? '';
//     $firstName = explode(' ', trim($fullName))[0];
//     $username = $receptionist->username ?? '';

//     // --- Visit Lists ---

//     // Expected Today
//     $expectedVisits = Visit::with(['visitor', 'staff'])
//         ->whereDate('visit_date', $today)
//         ->where('status', 'approved')
//         ->orderBy('visit_date', 'asc')
//         ->get();

//     // Expected Today
//     $approvedPendingCheckin = Visit::with(['visitor', 'staff'])
//         ->where('status', 'approved')
//         ->where('is_checked_in', false)
//         ->orderBy('created_at', 'desc')
//         // ->orderBy('visit_date', 'asc')
//         ->get();

//     // Checked In Today
//     $checkedInVisits = Visit::with(['visitor', 'staff', 'accessCard'])
//         ->where('is_checked_in', true)
//         ->where('is_checked_out', false)
//         // ->whereDate('checked_in_at', $today)
//         ->orderBy('checked_in_at', 'desc')
//         ->get();

//     // Checked Out Today
//     $checkedOutVisits = Visit::with(['visitor', 'staff', 'accessCard'])
//         ->where('is_checked_out', true)
//         // ->whereDate('checked_out_at', $today)
//         ->orderBy('checked_out_at', 'desc')
//         ->get();

//     return view('reception.dashboard', compact(
//         'expectedTodayCount',
//         'checkedInCount',
//         'approvedPendingCheckin',
//         'checkedOutCount',
//         'cardsAvailable',
//         'cardsIssuedCount',
//         'expectedVisits',
//         'checkedInVisits',
//         'checkedOutVisits',
//         'firstName',
//         'username'
//     ));
// }

//     public function search(Request $request)
//     {
//         $query = $request->get('q');

//         if (empty($query)) {
//             return response()->json([]);
//         }

//         $visits = Visit::with(['visitor', 'staff'])
//             ->whereHas('visitor', function ($q) use ($query) {
//                 $q->where('name', 'like', "%{$query}%")
//                   ->orWhere('phone', 'like', "%{$query}%")
//                   ->orWhere('email', 'like', "%{$query}%");
//             })
//             ->orWhereHas('staff', function ($q) use ($query) {
//                 $q->where('name', 'like', "%{$query}%");
//             })
//             ->where('visit_date', '>=', Carbon::now()->subHours(48))
//             ->limit(10)
//             ->get();

//         return response()->json($visits);
//     }

// }





namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\AccessCard;
use Carbon\Carbon;

class ReceptionDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:receptionist');
    }

    public function index(Request $request)
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // --- Card Counts ---
        $expectedTodayCount = Visit::whereDate('visit_date', $today)
            ->where('status', 'approved')
            ->count();

        $checkedInCount = Visit::where('is_checked_in', true)
            ->whereDate('checked_in_at', $today)
            ->count();

        $checkedOutCount = Visit::where('is_checked_out', true)
            ->whereDate('checked_out_at', $today)
            ->count();

        $cardsAvailable = AccessCard::where('is_issued', false)->count();
        $cardsIssuedCount = AccessCard::where('is_issued', true)->count();

        $receptionist = auth('receptionist')->user();
        $fullName = $receptionist->name ?? '';
        $firstName = explode(' ', trim($fullName))[0];
        $username = $receptionist->username ?? '';

        // Search query
        $search = $request->get('search');

        // Base queries for each tab
        $approvedPendingQuery = Visit::with(['visitor', 'staff'])
            ->where('status', 'approved')
            ->where('is_checked_in', false);

        $checkedInQuery = Visit::with(['visitor', 'staff', 'accessCard'])
            ->where('is_checked_in', true)
            ->where('is_checked_out', false);

        $checkedOutQuery = Visit::with(['visitor', 'staff', 'accessCard'])
            ->where('is_checked_out', true);

        // Apply search if exists
        if ($search) {
            $searchFunction = function($query) use ($search) {
                $query->whereHas('visitor', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('staff', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhere('unique_code', 'like', "%{$search}%");
            };

            $approvedPendingQuery->where($searchFunction);
            $checkedInQuery->where($searchFunction);
            $checkedOutQuery->where($searchFunction);
        }

        // Get paginated results
        $approvedPendingCheckin = $approvedPendingQuery
            ->orderBy('visit_date', 'asc')
            ->paginate(10, ['*'], 'approved_page')
            ->appends(['search' => $search]);

        $checkedInVisits = $checkedInQuery
            ->orderBy('checked_in_at', 'desc')
            ->paginate(10, ['*'], 'checked_in_page')
            ->appends(['search' => $search]);

        $checkedOutVisits = $checkedOutQuery
            ->orderBy('checked_out_at', 'desc')
            ->paginate(10, ['*'], 'checked_out_page')
            ->appends(['search' => $search]);

        return view('reception.dashboard', compact(
            'expectedTodayCount',
            'checkedInCount',
            'approvedPendingCheckin',
            'checkedOutCount',
            'cardsAvailable',
            'cardsIssuedCount',
            // 'expectedVisits',
            'checkedInVisits',
            'checkedOutVisits',
            'firstName',
            'username',
            'search'
        ));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return response()->json([]);
        }

        $visits = Visit::with(['visitor', 'staff'])
            ->whereHas('visitor', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->orWhereHas('staff', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->where('visit_date', '>=', Carbon::now()->subHours(48))
            ->limit(10)
            ->get();

        return response()->json($visits);
    }
}




// public function index()
    // {
    //     $today = Carbon::today();
    //     $last48Hours = Carbon::now()->subHours(48);

    //     // Get statistics for dashboard cards
    //     $gateClearedCount = Visit::where('verification_passed', true)
    //         ->where('arrived_at_gate', '>=', $last48Hours)
    //         ->where('is_checked_in', false)
    //         ->count();

    //     $checkedInCount = Visit::where('is_checked_in', true)
    //         ->where('checked_in_at', '>=', $last48Hours)
    //         ->count();

    //     $approvedTodayCount = Visit::whereDate('visit_date', $today)
    //         ->where('status', 'approved')
    //         ->count();

    //     $cardsAvailable = AccessCard::where('is_issued', false)->count();

    //     // Get visits data for the table
    //     $gateClearedVisits = Visit::with(['visitor', 'staff'])
    //         ->where('verification_passed', true)
    //         ->where('arrived_at_gate', '>=', $last48Hours)
    //         ->where('is_checked_in', false)
    //         ->orderBy('arrived_at_gate', 'desc')
    //         ->get();

    //     $checkedInVisits = Visit::with(['visitor', 'staff', 'accessCard'])
    //         ->where('is_checked_in', true)
    //         ->where('checked_in_at', '>=', $last48Hours)
    //         ->orderBy('checked_in_at', 'desc')
    //         ->get();

    //     $approvedVisits = Visit::with(['visitor', 'staff'])
    //         ->whereDate('visit_date', $today)
    //         ->where('status', 'approved')
    //         ->where('is_checked_in', false)
    //         ->orderBy('visit_date', 'asc')
    //         ->get();

    //     return view('reception.dashboard', compact(
    //         'gateClearedCount',
    //         'checkedInCount',
    //         'approvedTodayCount',
    //         'cardsAvailable',
    //         'gateClearedVisits',
    //         'checkedInVisits',
    //         'approvedVisits'
    //     ));
    // }

    // public function checkIn(Visit $visit)
    // {
    //     // Find an available access card
    //     $accessCard = AccessCard::where('is_issued', false)->first();

    //     if (!$accessCard) {
    //         return response()->json(['error' => 'No access cards available'], 400);
    //     }

    //     // Check in the visitor
    //     $visit->update([
    //         'is_checked_in' => true,
    //         'checked_in_at' => now(),
    //         'checkin_by' => auth('receptionist')->id(),
    //         'access_card_id' => $accessCard->id,
    //         'card_issued_at' => now(),
    //     ]);

    //     // Mark the card as issued
    //     $accessCard->update([
    //         'is_issued' => true,
    //         'issued_to' => $visit->visitor->name,
    //         'issued_at' => now(),
    //     ]);

    //     return response()->json(['success' => 'Visitor checked in successfully']);
    // }

    // public function checkOut(Visit $visit)
    // {
    //     if (!$visit->is_checked_in) {
    //         return response()->json(['error' => 'Visitor is not checked in'], 400);
    //     }

    //     // Check out the visitor
    //     $visit->update([
    //         'is_checked_out' => true,
    //         'checked_out_at' => now(),
    //         'checkout_by' => auth('receptionist')->id(),
    //         'card_retrieved_at' => now(),
    //     ]);

    //     // Make the access card available again
    //     if ($visit->accessCard) {
    //         $visit->accessCard->update([
    //             'is_issued' => false,
    //             'issued_to' => null,
    //             'issued_at' => null,
    //         ]);
    //     }

    //     return response()->json(['success' => 'Visitor checked out successfully']);
    // }
