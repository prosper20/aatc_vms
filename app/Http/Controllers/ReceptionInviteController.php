<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Visitor;
use App\Models\AccessCard;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ReceptionInviteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:receptionist');
    }

    public function sendInvitation(Request $request)
    {
        // Verify reception is authenticated
        if (!auth('receptionist')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - receptionist not authenticated',
            ], 401);
        }

        // Get the authenticated reception member
        $receptionist = auth('receptionist')->user();
        $receptionistId = $receptionist->id;
        $staffId = ""; // to be updpated

        $request->validate([
            'guests' => 'required|array',
            'guests.*.name' => 'required|string|max:255',
            'guests.*.email' => 'required|email|max:255',
            'guests.*.phone' => 'required|string|max:20',
            'guests.*.organization' => 'nullable|string|max:255',
            'guests.*.reason' => 'required|string|max:500',
            'guests.*.date' => 'required|date|after_or_equal:today',
            'guests.*.time' => 'required',
            'guests.*.floor' => 'required|string|max:50',
            'guests.*.host_id' => 'nullable|exists:staff,id',
        ]);

        DB::beginTransaction();

        try {
            $createdVisits = [];

            foreach ($request->guests as $guestData) {
                $visitDateTime = Carbon::createFromFormat(
                    'Y-m-d H:i',
                    $guestData['date'] . ' ' . $guestData['time']
                );

                // Find or create visitor
                $visitor = Visitor::firstOrCreate(
                    ['email' => $guestData['email']],
                    [
                        'name' => $guestData['name'],
                        'phone' => $guestData['phone'],
                        'organization' => $guestData['organization'] ?? null,
                    ]
                );

                $hostId = $guestData['host_id'] ?? 1;

                // Generate unique code (similar to storeVisitors but more readable)
                $uniqueCode = strtoupper(
                    dechex(time() % 0xFFFF) . '-' .
                    dechex(rand(0, 0xFFFF))
                );

                // Create visit record
                $visit = Visit::create([
                    'visitor_id' => $visitor->id,
                    'staff_id' => $hostId,
                    'visit_date' => $visitDateTime,
                    'reason' => $guestData['reason'],
                    'status' => 'pending',
                    'unique_code' => $uniqueCode,
                    'floor_of_visit' => $guestData['floor'],
                    'checked_in_at' => null,
                    'checked_out_at' => null,
                    'checkin_by' => null,
                    'checkout_by' => null,
                    'is_checked_in' => false,
                    'is_checked_out' => false,
                    'verification_message' => 'Visitor has not arrived at the gate',
                ]);

                $createdVisits[] = $visit;

            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invitations sent successfully!',
                'data' => [
                    'count' => count($createdVisits),
                    'visits' => $createdVisits,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Invitation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send invitations. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function getVisitDetails(Visit $visit)
    {
        return response()->json([
            'success' => true,
            'visit' => $visit->load('visitor')
        ]);
    }
}
