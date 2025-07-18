<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StaffSidebar extends Component
{
    public $user;
    public $fullName;
    public $staffEmail;

    /**
     * Create a new component instance.
     *
     * @param  mixed  $user
     * @param  string  $fullName
     * @param  string  $staffEmail
     * @return void
     */
    public function __construct($user, $fullName, $staffEmail)
    {
        $this->user = $user;
        $this->fullName = $fullName;
        $this->staffEmail = $staffEmail;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.staff-sidebar');
    }
}
