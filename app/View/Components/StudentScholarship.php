<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StudentScholarship extends Component
{
    public $tableheading;
    public $phototitle;
    public $nametitle;
    public $sessiontitle;
    public $scholarshiptitle;
    public $studentphoto;
    public $name;
    public $session;
    public $scholarship;

    public function __construct(
            $tableheading = 'Student Scholarship Information', $phototitle = 'Student Photo', $nametitle = 'Name', $sessiontitle = 'Session', 
            $scholarshiptitle = 'Scholarship', $studentphoto = 1, $name = 1, $session = 1, $scholarship = 1
        )
    {
        $this->tableheading = $tableheading;
        $this->phototitle = $phototitle;
        $this->nametitle = $nametitle;
        $this->sessiontitle = $sessiontitle;
        $this->scholarshiptitle = $scholarshiptitle;
        $this->studentphoto = $studentphoto;
        $this->name = $name;
        $this->session = $session;
        $this->scholarship = $scholarship;
    }

    public function render(): View|Closure|string
    {
        return view('components.'.activeTheme().'.student-scholarship');
    }
}
