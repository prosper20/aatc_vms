<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ModalController extends Controller
{
    public function index()
    {
        return view('modal-page');
    }

    public function visitorLookup(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $visitor = Visitor::where('email', $request->email)->first();

        if ($visitor) {
            return response()->json([
                'found' => true,
                'visitor' => $visitor,
            ]);
        }

        return response()->json([
            'found' => false,
            'visitor' => null,
        ]);
    }

    public function storeVisitors(Request $request)
    {
        // Verify staff is authenticated
    if (!auth('staff')->check()) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized - Staff not authenticated',
        ], 401);
    }

    // Get the authenticated staff member
    $staff = auth('staff')->user();
    $staffId = $staff->id;


        $request->validate([
            'visitors' => 'required|array',
            'visitors.*.email' => 'required|email',
            'visitors.*.name' => 'required|string',
            'visitors.*.phone' => 'nullable|string',
            'visitors.*.organization' => 'nullable|string',
            'visitors.*.date' => 'required|date_format:Y-m-d',
            'visitors.*.time' => 'required|date_format:H:i',
            'visitors.*.floor' => 'required|string',
            'visitors.*.reason' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->visitors as $data) {
                $visitDateTime = Carbon::createFromFormat(
                    'Y-m-d H:i',
                    $data['date'] . ' ' . $data['time']
                );
                // Either find or create the visitor
                $visitor = Visitor::firstOrCreate(
                    ['email' => $data['email']],
                    [
                        'name' => $data['name'],
                        'phone' => $data['phone'] ?? null,
                        'organization' => $data['organization'] ?? null,
                    ]
                );

                // Create associated visit
                Visit::create([
                    'visitor_id' => $visitor->id,
                    'staff_id' => $staffId,
                    'visit_date' => $visitDateTime,
                    'reason' => $data['reason'] ?? null,
                    'status' => 'pending',
                    'unique_code' => Str::uuid(),
                    'floor_of_visit' => $data['floor'],
                    'checked_in_at' => null,
                    'checked_out_at' => null,
                    'checkin_by' => null,
                    'checkout_by' => null,
                    'is_checked_in' => false,
                    'is_checked_out' => false,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Visitors and visits saved successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function uploadCSV(Request $request)
{
    if (!auth('staff')->check()) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized - Staff not authenticated',
        ], 401);
    }

    $request->validate([
        'file' => 'required|file|mimes:csv,txt|max:2048',
    ]);

    $staffId = auth('staff')->id();
    $file = $request->file('file');

    try {
        $rows = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_map('strtolower', array_map('trim', $rows[0]));
        unset($rows[0]); // remove header

        $visitors = [];

        foreach ($rows as $index => $row) {
            $data = array_combine($header, $row);

            $validator = Validator::make($data, [
                'email' => 'required|email',
                'name' => 'required|string',
                'phone' => 'nullable|string',
                'organization' => 'nullable|string',
                'date' => 'required|date_format:Y-m-d',
                'time' => 'required|date_format:H:i',
                'floor' => 'required|string',
                'reason' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => "Row " . ($index + 2) . " failed validation.",
                    'errors' => $validator->errors(),
                ], 422);
            }

            $visitors[] = $data;
        }

        return response()->json([
            'success' => true,
            'visitors' => $visitors, // return parsed visitor data to populate the UI
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'CSV processing failed.',
            'error' => $e->getMessage(),
        ], 500);
    }
}
}



// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;

// class ModalController extends Controller
// {
//     public function index()
//     {
//         return view('modal-page');
//     }

//     public function getModalData(Request $request)
//     {
//         // Simulate database lookup
//         $users = collect([
//             ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'status' => 'active'],
//             ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'status' => 'inactive'],
//             ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com', 'status' => 'active'],
//         ]);

//         // Simulate some conditional logic
//         $searchTerm = $request->input('search', '');
//         if ($searchTerm) {
//             $users = $users->filter(function ($user) use ($searchTerm) {
//                 return stripos($user['name'], $searchTerm) !== false ||
//                        stripos($user['email'], $searchTerm) !== false;
//             });
//         }

//         $activeCount = $users->where('status', 'active')->count();
//         $inactiveCount = $users->where('status', 'inactive')->count();

//         return response()->json([
//             'users' => $users->values(),
//             'stats' => [
//                 'total' => $users->count(),
//                 'active' => $activeCount,
//                 'inactive' => $inactiveCount
//             ],
//             'message' => $users->isEmpty() ? 'No users found' : 'Users loaded successfully'
//         ]);
//     }

//     public function visitorLookup(Request $request)
//     {
//         // Simulate visitor lookup database
//         $visitors = collect([
//             ['email' => 'john.doe@example.com', 'name' => 'John Doe', 'phone' => '+1234567890', 'organization' => 'Acme Inc.'],
//             ['email' => 'jane.smith@example.com', 'name' => 'Jane Smith', 'phone' => '+0987654321', 'organization' => 'Tech Corp'],
//             ['email' => 'bob.johnson@example.com', 'name' => 'Bob Johnson', 'phone' => '+1122334455', 'organization' => 'StartupXYZ'],
//             ['email' => 'alice.brown@example.com', 'name' => 'Alice Brown', 'phone' => '+5566778899', 'organization' => 'Design Studio'],
//         ]);

//         $email = $request->input('email', '');
//         $visitor = $visitors->firstWhere('email', $email);

//         if ($visitor) {
//             return response()->json([
//                 'found' => true,
//                 'visitor' => $visitor
//             ]);
//         }

//         return response()->json([
//             'found' => false,
//             'message' => 'Visitor not found'
//         ]);
//     }
// }
