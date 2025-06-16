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

        $recentActivities = $this->getRecentActivities();

        // return view('analytics.index', compact(
        //     'visitsToday', 'visitsThisWeek', 'visitsThisMonth', 'dailyVisitTrend', 'frequentVisitors', 'weeklyVisitTrend', 'recentActivities'
        // ));

        $visitStatusStats = $this->getVisitStatusStats();

        // 2. Access Card Usage
        $accessCardStats = $this->getAccessCardStats();

        // 3. Floor Distribution
        $floorDistribution = $this->getFloorDistribution();

        // 4. Mode of Arrival Statistics
        $arrivalModeStats = $this->getArrivalModeStats();

        // 5. Peak Hours Analysis
        $peakHoursData = $this->getPeakHoursData();

        // 6. Average Visit Duration
        $visitDurationStats = $this->getVisitDurationStats();

        // 7. Staff Performance (Approvals)
        $staffPerformance = $this->getStaffPerformance();

        // 8. Security Verification Stats
        $verificationStats = $this->getVerificationStats();

        // 9. Organization-wise Visits
        $organizationStats = $this->getOrganizationStats();

        // 10. Vehicle vs Walk-in Statistics
        $vehicleStats = $this->getVehicleStats();

        return view('analytics.index', compact(
            'visitsToday', 'visitsThisWeek', 'visitsThisMonth', 'dailyVisitTrend',
            'frequentVisitors', 'weeklyVisitTrend', 'recentActivities',
            'visitStatusStats', 'accessCardStats', 'floorDistribution',
            'arrivalModeStats', 'peakHoursData', 'visitDurationStats',
            'staffPerformance', 'verificationStats', 'organizationStats', 'vehicleStats'
        ));
    }

    /**
     * Get recent activities for the dashboard
     */
    private function getRecentActivities()
    {
        $activities = collect();

        // Recent check-ins (last 24 hours)
        $recentCheckIns = Visit::with(['visitor'])
            ->whereNotNull('checked_in_at')
            ->where('checked_in_at', '>=', Carbon::now()->subHours(24))
            ->orderBy('checked_in_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentCheckIns as $visit) {
            $activities->push([
                'type' => 'check_in',
                'title' => 'Visitor Check-in',
                'description' => "{$visit->visitor->name} checked in",
                'timestamp' => $visit->checked_in_at,
                'visitor_name' => $visit->visitor->name,
                'icon' => 'fas fa-sign-in-alt',
                'color' => 'text-green-600',
                'bg_color' => 'bg-green-50'
            ]);
        }

        // Recent check-outs (last 24 hours)
        $recentCheckOuts = Visit::with(['visitor'])
            ->whereNotNull('checked_out_at')
            ->where('checked_out_at', '>=', Carbon::now()->subHours(24))
            ->orderBy('checked_out_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentCheckOuts as $visit) {
            $activities->push([
                'type' => 'check_out',
                'title' => 'Visitor Check-out',
                'description' => "{$visit->visitor->name} checked out",
                'timestamp' => $visit->checked_out_at,
                'visitor_name' => $visit->visitor->name,
                'icon' => 'fas fa-sign-out-alt',
                'color' => 'text-blue-600',
                'bg_color' => 'bg-blue-50'
            ]);
        }

        // Recent approvals (last 24 hours)
        $recentApprovals = Visit::with(['visitor'])
            ->where('status', 'approved')
            ->where('updated_at', '>=', Carbon::now()->subHours(24))
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentApprovals as $visit) {
            $activities->push([
                'type' => 'approval',
                'title' => 'Visit Approved',
                'description' => "Visit for {$visit->visitor->name} was approved",
                'timestamp' => $visit->updated_at,
                'visitor_name' => $visit->visitor->name,
                'icon' => 'fas fa-check-circle',
                'color' => 'text-emerald-600',
                'bg_color' => 'bg-emerald-50'
            ]);
        }

        // Recent gate verifications (last 24 hours)
        $recentVerifications = Visit::with(['visitor'])
            ->whereNotNull('arrived_at_gate')
            ->where('arrived_at_gate', '>=', Carbon::now()->subHours(24))
            ->orderBy('arrived_at_gate', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentVerifications as $visit) {
            $activities->push([
                'type' => 'verification',
                'title' => 'Gate Verification',
                'description' => "{$visit->visitor->name} arrived at gate",
                'timestamp' => $visit->arrived_at_gate,
                'visitor_name' => $visit->visitor->name,
                'icon' => 'fas fa-shield-alt',
                'color' => 'text-purple-600',
                'bg_color' => 'bg-purple-50',
                'extra_info' => $visit->verification_passed ? 'Verified' : 'Pending'
            ]);
        }

        // Sort all activities by timestamp and take the most recent 8
        return $activities->sortByDesc('timestamp')->take(8)->values();
    }

    /**
     * Get recent activities via AJAX
     */
    public function getRecentActivitiesAjax()
    {
        $activities = $this->getRecentActivities();

        return response()->json([
            'activities' => $activities,
            'last_updated' => now()->format('H:i:s')
        ]);
    }

      // 1. Visit Status Distribution
      private function getVisitStatusStats()
      {
          $statusCounts = Visit::select('status', DB::raw('count(*) as count'))
                              ->groupBy('status')
                              ->get()
                              ->pluck('count', 'status');

          $total = $statusCounts->sum();

          return [
              'pending' => $statusCounts->get('pending', 0),
              'approved' => $statusCounts->get('approved', 0),
              'rejected' => $statusCounts->get('rejected', 0),
              'total' => $total,
              'pending_percentage' => $total > 0 ? round(($statusCounts->get('pending', 0) / $total) * 100, 1) : 0,
              'approved_percentage' => $total > 0 ? round(($statusCounts->get('approved', 0) / $total) * 100, 1) : 0,
              'rejected_percentage' => $total > 0 ? round(($statusCounts->get('rejected', 0) / $total) * 100, 1) : 0,
          ];
      }

      // 2. Access Card Usage
      private function getAccessCardStats()
      {
          $today = Carbon::today();

          $cardsIssued = Visit::whereNotNull('access_card_id')
                             ->whereDate('card_issued_at', $today)
                             ->count();

          $cardsRetrieved = Visit::whereNotNull('card_retrieved_at')
                                ->whereDate('card_retrieved_at', $today)
                                ->count();

          $cardsOutstanding = Visit::whereNotNull('access_card_id')
                                   ->whereNull('card_retrieved_at')
                                   ->count();

          return [
              'issued_today' => $cardsIssued,
              'retrieved_today' => $cardsRetrieved,
              'outstanding' => $cardsOutstanding,
              'utilization_rate' => $cardsIssued > 0 ? round(($cardsRetrieved / $cardsIssued) * 100, 1) : 0
          ];
      }

      // 3. Floor Distribution
      private function getFloorDistribution()
      {
          return Visit::select('floor_of_visit', DB::raw('count(*) as count'))
                     ->whereNotNull('floor_of_visit')
                     ->groupBy('floor_of_visit')
                     ->orderBy('count', 'desc')
                     ->limit(10)
                     ->get();
      }

      // 4. Mode of Arrival Statistics
      private function getArrivalModeStats()
      {
          $modeCounts = Visit::select('mode_of_arrival', DB::raw('count(*) as count'))
                            ->whereNotNull('mode_of_arrival')
                            ->groupBy('mode_of_arrival')
                            ->get()
                            ->pluck('count', 'mode_of_arrival');

          $total = $modeCounts->sum();

          return [
              'vehicle' => $modeCounts->get('vehicle', 0),
              'foot' => $modeCounts->get('foot', 0),
              'total' => $total,
              'vehicle_percentage' => $total > 0 ? round(($modeCounts->get('vehicle', 0) / $total) * 100, 1) : 0,
              'foot_percentage' => $total > 0 ? round(($modeCounts->get('foot', 0) / $total) * 100, 1) : 0,
          ];
      }

      // 5. Peak Hours Analysis
      private function getPeakHoursData()
      {
          return Visit::select(DB::raw('HOUR(checked_in_at) as hour'), DB::raw('count(*) as count'))
                     ->whereNotNull('checked_in_at')
                     ->where('checked_in_at', '>=', Carbon::now()->subDays(7))
                     ->groupBy('hour')
                     ->orderBy('hour')
                     ->get();
      }

      // 6. Average Visit Duration
      private function getVisitDurationStats()
      {
          $completedVisits = Visit::whereNotNull('checked_in_at')
                                 ->whereNotNull('checked_out_at')
                                 ->where('checked_out_at', '>=', Carbon::now()->subDays(30))
                                 ->get();

          if ($completedVisits->isEmpty()) {
              return [
                  'average_duration_minutes' => 0,
                  'average_duration_formatted' => '0h 0m',
                  'shortest_visit' => 0,
                  'longest_visit' => 0,
                  'total_completed_visits' => 0
              ];
          }

          $durations = $completedVisits->map(function ($visit) {
              return Carbon::parse($visit->checked_out_at)->diffInMinutes(Carbon::parse($visit->checked_in_at));
          });

          $averageDuration = $durations->average();
          $hours = floor($averageDuration / 60);
          $minutes = $averageDuration % 60;

          return [
              'average_duration_minutes' => round($averageDuration, 1),
              'average_duration_formatted' => $hours . 'h ' . round($minutes) . 'm',
              'shortest_visit' => $durations->min(),
              'longest_visit' => $durations->max(),
              'total_completed_visits' => $completedVisits->count()
          ];
      }

      // 7. Staff Performance (Approvals)
      private function getStaffPerformance()
      {
          return Visit::with('staff')
                     ->select('staff_id', DB::raw('count(*) as approvals_count'))
                     ->whereNotNull('staff_id')
                     ->where('status', 'approved')
                     ->where('updated_at', '>=', Carbon::now()->subDays(30))
                     ->groupBy('staff_id')
                     ->orderBy('approvals_count', 'desc')
                     ->limit(5)
                     ->get();
      }

      // 8. Security Verification Stats
      private function getVerificationStats()
      {
          $today = Carbon::today();

          $totalArrivals = Visit::whereNotNull('arrived_at_gate')
                               ->whereDate('arrived_at_gate', $today)
                               ->count();

          $verifiedCount = Visit::where('verification_passed', true)
                               ->whereDate('arrived_at_gate', $today)
                               ->count();

          $pendingVerification = Visit::whereNotNull('arrived_at_gate')
                                     ->where('verification_passed', false)
                                     ->whereDate('arrived_at_gate', $today)
                                     ->count();

          return [
              'total_arrivals_today' => $totalArrivals,
              'verified_today' => $verifiedCount,
              'pending_verification' => $pendingVerification,
              'verification_rate' => $totalArrivals > 0 ? round(($verifiedCount / $totalArrivals) * 100, 1) : 0
          ];
      }

      // 9. Organization-wise Visits
      private function getOrganizationStats()
      {
          return Visitor::with('visits')
                       ->select('organization', DB::raw('count(*) as visitor_count'))
                       ->whereNotNull('organization')
                       ->groupBy('organization')
                       ->orderBy('visitor_count', 'desc')
                       ->limit(8)
                       ->get();
      }

      // 10. Vehicle Statistics
      private function getVehicleStats()
      {
          $vehicleVisits = Visit::where('mode_of_arrival', 'vehicle')->count();
          $dropOffCount = Visit::where('vehicle_type', 'drop-off')->count();
          $waitingCount = Visit::where('vehicle_type', 'wait')->count();

          return [
              'total_vehicle_visits' => $vehicleVisits,
              'drop_off_count' => $dropOffCount,
              'waiting_count' => $waitingCount,
              'drop_off_percentage' => $vehicleVisits > 0 ? round(($dropOffCount / $vehicleVisits) * 100, 1) : 0,
              'waiting_percentage' => $vehicleVisits > 0 ? round(($waitingCount / $vehicleVisits) * 100, 1) : 0,
          ];
      }
}
