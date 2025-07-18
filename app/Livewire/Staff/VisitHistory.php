<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Visit;
use Carbon\Carbon;

class VisitHistory extends Component
{
    use WithPagination;

    protected $listeners = ['guestInvited' => '$refresh'];

    public function render()
    {
        $staffId = auth('staff')->id();

        $history = Visit::with('visitor')
            ->where('staff_id', $staffId)
            ->where(function ($query) {
                $query->where('visit_date', '<', Carbon::today())
                      ->orWhere('status', 'rejected')
                      ->orWhere('is_checked_out', true);
            })
            ->orderByDesc('visit_date')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.staff.visit-history', ['history' => $history]);
    }
}

