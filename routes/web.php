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
use App\Http\Controllers\ReceptionAuthController;
use App\Http\Controllers\ReceptionDashboardController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\SmController;
use App\Http\Controllers\SmDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\VisitController;
use App\Exports\VisitsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\VisitorHistoryController;
use App\Http\Controllers\PendingVisitsController;




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

Route::get('/modal', [ModalController::class, 'index'])->name('modal');
Route::post('/modal-data', [ModalController::class, 'getModalData'])->name('modal.data');
Route::post('/visitor-lookup', [ModalController::class, 'visitorLookup'])->name('visitor.lookup');
Route::middleware(['auth:staff'])->group(function () {
    Route::post('/submit-visitors', [ModalController::class, 'storeVisitors'])
         ->name('store.visitors');
});
Route::post('/upload-visitors-csv', [ModalController::class, 'uploadCSV'])
    ->middleware('auth:staff')
    ->name('upload.visitors.csv');


// Reception Staff Routes
Route::prefix('reception')->name('reception.')->group(function () {
    // Authentication Routes
    Route::get('/login', [ReceptionAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [ReceptionAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [ReceptionAuthController::class, 'logout'])->name('logout');
    // Route::get('/dashboard', [ReceptionDashboardController::class, 'index'])->name('dashboard');

    // Protected Dashboard Route
    Route::middleware(['auth:receptionist'])->group(function () {
        Route::get('/dashboard', [ReceptionDashboardController::class, 'index'])->name('dashboard');
    });
    Route::post('/search', [ReceptionDashboardController::class, 'search'])->name('search');
    Route::post('/check-in/{visit}', [ReceptionDashboardController::class, 'checkIn'])->name('checkin');
    Route::post('/check-out/{visit}', [ReceptionDashboardController::class, 'checkOut'])->name('checkout');
});

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

// Gate routes
Route::prefix('gate')->name('gate.')->group(function () {
    Route::get('/scanner', [ScannerController::class, 'index'])->name('scanner.index');
    Route::post('/scanner/verify', [ScannerController::class, 'verify'])->name('scanner.verify');
    Route::post('/scanner/checkin', [ScannerController::class, 'checkin'])->name('scanner.checkin');
    Route::post('/scanner/notify', [ScannerController::class, 'notify'])->name('scanner.notify');
    Route::post('/scanner/search', [ScannerController::class, 'search'])->name('scanner.search');
});

// Security manager dashboard routes
Route::prefix('sm')->name('sm.')->group(function () {
    // Authentication Routes
    Route::get('/login', [SmController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [SmController::class, 'login'])->name('login.submit');
    // Protected Dashboard Routes
    Route::middleware(['auth:sm'])->group(function () {
        Route::get('/dashboard', [SmController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [SmController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::post('/visits/{visit}/approve', [VisitController::class, 'approve'])->name('visits.approve');
        Route::post('/visits/{visit}/deny', [VisitController::class, 'deny'])->name('visits.deny');
        Route::get('/visits/pending', [VisitController::class, 'pending'])->name('visits.pending');

        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
        Route::get('/analytics/export', function () {
            return Excel::download(new VisitsExport, 'visitors_report.xlsx');
        });
        Route::get('/analytics/recent-activities', [AnalyticsController::class, 'getRecentActivitiesAjax'])->name('analytics.recent-activities');

        Route::get('/visitor-history', [VisitorHistoryController::class, 'index'])->name('visitor-history');
        Route::get('/visitor-history/{visit}', [VisitorHistoryController::class, 'show'])->name('visitor-history.show');
        Route::get('/visitor-history/export', [VisitorHistoryController::class, 'export'])->name('visitor-history.export');

        Route::get('/pending-visits', [PendingVisitsController::class, 'index'])->name('pending-visits');
        Route::get('/pending-visits/export', [PendingVisitsController::class, 'export'])->name('pending-visits.export');
    });
});
// Route::prefix('sm')->name('sm.')->group(function () {
//     // Authentication Routes
//     Route::get('/login', [SmController::class, 'showLoginForm'])->name('sm.login');
//     Route::post('/login', [SmController::class, 'login'])->name('sm.login.submit');
//     // Protected Dashboard Routes
//     Route::middleware(['auth:sm'])->group(function () {
//         Route::get('/dashboard', [SmController::class, 'dashboard'])->name('sm.dashboard');
//         Route::post('/logout', [SmController::class, 'logout'])->name('sm.logout');
//         Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//         Route::post('/visits/{visit}/approve', [VisitController::class, 'approve'])->name('visits.approve');
//         Route::post('/visits/{visit}/deny', [VisitController::class, 'deny'])->name('visits.deny');
//         Route::get('/visits/pending', [VisitController::class, 'pending'])->name('visits.pending');
//         Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
//         Route::get('/analytics/export', function () {
//             return Excel::download(new VisitsExport, 'visitors_report.xlsx');
//         });
//     });
// });


