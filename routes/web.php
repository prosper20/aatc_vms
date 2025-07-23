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
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ReceptionGuestController;
use App\Http\Controllers\ReceptionHistoryController;

use App\Mail\VisitApprovedEmail;



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

    Route::prefix('staff')->middleware('auth:staff')->group(function() {
        // Dashboard
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');

        // Visit actions
        Route::post('/visits/invite', [StaffDashboardController::class, 'sendInvitation'])->name('staff.dashboard.invite');
        Route::get('/visits/{visit}/details', [StaffDashboardController::class, 'getVisitDetails'])->name('staff.visits.details');
        Route::post('/visits/{visit}/cancel', [StaffDashboardController::class, 'cancelVisit'])->name('staff.visits.cancel');
        Route::post('/visits/{visit}/resubmit', [StaffDashboardController::class, 'resubmitVisit'])->name('staff.visits.resubmit');
        Route::post('/visits/{visit}/resend-code', [StaffDashboardController::class, 'resendCode'])->name('staff.visits.resend-code');
        Route::post('/visits/edit', [StaffDashboardController::class, 'editVisit'])->name('staff.dashboard.edit');

        // Logout
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    });

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
    Route::get('/dashboard/guests', [ReceptionGuestController::class, 'index'])->name('dashboard.guests');
    Route::post('/search', [ReceptionDashboardController::class, 'search'])->name('search');
    Route::post('/check-in/{visit}', [ReceptionDashboardController::class, 'checkIn'])->name('checkin');
    Route::post('/check-out/{visit}', [ReceptionDashboardController::class, 'checkOut'])->name('checkout');

    Route::post('/visits/invite', [StaffDashboardController::class, 'sendInvitation'])->name('dashboard.invite');
    Route::get('/visits/{visit}/details', [StaffDashboardController::class, 'getVisitDetails'])->name('visits.details');
    Route::get('/visits/{visit}', [ReceptionGuestController::class, 'show'])->name('visits.show');
    Route::post('/visitor-history/export', [ReceptionHistoryController::class, 'export'])->name('history.export');
    // Route::get('/visitor-history/export', [VisitorHistoryController::class, 'export'])->name('visitor-history.export.get');

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
// Route::prefix('gate')->name('gate.')->group(function () {
//     Route::get('/scanner', [ScannerController::class, 'index'])->name('scanner.index');
//     Route::post('/scanner/verify', [ScannerController::class, 'verify'])->name('scanner.verify');
//     Route::post('/scanner/checkin', [ScannerController::class, 'checkin'])->name('scanner.checkin');
//     Route::post('/scanner/notify', [ScannerController::class, 'notify'])->name('scanner.notify');
//     Route::post('/scanner/search', [ScannerController::class, 'search'])->name('scanner.search');
// });
Route::prefix('gate')->name('gate.')->group(function () {
    // Scanner Interface
    Route::get('/scanner', [ScannerController::class, 'index'])->name('scanner.index');

    // QR Code Verification
    Route::post('/scanner/verify', [ScannerController::class, 'verify'])->name('scanner.verify');

    // Visitor Check-in
    Route::post('/scanner/checkin', [ScannerController::class, 'checkin'])->name('scanner.checkin');

    // Host Notification
    Route::post('/scanner/notify', [ScannerController::class, 'notify'])->name('scanner.notify');

    // Manual Search
    Route::post('/scanner/search', [ScannerController::class, 'search'])->name('scanner.search');

    // Vehicle Registration (if you want a separate endpoint)
    Route::post('/scanner/vehicle', [ScannerController::class, 'registerVehicle'])->name('scanner.vehicle');
});

// Security manager dashboard routes
Route::prefix('sm')->name('sm.')->group(function () {
    // Authentication Routes
    Route::get('/login', [SmController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [SmController::class, 'login'])->name('login.submit');
    // Protected Dashboard Routes
    Route::middleware(['auth:sm'])->group(function () {
        // Route::get('/dashboard', [SmController::class, 'dashboard'])->name('dashboard');
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

use App\Models\Visit;
Route::get('/test-email', function () {
    // Simulate a Visit model
    $visit = Visit::first(); // or create a dummy Visit model

    if (!$visit) {
        $visit = new Visit();
        $visit->visitor_name = 'John Doe';
        $visit->visit_date = now()->format('Y-m-d');
        $visit->unique_code = 'ABC123';
    }

    // Send email to your email for testing
    Mail::to('bobsonconnect@gmail.com')->send(new VisitApprovedEmail($visit, "QR CODE SVG"));

    return 'Test email sent!';
});

// Route::post('/send-email', [EmailController::class, 'send'])->name('send.email');
