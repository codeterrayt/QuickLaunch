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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')
        ->name('dashboard');


    Route::view('spaces', 'spaces')
        ->name('spaces');



    Route::view('profile', 'profile')
        ->name('profile');
});


// Route::get("test",function(){
//     return '<iframe src="https://127.0.0.1:50336/" style="height:80vh; width:100%"  />';
// });

// Route::view('shared/spaces', 'spaces')
//     ->middleware(['auth', 'verified'])
//     ->name('shared_spaces');


require __DIR__ . '/auth.php';
