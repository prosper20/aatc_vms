<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\Auth\EmployeeLoginController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Auth\LoginController;


Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);

    return redirect()->back();
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/hash/{password}', function ($password) {
    return Hash::make($password);
});


Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('/home/submit-request', [HomeController::class, 'submitRequest'])->name('home.submit');

// Route::get('/profile/update', [ProfileController::class, 'edit'])->name('profile.update');

Route::middleware(['auth:staff'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/register-visitor', [VisitorController::class, 'create'])->name('register_visitor');
Route::post('/register-visitor', [VisitorController::class, 'store'])->name('visitors.store');
Route::post('/visitors/import', [VisitorController::class, 'import'])->name('visitors.import');

Route::get('/visitors/sample-csv', function () {
    $csvContent = <<<CSV
name,phone,email,organization,visit_date,time_of_visit,floor,reason
John Doe,+2341234567890,john@example.com,ABC Corp,2025-05-20,14:30,Floor 3,Business Meeting
Jane Smith,+2349876543210,jane@example.com,XYZ Ltd,2025-05-21,10:00,Floor 5,Interview
CSV;

    return Response::make($csvContent, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="sample-visitors.csv"',
    ]);
})->name('visitors.sample-csv');

