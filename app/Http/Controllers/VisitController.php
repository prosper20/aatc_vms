<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;


class VisitController extends Controller
{
    public function approve(Visit $visit): RedirectResponse
    {
        $visit->update(['status' => 'approved']);
        return redirect()->route('dashboard')->with('success', 'Visit approved successfully');
    }

public function deny(Visit $visit): RedirectResponse
{
    $visit->update(['status' => 'denied']);
    return redirect()->route('sm.dashboard')->with('success', 'Visit denied successfully');
}

public function pending(Request $request)
{
    $visitors = Visit::with(['visitor', 'staff'])
        ->where('status', 'pending')
        ->latest()
        ->get();

    $pendingCount = $visitors->count();

    if ($request->ajax()) {
        return response()->json([
            'html' => View::make('cso.partials.visitor-list', compact('visitors'))->render(),
            'pendingCount' => $pendingCount,
        ]);
    }

    return back();
}

}
