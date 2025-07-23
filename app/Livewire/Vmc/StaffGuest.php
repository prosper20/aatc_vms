<?php

namespace App\Livewire\Vmc;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Visit;
use App\Models\Visitor;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StaffGuest extends Component
{
    use WithFileUploads;

    // Guest management
    public $guests = [];
    public $currentGuestIndex = 0;
    public $csvFile;

    // Current guest form fields
    public $name = '';
    public $email = '';
    public $organization = '';
    public $visit_date = '';
    public $visit_time = '';
    public $floor_of_visit = '';
    public $reasonType = '';
    public $reason = '';
    public $customReason = '';
    public $phone = '';

    protected $rules = [
        'guests.*.name' => 'required|string|max:255',
        'guests.*.email' => 'required|email|max:255',
        'guests.*.organization' => 'nullable|string|max:255',
        'guests.*.visit_date' => 'required|date|after_or_equal:today',
        'guests.*.visit_time' => 'required',
        'guests.*.floor_of_visit' => 'required|string',
        'guests.*.reasonType' => 'required|in:Official,Personal,Other',
        'guests.*.customReason' => 'required_if:guests.*.reasonType,Other|string|max:500',
        'guests.*.reason' => 'required|string|max:500',
        'guests.*.phone' => 'required|string|max:20',
    ];

    public function mount()
    {
        // Initialize with one empty guest
        $this->guests = [
            [
                'name' => '',
                'email' => '',
                'organization' => '',
                'visit_date' => '',
                'visit_time' => '',
                'floor_of_visit' => '',
                'reason' => '',
                'phone' => '',
                'reasonType' => '',
                'customReason' => '',
            ]
        ];
        $this->loadCurrentGuest();
    }

    public function loadCurrentGuest()
    {
        $guest = $this->guests[$this->currentGuestIndex] ?? [];

        $this->name = $guest['name'] ?? '';
        $this->email = $guest['email'] ?? '';
        $this->organization = $guest['organization'] ?? '';
        $this->visit_date = $guest['visit_date'] ?? '';
        $this->visit_time = $guest['visit_time'] ?? '';
        $this->floor_of_visit = $guest['floor_of_visit'] ?? '';
        $this->reason = $guest['reason'] ?? '';
        $this->phone = $guest['phone'] ?? '';
        $this->reasonType = $guest['reasonType'] ?? '';
        $this->customReason = $guest['customReason'] ?? '';
    }

    public function saveCurrentGuest()
    {
        // Set the reason based on reasonType
        if ($this->reasonType === 'Other') {
            $this->reason = $this->customReason;
        } else {
            $this->reason = $this->reasonType;
        }

        $this->guests[$this->currentGuestIndex] = [
            'name' => $this->name,
            'email' => $this->email,
            'organization' => $this->organization,
            'visit_date' => $this->visit_date,
            'visit_time' => $this->visit_time,
            'floor_of_visit' => $this->floor_of_visit,
            'reason' => $this->reason,
            'phone' => $this->phone,
            'reasonType' => $this->reasonType,
            'customReason' => $this->customReason,
        ];
    }

    public function addGuest()
    {
        // Save current guest data
        $this->saveCurrentGuest();

        // Check if current guest is complete
        if (!$this->isCurrentGuestComplete()) {
            session()->flash('error', 'Please complete the current guest information before adding another.');
            return;
        }

        // Add new empty guest
        $this->guests[] = [
            'name' => '',
            'email' => '',
            'organization' => '',
            'visit_date' => '',
            'visit_time' => '',
            'floor_of_visit' => '',
            'reason' => '',
            'phone' => '',
            'reasonType' => '',
            'customReason' => '',
        ];

        // Move to new guest
        $this->currentGuestIndex = count($this->guests) - 1;
        $this->loadCurrentGuest();
    }

    public function removeGuest()
    {
        if (count($this->guests) > 1) {
            unset($this->guests[$this->currentGuestIndex]);
            $this->guests = array_values($this->guests); // Reindex array

            // Adjust current index
            $this->currentGuestIndex = min($this->currentGuestIndex, count($this->guests) - 1);
            $this->loadCurrentGuest();
        }
    }

    public function previousGuest()
    {
        if ($this->currentGuestIndex > 0) {
            $this->saveCurrentGuest();
            $this->currentGuestIndex--;
            $this->loadCurrentGuest();
        }
    }

    public function nextGuest()
    {
        if ($this->currentGuestIndex < count($this->guests) - 1) {
            $this->saveCurrentGuest();
            $this->currentGuestIndex++;
            $this->loadCurrentGuest();
        }
    }

    public function isCurrentGuestComplete()
    {
        $reasonComplete = !empty($this->reasonType) &&
                         ($this->reasonType !== 'Other' || !empty($this->customReason));

        return !empty($this->name) &&
               !empty($this->email) &&
               !empty($this->phone) &&
               $reasonComplete &&
               !empty($this->visit_date) &&
               !empty($this->visit_time) &&
               !empty($this->floor_of_visit);
    }

    public function downloadTemplate()
    {
        $csvContent = "Guest Name,Email,Phone,Organization,Visit Reason,Visit Date,Visit Time,Floor\n" .
                     "John Doe,john@example.com,+1234567890,ABC Corp,Business Meeting,2024-01-15,14:00,ground\n" .
                     "Jane Smith,jane@example.com,+1987654321,XYZ Ltd,Project Review,2024-01-16,10:30,mezzanine\n" .
                     "Chinedu Okafor,chinedu.okafor@example.com,+2348012345678,Zentech Ltd,Tech Demo,2024-01-17,11:15,1st\n" .
                     "Amina Bello,amina.bello@example.com,+2348098765432,GreenEdge Consult,Client Onboarding,2024-01-18,09:45,2nd";

        return response()->streamDownload(function () use ($csvContent) {
            echo $csvContent;
        }, 'guest_invitation_template.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function updatedCsvFile()
    {
        $this->validate([
            'csvFile' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        try {
            $path = $this->csvFile->getRealPath();
            $content = file_get_contents($path);
            $lines = explode("\n", $content);

            if (count($lines) < 2) {
                session()->flash('error', 'CSV file must contain at least a header row and one data row.');
                return;
            }

            $headers = str_getcsv($lines[0]);
            $importedGuests = [];

            for ($i = 1; $i < count($lines); $i++) {
                $values = str_getcsv($lines[$i]);

                if (count($values) >= 8 && !empty(trim($values[0]))) {
                    $importedGuests[] = [
                        'name' => trim($values[0]),
                        'email' => trim($values[1]),
                        'phone' => trim($values[2]),
                        'organization' => trim($values[3]),
                        'reason' => trim($values[4]),
                        'visit_date' => trim($values[5]),
                        'visit_time' => trim($values[6]),
                        'floor_of_visit' => trim($values[7]),
                        'reasonType' => 'Other', // Default for CSV imports
                        'customReason' => trim($values[4]), // Use the reason from CSV
                    ];
                }
            }

            if (count($importedGuests) > 0) {
                $this->guests = $importedGuests;
                $this->currentGuestIndex = 0;
                $this->loadCurrentGuest();
                session()->flash('message', count($importedGuests) . ' guest(s) imported successfully from CSV file.');
            } else {
                session()->flash('error', 'No valid guest data found in CSV file.');
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Error processing CSV file: ' . $e->getMessage());
        }

        // Reset the file input
        $this->csvFile = null;
    }

    public function updated($propertyName)
    {
        // Auto-save when form fields change, including reasonType and customReason
        if (in_array($propertyName, [
            'name', 'email', 'organization', 'visit_date', 'visit_time',
            'floor_of_visit', 'reason', 'phone', 'reasonType', 'customReason'
        ])) {
            $this->saveCurrentGuest();
        }
    }

    public function updatedReasonType($value)
    {
        if ($value !== 'Other') {
            $this->customReason = ''; // Clear custom reason when not 'Other'
        }
        // Save the current guest to update the array
        $this->saveCurrentGuest();
    }

    public function updatedCustomReason($value)
    {
        // Save the current guest when custom reason changes
        $this->saveCurrentGuest();
    }

    public function submit()
    {
        // Save current guest data
        $this->saveCurrentGuest();

        // Validate all guests
        $this->validate();

        DB::beginTransaction();

        try {
            $createdVisits = [];

            foreach ($this->guests as $guestData) {
                // Skip empty guests
                if (empty($guestData['name']) || empty($guestData['email'])) {
                    continue;
                }

                $visitDateTime = Carbon::createFromFormat(
                    'Y-m-d H:i',
                    $guestData['visit_date'] . ' ' . $guestData['visit_time']
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

                // Generate unique code (similar to controller)
                $uniqueCode = strtoupper(
                    dechex(time() % 0xFFFF) . '-' .
                    dechex(rand(0, 0xFFFF))
                );

                // Create visit record
                $visit = Visit::create([
                    'visitor_id' => $visitor->id,
                    'staff_id' => auth('staff')->id(),
                    'visit_date' => $visitDateTime,
                    'reason' => $guestData['reason'], // This will be properly set by saveCurrentGuest()
                    'status' => 'pending',
                    'unique_code' => $uniqueCode,
                    'floor_of_visit' => $guestData['floor_of_visit'],
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

            // Emit events for each created visit
            foreach ($createdVisits as $visit) {
                $this->dispatch('visitCreated', visitId: $visit->id);
            }

            // Reset form
            $this->guests = [
                [
                    'name' => '',
                    'email' => '',
                    'organization' => '',
                    'visit_date' => '',
                    'visit_time' => '',
                    'floor_of_visit' => '',
                    'reason' => '',
                    'phone' => '',
                    'reasonType' => '',
                    'customReason' => '',
                ]
            ];
            $this->currentGuestIndex = 0;
            $this->loadCurrentGuest();

            session()->flash('message', 'Invitations sent successfully! ' . count($createdVisits) . ' guest(s) will receive email invitations with unique codes.');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to send invitations. Please try again.');
        }
    }

    public function render()
    {
        $floorOptions = [
            'ground' => 'Ground Floor',
            'mezzanine' => 'Mezzanine',
            '1st' => 'Floor 1',
            '2nd' => 'Floor 2',
            '3rd' => 'Floor 3',
            '4th' => 'Floor 4',
            '5th' => 'Floor 5',
            '6th' => 'Floor 6',
            '7th' => 'Floor 7',
            '8th' => 'Floor 8',
            '9th' => 'Floor 9',
        ];

        return view('livewire.vmc.staff-guest', compact('floorOptions'));
    }
}
