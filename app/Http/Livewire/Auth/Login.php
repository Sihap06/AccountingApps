<?php

namespace App\Http\Livewire\Auth;

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;
    public $isError = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    protected $messages = [
        'email.required' => 'This field is required',
        'email.email' => 'Invalid email',
        'password.required' => 'This field is required',
    ];


    public function handleLogin()
    {
        $this->isError = false;
        $validateData = $this->validate();
        $request = new Request();
        $request->merge($validateData);
        $response = app(LoginController::class)->login($request);
        $status = $response->getData(true)['status'];

        if ($status == 'success') {
            return redirect()->route('dashboard.index');
        } else {
            $this->isError = true;
        }
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.auth');
    }
}
