<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ClientLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render()
    {
        return view('layouts.client');
    }
}
