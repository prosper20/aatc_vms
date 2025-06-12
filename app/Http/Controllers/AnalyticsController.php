<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\View\View;

use Illuminate\Http\Request;
use App\Models\Visitor;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    // public function index(): View
    // {
    //     $visits = Visit::all();
    //     // Implement more detailed analytics as needed
    //     return view('analytics.index', compact('visits'));
    // }

    public function index()
    {
        $today = Carbon::today();

        $visitsToday = Visit::whereDate('visit_date', $today)->count();
        $visitsThisWeek = Visit::whereBetween('visit_date', [
            Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()
        ])->count();

        $visitsThisMonth = Visit::whereMonth('visit_date', Carbon::now()->month)->count();

        $dailyVisitTrend = Visit::select(DB::raw('DATE(visit_date) as day'), DB::raw('count(*) as total'))
                                ->where('visit_date', '>=', Carbon::now()->subDays(7))
                                ->groupBy('day')
                                ->orderBy('day')
                                ->get();

        $weeklyVisitTrend = Visit::select(DB::raw('DAYNAME(visit_date) as day'), DB::raw('count(*) as total'))
                                 ->whereBetween('visit_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                 ->groupBy('day')
                                 ->get();

        $frequentVisitors = Visitor::withCount('visits')
                                   ->orderBy('visits_count', 'desc')
                                   ->take(5)
                                   ->get();

        return view('analytics.index', compact(
            'visitsToday', 'visitsThisWeek', 'visitsThisMonth', 'dailyVisitTrend', 'frequentVisitors', 'weeklyVisitTrend'
        ));
    }
}
