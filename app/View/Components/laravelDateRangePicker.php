<?php

namespace App\View\Components;

use Illuminate\Http\Client\Request;
use Illuminate\View\Component;

class laravelDateRangePicker extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $reportId;
    public $route;
    public $format;

    public function __construct($reportId, $route, $format = "YYYY-MM-DD")
    {
        $this->reportId = $reportId;
        $this->route = $route;
        $this->format = $format;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.laravelDateRangePicker');
    }
}
