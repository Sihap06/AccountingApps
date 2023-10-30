<?php

namespace App\Http\Livewire\Auth;

use App\Http\Controllers\LoginController;
use Livewire\Component;

class Login extends Component
{

    private $loginClass;


    public function mount(LoginController $login)
    {
        $this->loginClass = $login;
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.auth');
    }
}
