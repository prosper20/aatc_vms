<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Visit;

class ActiveVisits extends Component
{
    use WithPagination;

    public $refreshInterval = 30000; // 30 seconds

    protected $listeners = [
        'visitCreated' => 'refreshComponent',
        'visitUpdated' => 'refreshComponent',
        'visitCancelled' => 'refreshComponent'
    ];

    public function mount()
    {
        // Component mounted
    }

    public function refreshComponent()
    {
        // This will re-render the component
        $this->resetPage();
    }

    public function cancelVisit($visitId)
    {
        $visit = Visit::where('id', $visitId)
            ->where('staff_id', auth('staff')->id())
            ->firstOrFail();

        $visit->update(['status' => 'cancelled']);

        $this->emit('visitCancelled', $visitId);
        session()->flash('message', 'Visit cancelled successfully.');
    }

    public function resendCode($visitId)
    {
        $visit = Visit::where('id', $visitId)
            ->where('staff_id', auth('staff')->id())
            ->where('status', 'approved')
            ->firstOrFail();

        // Your resend logic here
        // Mail::to($visit->visitor->email)->send(new VisitCodeMail($visit));

        session()->flash('message', 'Invitation code resent successfully.');
    }

    public function render()
    {
        $activeVisits = Visit::with('visitor')
            ->where('staff_id', auth('staff')->id())
            ->whereIn('status', ['pending', 'approved'])
            ->where('visit_date', '>=', now()->toDateString())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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

        return view('livewire.staff.active-visits', compact('activeVisits', 'floorOptions'));
    }
}

