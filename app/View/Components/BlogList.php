<?php

namespace App\View\Components;

use Closure;
use App\SmNews;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class BlogList extends Component
{
    public $count ;
    public $sorting;
    public $btntext;

    public function __construct($count = 4, $sorting = 'asc', $btntext = '+ Read More')
    {
        $this->count = $count;
        $this->sorting = $sorting;
        $this->btntext = $btntext;
    }

    public function render(): View|Closure|string
    {
        $news = SmNews::query();
        $news->where('school_id', app('school')->id)->where('status', 1);
        if($this->sorting =='asc'){
            $news->orderBy('id','asc');
        }elseif($this->sorting =='desc'){
            $news->orderBy('id','desc');
        }else{
            $news->inRandomOrder();
        }

        $news = $news->take($this->count)->get();
        return view('components.'.activeTheme().'.blog-list', compact('news'));
    }
}
