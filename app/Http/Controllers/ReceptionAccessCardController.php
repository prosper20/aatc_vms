<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\AccessCard;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReceptionAccessCardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:receptionist');
    }

//     public function getPassDetails(Visit $visit)
//     {
//         // Check if visitor already has a pass or access card
//         if ($visit->accessCard || $visit->visitorPass) {
//             return response()->json([
//                 'success' => false,
//                 'error' => 'Visitor already has an access card or pass issued'
//             ]);
//         }

//         // Get available passes
//         $availablePasses = AccessCard::where('is_active', true)
//             ->where(function($query) {
//                 $query->whereNull('issued_at')
//                     ->orWhere('valid_until', '<', now());
//             })
//             ->get();

//         return response()->json([
//             'success' => true,
//             'visitor' => $visit->visitor,
//             'staff' => $visit->staff,
//             'visit' => $visit,
//             'availablePasses' => $availablePasses
//         ]);
//     }

//     public function issuePass(Request $request, Visit $visit)
//     {
//         $validated = $request->validate([
//             'pass_id' => 'required|integer|exists:access_cards,id',
//         ]);

//         // Check if visitor already has a pass or access card
//         if ($visit->accessCard) {
//             return response()->json([
//                 'success' => false,
//                 'error' => 'Visitor already has an access card or pass issued'
//             ]);
//         }

//         try {
//             $now = now();
//             $validUntil = $now->copy()->hour(18)->minute(0)->second(0); // Today 6 PM

//             // If current time is past 6PM, set it to 12 hours from now instead
//             if ($now->greaterThan($validUntil)) {
//                 $validUntil = $now->addHours(12);
//             }

//             $receptionist = auth('receptionist')->user();

//             // Update the pass record
//             $pass = AccessCard::find($validated['pass_id']);
//             $pass->update([
//                 'issued_to' => $visit->visitor->name,
//                 'issued_at' => now(),
//                 'issued_by' => $receptionist->name,
//                 'valid_until' => $validUntil,
//                 'is_active' => true
//             ]);

//             // Update the visit record
//             $visit->update([
//                 'access_card_id' => $validated['pass_id'],
//                 'card_issued_at' => now(),
//             ]);

//             return response()->json(['success' => true]);
//         } catch (\Exception $e) {
//             return response()->json(['success' => false, 'error' => $e->getMessage()]);
//         }
//     }

//     public function generatePass(Request $request)
// {
//     $request->validate([
//         'floor' => 'required|string'
//     ]);

//     try {
//         $floor = $request->floor;

//         $map = [
//             'Ground Floor' => 'GF',
//             'Mezzanine' => 'MZ',
//             'Floor 1' => 'F1',
//             'Floor 2 - Right Wing' => 'F2RW',
//             'Floor 2 - Left Wing' => 'F2LW',
//             'Floor 3 - Right Wing' => 'F3RW',
//             'Floor 3 - Left Wing' => 'F3LW',
//             'Floor 4 - Right Wing' => 'F4RW',
//             'Floor 4 - Left Wing' => 'F4LW',
//             'Floor 5 - Right Wing' => 'F5RW',
//             'Floor 5 - Left Wing' => 'F5LW',
//             'Floor 6 - Right Wing' => 'F6RW',
//             'Floor 6 - Left Wing' => 'F6LW',
//             'Floor 7 - Right Wing' => 'F7RW',
//             'Floor 7 - Left Wing' => 'F7LW',
//             'Floor 8 - Right Wing' => 'F8RW',
//             'Floor 8 - Left Wing' => 'F8LW',
//             'Floor 9 - Right Wing' => 'F9RW',
//             'Floor 9 - Left Wing' => 'F9LW',
//         ];

//         // Get abbreviation or fallback to 'XX'
//         $abbr = $map[$floor] ?? 'XX';

//         // Find last serial for this abbreviation
//         $lastPass = AccessCard::where('serial_number', 'like', $abbr . '-%')
//             ->orderByRaw("CAST(SUBSTRING_INDEX(serial_number, '-', -1) AS UNSIGNED) DESC")
//             ->first();

//         $nextNumber = 1;

//         if ($lastPass) {
//             $parts = explode('-', $lastPass->serial_number);
//             $lastNumber = intval(end($parts));
//             $nextNumber = $lastNumber + 1;
//         }

//         $serialNumber = $abbr . '-' . $nextNumber;

//         $pass = AccessCard::create([
//             'serial_number' => $serialNumber,
//             'is_active' => true,
//             'card_type' => 'visitor_pass',
//             'access_level' => 'low'
//         ]);

