<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('app', \App\Livewire\App\DashboardComponent::class)
    ->middleware(['auth', 'verified'])
    ->name('app.dashboard');
Route::get('app/roles', \App\Livewire\App\RoleComponent::class)
    ->middleware(['auth', 'verified'])
    ->name('app.roles');


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
Route::view('asdf', 'profile')
    ->middleware(['auth'])
    ->name('products');
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('users');

require __DIR__.'/auth.php';
