<?php

namespace App\View\Components\ui;

use Illuminate\View\Component;

class Input extends Component
{
    public string $name;
    public string $id;
    public string $label;
    public string $value;
    public string $type;
    public string $wireModel;

    public function __construct(
        string $name,
        string $id,
        string $label,
        string $value,
        string $type,
        string $wireModel
    ) {
        $this->name = $name;
        $this->id = $id;
        $this->label = $label;
        $this->value = $value;
        $this->type = $type;
        $this->wireModel = $wireModel;
    }

    public function render()
    {
        return view('components.ui.input');
    }
}