//         return response()->json([
//             'success' => true,
//             'pass' => $pass
//         ]);
//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'error' => $e->getMessage()
//         ]);
//     }
// }



//     public function printPass(Visit $visit)
//     {
//         if (!$visit->accessCard) {
//             abort(404, 'No pass issued for this visit');
//         }

//         return view('reception.print-pass', [
//             'visit' => $visit,
//             'pass' => $visit->visitorPass
//         ]);
//     }

//     // Access Card functions
//     public function getAccessCardDetails(Visit $visit)
//     {
//         // Check if visitor already has an access card
//         if ($visit->accessCard && $visit->accessCard->card_type === 'access_card') {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Visitor already has an Access Card',
//                 'visitor' => $visit->visitor,
//                 'staff' => $visit->staff,
//                 'accessCard' => $visit->accessCard
//             ]);
//         }

//         // Create an access card
//         // validity should be 1 weeek by default after the card is displayed there should be an option in the modal to update the validity
//         $lastPass = AccessCard::orderBy('id', 'desc')->first();
//             $nextId = $lastPass ? $lastPass->id + 1 : 1;
//             $serialNumber = 'AC-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

//             $accessCard = AccessCard::create([
//                 'serial_number' => $serialNumber,
//                 'is_active' => true
//             ]);

//         return response()->json([
//             'success' => true,
//             'message' => 'Card Created Successfully',
//             'visitor' => $visit->visitor,
//             'staff' => $visit->staff,
//             'accessCard' => $accessCard
//         ]);
//     }

//     public function issueAccessCardOld(Visit $visit)
//     {
//         // Check if already has an access card
//         if ($visit->accessCard && $visit->accessCard->card_type === 'access_card') {
//             return response()->json(['success' => true]);
//         }

//         // Find an available access card
//         $accessCard = AccessCard::accessCards()->available()->first();

//         if (!$accessCard) {
//             return response()->json([
//                 'success' => false,
//                 'error' => 'No available access cards'
//             ]);
//         }

//         try {
//             // Issue the card
//             $accessCard->update([
//                 'issued_to' => $visit->visitor->name,
//                 'issued_at' => now(),
//                 'issued_by' => auth()->user()->name,
//                 'valid_until' => now()->addHours(8), // 8-hour validity
//                 'is_issued' => true
//             ]);

//             // Update the visit record
//             $visit->update([
//                 'access_card_id' => $accessCard->id,
//                 'card_issued_at' => now(),
//                 'checkin_by' => auth()->user()->name
//             ]);

//             return response()->json(['success' => true]);
//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'error' => $e->getMessage()
//             ]);
//         }
//     }

//     public function printAccessCard(Visit $visit)
//     {
//         if (!$visit->accessCard || $visit->accessCard->card_type !== 'access_card') {
//             abort(404, 'No access card issued for this visit');
//         }

