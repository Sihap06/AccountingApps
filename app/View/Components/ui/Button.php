<?php

namespace App\View\Components\ui;

use Illuminate\View\Component;

class Button extends Component
{
    public $type;
    public $title;
    public $color;
    public $isLoading;
    public $wireLoading;

    public function __construct($type, $title, $color, $isLoading, $wireLoading)
    {
        $this->type = $type;
        $this->title = $title;
        $this->color = $color;
        $this->isLoading = $isLoading;
        $this->wireLoading  = $wireLoading;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.ui.button');
    }
}
