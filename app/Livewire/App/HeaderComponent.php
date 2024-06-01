<?php

namespace App\Livewire\App;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HeaderComponent extends Component
{
    public $locale;
    public function logout()
    {
        Auth::logout();
        return redirect(route('home'));

    }
    public function updatedLocale()
    {
        session()->put('locale', $this->locale);
        return redirect()->to(url()->previous());
    }

public function render()
    {
        return view('livewire.app.header-component');
    }
}