//         return view('reception.print-access-card', [
//             'visit' => $visit,
//             'accessCard' => $visit->accessCard
//         ]);
//     }

    // check out functions
    public function getCheckoutDetails(Visit $visit)
    {
        $hasAccessCard = $visit->accessCard && $visit->accessCard->card_type === 'access_card';

        return response()->json([
            'success' => true,
            'hasAccessCard' => $hasAccessCard,
            'accessCard' => $hasAccessCard ? $visit->accessCard : null
        ]);
    }

    public function checkout(Request $request, Visit $visit)
    {
        try {
            DB::beginTransaction();

            // Retrieve card if one was issued
            if ($visit->accessCard) {
                $visit->accessCard->update([
                    'issued_to' => null,
                    'issued_at' => null,
                    'issued_by' => null,
                    'is_issued' => false,
                    'valid_until' => null
                ]);

                $visit->update([
                    'card_retrieved_at' => now(),
                    'checkout_by' => auth()->user()->name
                ]);
            }

            // Checkout the visitor
            $visit->update([
                'checked_out_at' => now(),
                'is_checked_out' => true,
                'checkout_notes' => $request->input('notes')
            ]);

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // new code

    public function getCardDetails(Visit $visit)
    {
        return response()->json([
            'success' => true,
            'visitor' => $visit->visitor,
            'staff' => $visit->staff,
            'visit' => $visit,
            'currentCard' => $visit->accessCard,
            'hasCard' => !is_null($visit->accessCard),
            'cardType' => $visit->accessCard ? $visit->accessCard->card_type : null,
            'availablePasses' => AccessCard::where('is_active', true)
                ->where('card_type', 'visitor_pass')
                ->where(function($query) {
                    $query->whereNull('issued_at')
                        ->orWhere('valid_until', '<', now());
                })
                ->get()
        ]);
    }

    public function issueCard(Request $request, Visit $visit)
    {
        try {
            $validated = $request->validate([
                'card_type' => 'required|in:visitor_pass,access_card',
                'pass_id' => 'nullable|integer|exists:access_cards,id',
                'floor' => 'nullable|string',
                'action' => 'required|in:issue,retrieve_and_issue'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $receptionist = auth('receptionist')->user();

            // Handle retrieve and issue scenario
            if ($validated['action'] === 'retrieve_and_issue' && $visit->accessCard) {
                $this->retrieveCard($visit);
            }

            if ($validated['card_type'] === 'visitor_pass') {
                $this->issueVisitorPass($visit, $validated, $receptionist);
            } else {
                $this->issueAccessCard($visit, $receptionist);
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function retrieveCard(Visit $visit)
    {
        $card = $visit->accessCard;

        if ($card->card_type === 'visitor_pass') {
            // Reset pass to available state
            $card->update([
                'issued_to' => null,
                'issued_at' => null,
                'issued_by' => null,
                'is_issued' => false,
                'valid_until' => null
            ]);
        } else {
            // Delete access card as it's user-specific
            $card->delete();
        }

        // Clear visit card association
        $visit->update([
            'access_card_id' => null,
            'card_issued_at' => null,
            'card_retrieved_at' => now()
        ]);
    }

    private function issueVisitorPass(Visit $visit, array $validated, $receptionist)
    {
        $pass = null;

        if (isset($validated['pass_id'])) {
            // Use existing pass
            $pass = AccessCard::find($validated['pass_id']);
        } else {
            // Generate new pass
            $pass = $this->generateNewPass($validated['floor']);
        }

        $now = now();
        $validUntil = $now->copy()->hour(18)->minute(0)->second(0); // Today 6 PM

        if ($now->greaterThan($validUntil)) {
            $validUntil = $now->addHours(12);
        }

        $pass->update([
            'issued_to' => $visit->visitor->name,
            'issued_at' => now(),
            'issued_by' => $receptionist->name,
            'valid_until' => $validUntil,
            'is_issued' => true
        ]);

        $visit->update([
            'access_card_id' => $pass->id,
            'card_issued_at' => now(),
            'checked_in_at' => now(),
            'checkin_by' => $receptionist->name
        ]);
    }

    private function issueAccessCard(Visit $visit, $receptionist)
    {
        // Generate new access card
        $lastCard = AccessCard::where('card_type', 'access_card')
            ->orderBy('id', 'desc')
            ->first();

        $nextId = $lastCard ? $lastCard->id + 1 : 1;
        $serialNumber = 'AC-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $accessCard = AccessCard::create([
            'serial_number' => $serialNumber,
            'card_type' => 'access_card',
            'access_level' => 'low',
            'issued_to' => $visit->visitor->name,
            'issued_at' => now(),
            'issued_by' => $receptionist->name,
            'valid_until' => now()->addWeek(), // 1 week validity
            'is_active' => true,
            'is_issued' => true
        ]);

        $visit->update([
            'access_card_id' => $accessCard->id,
            'card_issued_at' => now(),
            'checked_in_at' => now(),
            'checkin_by' => $receptionist->name
        ]);
    }

    private function generateNewPass($floor)
    {
        $map = [
            'Ground Floor' => 'GF',
            'Mezzanine' => 'MZ',
            'Floor 1' => 'F1',
            'Floor 2 - Right Wing' => 'F2RW',
            'Floor 2 - Left Wing' => 'F2LW',
            'Floor 3 - Right Wing' => 'F3RW',
            'Floor 3 - Left Wing' => 'F3LW',
            'Floor 4 - Right Wing' => 'F4RW',
            'Floor 4 - Left Wing' => 'F4LW',
            'Floor 5 - Right Wing' => 'F5RW',
            'Floor 5 - Left Wing' => 'F5LW',
            'Floor 6 - Right Wing' => 'F6RW',
            'Floor 6 - Left Wing' => 'F6LW',
            'Floor 7 - Right Wing' => 'F7RW',
            'Floor 7 - Left Wing' => 'F7LW',
            'Floor 8 - Right Wing' => 'F8RW',
            'Floor 8 - Left Wing' => 'F8LW',
            'Floor 9 - Right Wing' => 'F9RW',
            'Floor 9 - Left Wing' => 'F9LW',
        ];

        $abbr = $map[$floor] ?? 'XX';

        $lastPass = AccessCard::where('serial_number', 'like', $abbr . '-%')
            ->orderByRaw("CAST(SUBSTRING_INDEX(serial_number, '-', -1) AS UNSIGNED) DESC")
            ->first();

        $nextNumber = 1;
        if ($lastPass) {
            $parts = explode('-', $lastPass->serial_number);
            $lastNumber = intval(end($parts));
            $nextNumber = $lastNumber + 1;
        }

        $serialNumber = $abbr . '-' . $nextNumber;

        return AccessCard::create([
            'serial_number' => $serialNumber,
            'is_active' => true,
            'card_type' => 'visitor_pass',
            'access_level' => 'low'
        ]);
    }

    public static function decodeSerialNumber(string $serial): array
    {
        $map = [
            'Ground Floor' => 'GF',
            'Mezzanine' => 'MZ',
            'Floor 1' => 'F1',
            'Floor 2 - Right Wing' => 'F2RW',
            'Floor 2 - Left Wing' => 'F2LW',
            'Floor 3 - Right Wing' => 'F3RW',
            'Floor 3 - Left Wing' => 'F3LW',
            'Floor 4 - Right Wing' => 'F4RW',
            'Floor 4 - Left Wing' => 'F4LW',
            'Floor 5 - Right Wing' => 'F5RW',
            'Floor 5 - Left Wing' => 'F5LW',
            'Floor 6 - Right Wing' => 'F6RW',
            'Floor 6 - Left Wing' => 'F6LW',
            'Floor 7 - Right Wing' => 'F7RW',
            'Floor 7 - Left Wing' => 'F7LW',
            'Floor 8 - Right Wing' => 'F8RW',
            'Floor 8 - Left Wing' => 'F8LW',
            'Floor 9 - Right Wing' => 'F9RW',
            'Floor 9 - Left Wing' => 'F9LW',
        ];

        $reverseMap = array_flip($map);

        [$abbr, $number] = explode('-', $serial);
        $location = $reverseMap[$abbr] ?? 'Unknown Location';

        // Split floor and wing into separate lines
        $lines = explode(' - ', $location);
        $floor = $lines[0] ?? '';
        $wing = $lines[1] ?? '';

        return [
            'floor' => $floor,
            'wing' => $wing,
            'pass_id' => str_pad($number, 3, '0', STR_PAD_LEFT),
        ];
    }

    public function getPrintDetails(Visit $visit)
    {
        if (!$visit->accessCard) {
            return response()->json([
                'success' => false,
                'error' => 'No card has been issued to this visitor. Please issue a card first.'
            ]);
        }

        $decoded = $this->decodeSerialNumber($visit->accessCard->serial_number);

        return response()->json([
            'success' => true,
            'card' => $visit->accessCard,
            'visitor' => $visit->visitor,
            'staff' => $visit->staff,
            'decoded' => $decoded,
            'visit' => $visit
        ]);
    }

    public function formatDuration($startDate, $endDate)
    {
        $totalSeconds = strtotime($endDate) - strtotime($startDate);

        if ($totalSeconds <= 0) {
            return 'Expired';
        }

        $units = [
            ['label' => 'month', 'seconds' => 30 * 24 * 60 * 60],
            ['label' => 'week', 'seconds' => 7 * 24 * 60 * 60],
            ['label' => 'day', 'seconds' => 24 * 60 * 60],
            ['label' => 'hour', 'seconds' => 60 * 60],
            ['label' => 'minute', 'seconds' => 60],
        ];

        foreach ($units as $unit) {
            $value = floor($totalSeconds / $unit['seconds']);
            if ($value > 0) {
                return $value . ' ' . $unit['label'] . ($value > 1 ? 's' : '');
            }
        }

        return 'Less than a minute';
    }


    public function printCard(Visit $visit, Request $request)
    {
        $side = $request->get('side', 'front');
        $viewName = $visit->accessCard->card_type === 'access_card'
            ? 'reception.print-access-card'
            : 'reception.print-visitor-pass';

        $decoded = $this->decodeSerialNumber($visit->accessCard->serial_number);

        $validUntil = $visit->accessCard->valid_until;
        $now = now();

        $duration = $this->formatDuration($now, $validUntil);

        return view($viewName, [
            'visit' => $visit,
            'staff' => $visit->staff,
            'visitor' => $visit->visitor,
            'card' => $visit->accessCard,
            'decoded' => $decoded,
            'duration' => $duration,
            'side' => $side,
        ]);
    }
}
