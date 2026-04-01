<?php

namespace App\View\Components\ui;

use Illuminate\View\Component;

class InputDefault extends Component
{
    public $label;
    public $name;
    public $type;
    public $id;
    public $value;
    public $disabled;
    public $placeholder;
    public $required;
    public $error;
    public $inline;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $label = '',
        $name = '',
        $type = 'text',
        $id = null,
        $value = '',
        $disabled = false,
        $placeholder = '',
        $required = false,
        $error = null,
        $inline = false
    ) {
        $this->label = $label;
        $this->name = $name;
        $this->type = $type;
        $this->id = $id ?? $name;
        $this->value = $value;
        $this->disabled = $disabled;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->error = $error;
        $this->inline = $inline;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.ui.input-default');
    }
}
