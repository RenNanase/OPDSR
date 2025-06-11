<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Sidebar extends Component
{
    public $showingSidebar;
    public $isMinimized;

    public function __construct($showingSidebar = false, $isMinimized = false)
    {
        $this->showingSidebar = $showingSidebar;
        $this->isMinimized = $isMinimized;
    }

    public function render()
    {
        return view('components.sidebar');
    }
}
