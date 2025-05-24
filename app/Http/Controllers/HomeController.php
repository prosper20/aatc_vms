<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Models\Visit;
use App\Models\Staff;
use Illuminate\Support\Str;

class HomeController extends Controller
{

    public function __construct()
{
    $this->middleware('auth:staff');
}

public function submitRequest(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'organization' => 'required|string',
        'visit_date' => 'required|date',
        'purpose' => 'required|string',
    ]);

    $staffId = session('staff_id');

    // Check if visitor already exists
    $visitor = Visitor::firstOrCreate(
        ['email' => $request->email],
        [
            'name' => $request->name,
            'phone' => $request->phone ?? null,
            'organization' => $request->organization,
        ]
    );

    // Create a new visit record
    Visit::create([
        'visitor_id' => $visitor->id,
        'staff_id' => $staffId,
        'visit_date' => $request->visit_date,
        'reason' => $request->purpose,
        'status' => 'Pending',
        'unique_code' => Str::uuid(),
    ]);

    return redirect()->route('home')->with('success', 'Request submitted!');
}


public function index(Request $request)
{
    $staffId = session('staff_id');
    if (!$staffId) return redirect('login');

    $staff = staff::findOrFail($staffId);
    $search = $request->input('search');

    $notifications = Visit::with('visitor')
        ->where('staff_id', $staffId)
        ->whereIn('status', ['approved', 'rejected'])
        ->orderBy('updated_at', 'desc')
        ->limit(3)
        ->get();

    $stats = [
        'total_requests' => Visit::where('staff_id', $staffId)->count(),
        'approved' => Visit::where('staff_id', $staffId)->where('status', 'approved')->count(),
        'declined' => Visit::where('staff_id', $staffId)->where('status', 'rejected')->count(),
    ];

    $requests = Visit::with('visitor')
    ->where('staff_id', $staffId)
    ->when($search, function ($query) use ($search) {
        $query->whereHas('visitor', function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%")
              ->orWhere('organization', 'like', "%$search%");
        })->orWhere('reason', 'like', "%$search%");
    })
    ->orderBy('created_at', 'desc')
    ->get();

    return view('home', compact('staff', 'notifications', 'stats', 'requests', 'search'));
}


}
