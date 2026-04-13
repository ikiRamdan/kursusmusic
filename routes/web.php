<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\Admin\UserController;
    use App\Http\Controllers\Admin\MentorController;
    use App\Http\Controllers\Admin\CourseController;
    use App\Http\Controllers\Admin\CoursePackageController;
    use App\Http\Controllers\Admin\RoomController;
    use App\Http\Controllers\Admin\SchedulesController;
        use App\Http\Controllers\Kasir\TransactionController;
            use App\Http\Controllers\Owner\OwnerController;
            use App\Http\Controllers\Owner\ReportController;
/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect('/login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'loginWeb']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (AUTH + ROLE)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // ADMIN
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:admin')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');

            // contoh tambahan (biar cocok sama sidebar kamu)
            Route::resource('/users', UserController::class)->except(['show']);
            Route::resource('/mentors', MentorController::class)->except(['show']);
            Route::resource('/courses', CourseController::class)->except(['show']);
            Route::resource('/course-packages', CoursePackageController::class)->except(['show']);
            Route::resource('/rooms', RoomController::class)->except(['show']);
            Route::resource('/schedules', SchedulesController::class)->except(['show']);
            Route::get('/get-mentors/{courseId}', [SchedulesController::class, 'getMentors'])->name('get-mentors');
        });



Route::prefix('kasir')
    ->name('kasir.')
    ->middleware('role:kasir')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // ROUTE BARU DATA PELANGGAN
        Route::get('/pelanggan', [TransactionController::class, 'pelanggan'])->name('pelanggan');

        Route::resource('/transactions', TransactionController::class);
        Route::post('/transactions/{id}/pay', [TransactionController::class, 'payRemaining'])->name('transactions.pay');
    });

    // OWNER
   Route::prefix('owner')
    ->name('owner.')
    ->middleware('role:owner')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Laporan & Cetak PDF
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/print', [ReportController::class, 'printPdf'])->name('reports.print');

        // Data Read-Only (Hanya Lihat)
        Route::get('/courses', [OwnerController::class, 'courses'])->name('courses.index');
        Route::get('/packages', [OwnerController::class, 'packages'])->name('packages.index');

        // Log Aktivitas
        Route::get('/logs', [OwnerController::class, 'logs'])->name('logs.index');
    });

});