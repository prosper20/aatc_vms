<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Visit;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SmDashboardController extends Controller
{
    public function index(Request $request)
    {
        $active_tab = $request->get('tab', 'approvals');
        $stats = $this->fetchStatistics();

        return view('sm-dashboard', [
            'active_tab' => $active_tab,
            'stats' => $stats
        ]);
    }

    public function getVisitors()
    {
        $visits = Visit::with(['visitor', 'staff'])
            ->where('status', 'pending')
            ->orderBy('visit_date', 'DESC')
            ->get();

        if ($visits->isEmpty()) {
            return view('partials.empty-state');
        }

        return view('partials.visitor-list', ['visits' => $visits]);
    }

    public function getStats()
    {
        return response()->json($this->fetchStatistics());
    }

    public function approve($id)
    {
        $visit = Visit::findOrFail($id);
        $visit->update(['status' => 'approved']);

        return response()->json(['success' => true]);
    }

    public function deny($id)
    {
        $visit = Visit::findOrFail($id);
        $visit->update(['status' => 'denied']);

        return response()->json(['success' => true]);
    }

    private function fetchStatistics()
    {
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();

        $stats = [
            'total_today' => Visit::whereDate('visit_date', $today)->count(),
            'approved_today' => Visit::whereDate('visit_date', $today)
                ->where('status', 'approved')->count(),
            'denied_today' => Visit::whereDate('visit_date', $today)
                ->where('status', 'denied')->count(),
            'pending_count' => Visit::where('status', 'pending')->count(),
            'hourly_data' => [],
            'repeat_visitors' => 0,
            'top_hosts' => [],
            'weekly_data' => [],
            'approval_rate' => 0,
            'total_visitors' => Visit::where('status', 'approved')->count()
        ];

        // Hourly distribution
        $hourlyData = Visit::whereDate('visit_date', $today)
            ->selectRaw('HOUR(visit_date) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        for ($i = 0; $i < 24; $i++) {
            $stats['hourly_data'][$i] = $hourlyData[$i] ?? 0;
        }

        // Repeat visitors (visitors with more than one visit)
        $stats['repeat_visitors'] = Visitor::has('visits', '>', 1)
            ->whereHas('visits', function($query) {
                $query->where('status', 'approved');
            })
            ->count();

        // Top hosts (staff with most visits)
        $stats['top_hosts'] = Staff::select('staff.name', DB::raw('COUNT(visits.id) as count'))
            ->join('visits', 'staff.id', '=', 'visits.staff_id')
            ->where('visits.status', 'approved')
            ->groupBy('staff.id', 'staff.name')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->toArray();

        // Weekly data
        $weeklyData = Visit::whereBetween('visit_date', [$weekStart, $weekEnd])
            ->selectRaw('DAYNAME(visit_date) as day, COUNT(*) as count')
            ->groupBy('day')
            ->orderByRaw('DAYOFWEEK(visit_date)')
            ->pluck('count', 'day')
            ->toArray();

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $stats['weekly_data'] = array_map(fn($day) => $weeklyData[$day] ?? 0, $days);

        // Last week's data
        $lastWeeklyData = Visit::whereBetween('visit_date', [$lastWeekStart, $lastWeekEnd])
            ->selectRaw('DAYNAME(visit_date) as day, COUNT(*) as count')
            ->groupBy('day')
            ->orderByRaw('DAYOFWEEK(visit_date)')
            ->pluck('count', 'day')
            ->toArray();

        $stats['last_weekly_data'] = array_map(fn($day) => $lastWeeklyData[$day] ?? 0, $days);

        // Approval rate
        $totalProcessed = $stats['approved_today'] + $stats['denied_today'];
        $stats['approval_rate'] = $totalProcessed > 0
            ? round(($stats['approved_today'] / $totalProcessed) * 100, 2)
            : 0;

        return $stats;
    }
}


// namespace App\Http\Controllers;

// use App\Models\Visitor;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use Carbon\Carbon;

// class SmDashboardController extends Controller
// {
//     public function index(Request $request)
//     {
//         $active_tab = $request->get('tab', 'approvals');
//         $stats = $this->fetchStatistics();

