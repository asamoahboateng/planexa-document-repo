<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Filament\Notifications\Notification;

class AuthorizeUsers extends Component
{
    public $type = 'login', $username, $password;

    public function authhenticate()
    {
        $this->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($this->type == 'login') {
            if (auth()->attempt(['username' => $this->username, 'password' => $this->password])) {
                Notification::make()->title('Login Successful')->success()->send();
                return redirect()->route('backend.dashboard');
            }
        }

    }
    public function render()
    {
        return view('livewire.admin.authorize-users')->extends('backend.layouts.auth')->section('contents');
    }
}
