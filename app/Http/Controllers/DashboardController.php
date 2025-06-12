<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingVisits = Visit::with(['visitor', 'staff'])->where('status', 'pending')->get();
        $stats = [
            'pending_count' => $pendingVisits->count()
        ];
    //     $visitors = Visit::with(['visitor', 'staff'])->where('status', 'pending')->get();
    // $stats = [
    //     'pending_count' => $visitors->count()
    // ];
        // $pendingRequests = Visit::with(['visitor', 'staff'])->where('status', 'pending')->get();
        // $stats = [
        //     'pending_count' => $pendingRequests->count()
        // ];
        $approvedToday = Visit::where('status', 'approved')->whereDate('created_at', Carbon::today())->count();
        $deniedToday = Visit::where('status', 'denied')->whereDate('created_at', Carbon::today())->count();
        $visitorsToday = Visit::whereDate('created_at', Carbon::today())->count();
        $visitTrends = Visit::selectRaw('DATE(created_at) as date, COUNT(*) as total')
                            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                            ->groupBy('date')->get();
        $visitHistory = Visit::with(['visitor', 'staff'])->latest()->take(5)->get();

        return view('dashboard.index', compact('pendingVisits', 'approvedToday', 'deniedToday', 'visitorsToday', 'visitTrends', 'visitHistory', 'stats'));
        // return view('dashboard.index', compact('pendingRequests', 'approvedToday', 'deniedToday', 'visitorsToday', 'visitTrends', 'visitHistory', 'stats'));
    }
}