//         return view('sm-dashboard', [
//             'active_tab' => $active_tab,
//             'stats' => $stats
//         ]);
//     }

//     public function getVisitors()
//     {
//         $visitors = Visitor::where('status', 'pending')
//             ->orderBy('time_of_visit', 'DESC')
//             ->get();

//         if ($visitors->isEmpty()) {
//             return view('partials.empty-state');
//         }

//         return view('partials.visitor-list', ['visitors' => $visitors]);
//     }

//     public function getStats()
//     {
//         return response()->json($this->fetchStatistics());
//     }

//     public function approve($id)
//     {
//         $visitor = Visitor::findOrFail($id);
//         $visitor->update(['status' => 'approved']);

//         return response()->json(['success' => true]);
//     }

//     public function deny($id)
//     {
//         $visitor = Visitor::findOrFail($id);
//         $visitor->update(['status' => 'denied']);

//         return response()->json(['success' => true]);
//     }

//     private function fetchStatistics()
//     {
//         $today = Carbon::today();
//         $weekStart = Carbon::now()->startOfWeek();
//         $weekEnd = Carbon::now()->endOfWeek();
//         $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
//         $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();

//         $stats = [
//             'total_today' => Visitor::whereDate('time_of_visit', $today)->count(),
//             'approved_today' => Visitor::whereDate('time_of_visit', $today)
//                 ->where('status', 'approved')->count(),
//             'denied_today' => Visitor::whereDate('time_of_visit', $today)
//                 ->where('status', 'denied')->count(),
//             'pending_count' => Visitor::where('status', 'pending')->count(),
//             'hourly_data' => [],
//             'repeat_visitors' => 0,
//             'top_hosts' => [],
//             'weekly_data' => [],
//             'approval_rate' => 0,
//             'total_visitors' => Visitor::where('status', 'approved')->count()
//         ];

//         // Hourly distribution
//         $hourlyData = Visitor::whereDate('time_of_visit', $today)
//             ->selectRaw('HOUR(time_of_visit) as hour, COUNT(*) as count')
//             ->groupBy('hour')
//             ->pluck('count', 'hour')
//             ->toArray();

//         for ($i = 0; $i < 24; $i++) {
//             $stats['hourly_data'][$i] = $hourlyData[$i] ?? 0;
//         }

//         // Repeat visitors
//         $stats['repeat_visitors'] = Visitor::where('status', 'approved')
//             ->select('phone')
//             ->groupBy('phone')
//             ->havingRaw('COUNT(*) > 1')
//             ->count();

//         // Top hosts
//         $stats['top_hosts'] = Visitor::where('status', 'approved')
//             ->select('host_name', DB::raw('COUNT(*) as count'))
//             ->groupBy('host_name')
//             ->orderByDesc('count')
//             ->limit(5)
//             ->get()
//             ->toArray();

//         // Weekly data
//         $weeklyData = Visitor::whereBetween('time_of_visit', [$weekStart, $weekEnd])
//             ->selectRaw('DAYNAME(time_of_visit) as day, COUNT(*) as count')
//             ->groupBy('day')
//             ->orderByRaw('DAYOFWEEK(time_of_visit)')
//             ->pluck('count', 'day')
//             ->toArray();

//         $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
//         $stats['weekly_data'] = array_map(fn($day) => $weeklyData[$day] ?? 0, $days);

//         // Last week's data
//         $lastWeeklyData = Visitor::whereBetween('time_of_visit', [$lastWeekStart, $lastWeekEnd])
//             ->selectRaw('DAYNAME(time_of_visit) as day, COUNT(*) as count')
//             ->groupBy('day')
//             ->orderByRaw('DAYOFWEEK(time_of_visit)')
//             ->pluck('count', 'day')
//             ->toArray();

//         $stats['last_weekly_data'] = array_map(fn($day) => $lastWeeklyData[$day] ?? 0, $days);

//         // Approval rate
//         $totalProcessed = $stats['approved_today'] + $stats['denied_today'];
//         $stats['approval_rate'] = $totalProcessed > 0
//             ? round(($stats['approved_today'] / $totalProcessed) * 100, 2)
//             : 0;

//         return $stats;
//     }
// }
