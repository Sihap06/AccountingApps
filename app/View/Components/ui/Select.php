<?php

namespace App\View\Components\ui;

use Illuminate\View\Component;

class Select extends Component
{
    public string $name;
    public string $id;
    public string $label;
    public string $value;
    public array $options;
    public bool $search;

    public function __construct(
        string $name,
        string $id,
        string $label,
        string $value,
        array $options,
        bool $search
    ) {
        $this->name = $name;
        $this->id = $id;
        $this->label = $label;
        $this->value = $value;
        $this->options = $options;
        $this->search = $search;
    }

    public function render()
    {
        return view('components.ui.select');
    }
}
