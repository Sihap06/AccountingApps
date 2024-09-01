<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SearchableSelect extends Component
{
    public $search = '';
    public $selected = null;
    public $selectedName = '';
    public $options = [];
    public $name = '';
    public $label = '';

    protected $listeners = ['resetSelect' => 'resetSelect', 'updateList'];

    public function updateList($name, $list)
    {
        if ($this->name === $name) {
            $this->options = $list;
        }
    }

    public function resetSelect($componentNames = [])
    {
        // Jika hanya satu nama yang dikirimkan sebagai string, konversi menjadi array
        if (is_string($componentNames)) {
            $componentNames = [$componentNames];
        }

        // Reset hanya jika nama komponen ada dalam array
        if (in_array($this->name, $componentNames)) {
            $this->selected = null;
            $this->selectedName = '';
        }
    }

    public function mount($list, $selectedOption = null, $name, $label)
    {
        $this->options = $list;
        $this->selected = $selectedOption;
        $this->name = $name;
        $this->label = $label;

        if ($this->selected) {
            $selectedOptionData = collect($this->options)->firstWhere('value', $this->selected);
            $this->selectedName = $selectedOptionData ? $selectedOptionData['label'] : null;
        }
    }

    public function selectOption($option)
    {
        $selectedOption = collect($this->options)->firstWhere('value', $option);

        $this->emitUp('setSelected', $option, $this->name);
        $this->selected = $option;
        $this->selectedName = $selectedOption ? $selectedOption['label'] : null;


        $this->dispatchBrowserEvent('close-dropdown');
    }

    public function render()
    {
        return view('livewire.searchable-select', [
            'filteredOptions' => collect($this->options)->filter(function ($option) {
                return str_contains(strtolower($option['label']), strtolower($this->search));
            })->all()
        ]);
    }
}
