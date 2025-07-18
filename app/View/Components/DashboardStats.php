<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DashboardStats extends Component
{
    public $stats;

    /**
     * Create a new component instance.
     *
     * @param  array  $stats
     * @return void
     */
    public function __construct($stats)
    {
        $this->stats = $stats;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dashboard-stats');
    }
}
