<?php

use App\Livewire\Test;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::view('spaces', 'spaces')
    ->middleware(['auth', 'verified'])
    ->name('spaces');

Route::get("test",function(){
    return '<iframe src="https://127.0.0.1:50336/" style="height:80vh; width:100%"  />';
});

Route::view('shared/spaces', 'spaces')
    ->middleware(['auth', 'verified'])
    ->name('shared_spaces');


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
