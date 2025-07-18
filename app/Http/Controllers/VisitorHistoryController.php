<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VisitorHistoryController extends Controller
{
    public function index(Request $request)
    {
        $pendingVisits = Visit::with(['visitor', 'staff'])->where('status', 'pending')->get();
        $query = Visit::with(['visitor', 'staff'])
            ->whereIn('status', ['approved', 'denied', 'completed']);

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

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
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

        // Order by latest first
        $visits = $query->orderBy('visit_date', 'desc')
                       ->orderBy('created_at', 'desc')
                       ->paginate(20)
                       ->appends($request->query());

        // Statistics for the current filters
        $stats = [
            'total_visits' => $query->count(),
            'approved_visits' => (clone $query)->where('status', 'approved')->count(),
            'denied_visits' => (clone $query)->where('status', 'denied')->count(),
            'completed_visits' => (clone $query)->where('status', 'completed')->count(),
        ];

        // Get unique floors for filter dropdown
        $floors = Visit::distinct()->pluck('floor_of_visit')->filter()->sort()->values();

        return view('dashboard.visitor-history', compact('visits', 'stats', 'floors', 'pendingVisits'));
    }

    public function show(Visit $visit)
    {
        $pendingVisits = Visit::with(['visitor', 'staff'])->where('status', 'pending')->get();
        $visit->load(['visitor', 'staff']);

        return view('dashboard.visitor-history-detail', compact('visit', 'pendingVisits'));
    }

    public function export(Request $request)
    {
        // This would typically use Excel export functionality
        // For now, we'll return a JSON response
        $query = Visit::with(['visitor', 'staff'])
            ->whereIn('status', ['approved', 'denied', 'completed']);

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

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('visit_date', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('visit_date', '<=', $request->get('date_to'));
        }

        $visits = $query->orderBy('visit_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $visits,
            'message' => 'Visitor history exported successfully'
        ]);
    }
}
