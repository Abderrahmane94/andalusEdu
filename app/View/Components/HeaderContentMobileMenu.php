<?php

namespace App\View\Components;

use Closure;
use App\SmHeaderMenuManager;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class HeaderContentMobileMenu extends Component
{
    public function __construct()
    {
        //
    }

    public function render(): View|Closure|string
    {
        $menus = SmHeaderMenuManager::where('theme', activeTheme())->where('school_id', app('school')->id)->where('parent_id', null)->orderBy('position')->get();
        return view('components.'.activeTheme().'.header-content-mobile-menu',compact('menus'));
    }
}
