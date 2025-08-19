<?php

namespace App\Livewire\Vmc;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Visit;
use Carbon\Carbon;

class VisitorHistory extends Component
{
    use WithPagination;

    public $search = '';
    protected $queryString = ['search' => ['except' => '']];

    protected $listeners = ['guestInvited' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $staffId = auth('staff')->id();

        $query = Visit::with(['visitor', 'staff'])
            ->where('staff_id', $staffId)
            ->where(function ($query) {
                $query->where('visit_date', '<', Carbon::today())
                      ->orWhere('status', 'rejected')
                      ->orWhere('is_checked_out', true);
            });

        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('visitor', function($visitorQuery) {
                    $visitorQuery->where('name', 'like', '%'.$this->search.'%')
                                 ->orWhere('email', 'like', '%'.$this->search.'%')
                                 ->orWhere('phone', 'like', '%'.$this->search.'%');
                })
                ->orWhereHas('staff', function($staffQuery) {
                    $staffQuery->where('name', 'like', '%'.$this->search.'%');
                })
                ->orWhere('reason', 'like', '%'.$this->search.'%')
                ->orWhere('floor_of_visit', 'like', '%'.$this->search.'%');
            });
        }

        $history = $query->orderByDesc('visit_date')
                         ->orderByDesc('created_at')
                         ->paginate(10);

        return view('livewire.vmc.visitor-history', [
            'history' => $history
        ]);
    }
}
