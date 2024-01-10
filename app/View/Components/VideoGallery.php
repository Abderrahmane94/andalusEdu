<?php

namespace App\View\Components;

use App\Models\SmVideoGallery;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class VideoGallery extends Component
{
    public $count;
    public $column;
    public $sorting;
    /**
     * Create a new component instance.
     */
    public function __construct($count = 3, $column = 4, $sorting = 'asc')
    {
        $this->count = $count;
        $this->column = $column;
        $this->sorting = $sorting;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        $videoGalleries = SmVideoGallery::query();
        $videoGalleries->where('school_id', app('school')->id);
        if ($this->sorting == 'asc') {
            $videoGalleries->orderBy('id', 'asc');
        } elseif ($this->sorting == 'desc') {
            $videoGalleries->orderBy('id', 'desc');
        } else {
            $videoGalleries->inRandomOrder();
        }
        $videoGalleries = $videoGalleries->take($this->count)->get();
        return view('components.' . activeTheme() . '.video-gallery', compact('videoGalleries'));
    }
}
