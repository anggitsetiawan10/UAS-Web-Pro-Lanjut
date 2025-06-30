<?php

namespace App\Livewire\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    #[Layout('components.layouts.auth')]
    public $email;
    public $password;
    public function render()
    {
        return view('livewire.auth.login');
    }
    public function login()
    {
        $this->validate(
            rules:[
            	'email' => 'required|email',
            	'password' => 'required',
            ],
            messages: [
                'email.required'=>'Email tidak boleh kososng',
                'email.email'=>'Email format tidak benar',
                'password.required'=>'Password tidak boleh kososng'
            ]
        );
        $data_check = [
            'email' => $this->email,
            'password' => $this->password
        ];
        if (Auth::attempt(credentials:$data_check)) {
            return redirect()->route('dashboard');
        }else{
            return session()->flash('error', 'Email atau Password salah');
        }
    }
}
