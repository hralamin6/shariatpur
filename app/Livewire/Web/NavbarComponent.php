<?php

namespace App\Livewire\Web;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NavbarComponent extends Component
{
    public $locale;

    public function logout()
    {
        Auth::logout();
        return redirect(route('login'));

    }
    public function updatedLocale()
    {
        session()->put('locale', $this->locale);
        return $this->redirect(url()->previous(), navigate:true);

    }
    public function render()
    {
        return view('livewire.web.navbar-component');
    }
}
