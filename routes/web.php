<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    // Force password change
    Route::post('/force-password', [App\Http\Controllers\UserController::class, 'forcePasswordChange'])->name('force-password');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // System Setup
    Route::resource('academic-years', App\Http\Controllers\AcademicYearController::class)->except(['create', 'show', 'edit']);
    Route::resource('exam-types', App\Http\Controllers\ExamTypeController::class)->except(['create', 'show', 'edit']);
    Route::resource('regions', App\Http\Controllers\RegionController::class)->except(['create', 'show', 'edit']);
    Route::resource('districts', App\Http\Controllers\DistrictController::class)->except(['create', 'show', 'edit']);
    Route::resource('schools', App\Http\Controllers\SchoolController::class)->except(['create', 'show', 'edit']);
    Route::resource('subjects', App\Http\Controllers\SubjectController::class)->except(['create', 'show', 'edit']);
    Route::resource('classes', App\Http\Controllers\ClassController::class)->except(['create', 'show', 'edit']);
    Route::resource('streams', App\Http\Controllers\StreamController::class)->except(['create', 'show', 'edit']);

    // Examinations
    Route::resource('examinations', App\Http\Controllers\ExaminationController::class);
    Route::post('/examinations/{examination}/status/{status}', [App\Http\Controllers\ExaminationController::class, 'changeStatus'])->name('examinations.status');

    // Candidates
    Route::resource('candidates', App\Http\Controllers\CandidateController::class)->except(['create', 'show', 'edit']);
    Route::post('/candidates/import', [App\Http\Controllers\CandidateController::class, 'import'])->name('candidates.import');

    // Panels
    Route::resource('panels', App\Http\Controllers\PanelController::class);
    Route::post('/panels/{panel}/markers', [App\Http\Controllers\PanelController::class, 'addMarker'])->name('panels.markers.store');
    Route::delete('/panels/{panel}/markers/{marker}', [App\Http\Controllers\PanelController::class, 'removeMarker'])->name('panels.markers.destroy');
    Route::post('/panels/{panel}/data-entries', [App\Http\Controllers\PanelController::class, 'addDataEntry'])->name('panels.data-entries.store');
    Route::delete('/panels/{panel}/data-entries/{dataEntry}', [App\Http\Controllers\PanelController::class, 'removeDataEntry'])->name('panels.data-entries.destroy');

    // Assignments
    Route::resource('assignments', App\Http\Controllers\AssignmentController::class)->except(['create', 'show', 'edit']);

    // Marks Entry
    Route::get('/marks/entry', [App\Http\Controllers\MarkController::class, 'entry'])->name('marks.entry');
    Route::post('/marks/save', [App\Http\Controllers\MarkController::class, 'save'])->name('marks.save');
    Route::get('/marks/progress', [App\Http\Controllers\MarkController::class, 'myProgress'])->name('marks.progress');

    // Verification
    Route::get('/verification', [App\Http\Controllers\VerificationController::class, 'index'])->name('verification.index');
    Route::get('/verification/{panel}', [App\Http\Controllers\VerificationController::class, 'show'])->name('verification.show');
    Route::post('/verification/{mark}/approve', [App\Http\Controllers\VerificationController::class, 'approve'])->name('verification.approve');
    Route::post('/verification/{mark}/reject', [App\Http\Controllers\VerificationController::class, 'reject'])->name('verification.reject');
    Route::post('/verification/{panel}/bulk-approve', [App\Http\Controllers\VerificationController::class, 'bulkApprove'])->name('verification.bulk-approve');

    // Reports
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{examination}/overall', [App\Http\Controllers\ReportController::class, 'overall'])->name('reports.overall');
    Route::get('/reports/{examination}/school', [App\Http\Controllers\ReportController::class, 'bySchool'])->name('reports.school');
    Route::get('/reports/{examination}/subject', [App\Http\Controllers\ReportController::class, 'bySubject'])->name('reports.subject');
    Route::get('/reports/{examination}/district', [App\Http\Controllers\ReportController::class, 'byDistrict'])->name('reports.district');
    Route::get('/reports/{examination}/users', [App\Http\Controllers\ReportController::class, 'userPerformance'])->name('reports.users');

    // Users
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::post('/users/{user}/reset-password', [App\Http\Controllers\UserController::class, 'resetPassword'])->name('users.reset-password');

    // Audit Logs
    Route::get('/audit-logs', [App\Http\Controllers\AuditLogController::class, 'index'])->name('audit-logs.index');
});
