<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;

class PointOfSales extends Component
{
    public $biaya;


    public function render()
    {
        $listData = [
            ['id' => 1, 'nama' => 'LCD Iphone 14', 'jumlah' => 2, 'harga' => 1000000],
            // ['id' => 2, 'nama' => 'Battery Iphone 13', 'jumlah' => 1, 'harga' => 100000],
            // ['id' => 3, 'nama' => 'IC Audio Iphone X', 'jumlah' => 1, 'harga' => 100000],
        ];
        return view('livewire.dashboard.point-of-sales', compact('listData'))
            ->layout('components.layouts.dashboard');
    }

    public function save()
    {
        $currencyString = preg_replace("/[^0-9]/", "", $this->biaya);
        // dd($currencyString);

        $this->dispatchBrowserEvent('swal', [
            'text' => 'This is a success message!',
            'title' => 'Kembalian',

        ]);
    }
}
