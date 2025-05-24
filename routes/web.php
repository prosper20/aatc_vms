<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\Auth\EmployeeLoginController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\ProfileController;


Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);

    return redirect()->back();
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/hash/{password}', function ($password) {
    return Hash::make($password);
});


Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('/home/submit-request', [HomeController::class, 'submitRequest'])->name('home.submit');
Route::get('/register-visitor', [VisitorController::class, 'create'])->name('register_visitor');
Route::get('/profile/update', [ProfileController::class, 'edit'])->name('profile.update');


