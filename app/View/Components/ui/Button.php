<?php

namespace App\View\Components\ui;

use Illuminate\View\Component;

class Button extends Component
{
    public $type;
    public $title;
    public $color;
    public $isLoading;

    public function __construct($type, $title, $color, $isLoading)
    {
        $this->type = $type;
        $this->title = $title;
        $this->color = $color;
        $this->isLoading = $isLoading;
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
