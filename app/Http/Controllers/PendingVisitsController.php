<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PendingVisitsController extends Controller
{
    public function index(Request $request)
    {
        $query = Visit::with(['visitor', 'staff'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('visitor', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('organization', 'like', "%{$search}%");
            })->orWhereHas('staff', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('reason', 'like', "%{$search}%")
              ->orWhere('unique_code', 'like', "%{$search}%");
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('visit_date', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('visit_date', '<=', $request->get('date_to'));
        }

        // Floor filter
        if ($request->filled('floor')) {
            $query->where('floor_of_visit', $request->get('floor'));
        }

        $visits = $query->paginate(20)->appends($request->query());

        // Get unique floors for filter dropdown
        $floors = Visit::where('status', 'pending')
            ->distinct()
            ->pluck('floor_of_visit')
            ->filter()
            ->sort()
            ->values();

        return view('dashboard.pending-visits', compact('visits', 'floors'));
    }

    public function export(Request $request)
    {
        $query = Visit::with(['visitor', 'staff'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc');

        // Apply same filters as index method
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('visitor', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('organization', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('visit_date', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('visit_date', '<=', $request->get('date_to'));
        }

        $visits = $query->get();

        return response()->json([
            'success' => true,
            'data' => $visits,
            'message' => 'Pending visits exported successfully'
        ]);
    }
}
