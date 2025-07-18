<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StaffHeader extends Component
{
    public $user;
    public $fullName;

    /**
     * Create a new component instance.
     *
     * @param  mixed  $user
     * @param  string  $fullName
     * @return void
     */
    public function __construct($user, $fullName)
    {
        $this->user = $user;
        $this->fullName = $fullName;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.staff-header');
    }
}
