<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModalController extends Controller
{
    public function index()
    {
        return view('modal-page');
    }

    public function getModalData(Request $request)
    {
        // Simulate database lookup
        $users = collect([
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'status' => 'active'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'status' => 'inactive'],
            ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com', 'status' => 'active'],
        ]);

        // Simulate some conditional logic
        $searchTerm = $request->input('search', '');
        if ($searchTerm) {
            $users = $users->filter(function ($user) use ($searchTerm) {
                return stripos($user['name'], $searchTerm) !== false ||
                       stripos($user['email'], $searchTerm) !== false;
            });
        }

        $activeCount = $users->where('status', 'active')->count();
        $inactiveCount = $users->where('status', 'inactive')->count();

        return response()->json([
            'users' => $users->values(),
            'stats' => [
                'total' => $users->count(),
                'active' => $activeCount,
                'inactive' => $inactiveCount
            ],
            'message' => $users->isEmpty() ? 'No users found' : 'Users loaded successfully'
        ]);
    }

    public function visitorLookup(Request $request)
    {
        // Simulate visitor lookup database
        $visitors = collect([
            ['email' => 'john.doe@example.com', 'name' => 'John Doe', 'phone' => '+1234567890', 'organization' => 'Acme Inc.'],
            ['email' => 'jane.smith@example.com', 'name' => 'Jane Smith', 'phone' => '+0987654321', 'organization' => 'Tech Corp'],
            ['email' => 'bob.johnson@example.com', 'name' => 'Bob Johnson', 'phone' => '+1122334455', 'organization' => 'StartupXYZ'],
            ['email' => 'alice.brown@example.com', 'name' => 'Alice Brown', 'phone' => '+5566778899', 'organization' => 'Design Studio'],
        ]);

        $email = $request->input('email', '');
        $visitor = $visitors->firstWhere('email', $email);

        if ($visitor) {
            return response()->json([
                'found' => true,
                'visitor' => $visitor
            ]);
        }

        return response()->json([
            'found' => false,
            'message' => 'Visitor not found'
        ]);
    }
}
