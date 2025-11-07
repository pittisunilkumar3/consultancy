<?php

use App\Http\Controllers\Admin\Consultation\AppointmentController;
use App\Http\Controllers\Admin\Consultation\ConsulterController;
use App\Http\Controllers\Admin\Consultation\ReviewController;
use App\Http\Controllers\Admin\Consultation\StudentController;
use App\Http\Controllers\Consulter\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::group(['prefix' => 'notification', 'as' => 'notification.'], function () {
    Route::get('all-notification', [NotificationController::class, 'allNotification'])->name('all');
    Route::get('view/{id}', [NotificationController::class, 'notificationView'])->name('view');
    Route::get('notification-mark-as-read/{id}', [NotificationController::class, 'notificationMarkAsRead'])->name('notification-mark-as-read');
});

Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::post('update', [ProfileController::class, 'update'])->name('update');
    Route::get('password', [ProfileController::class, 'password'])->name('password');
    Route::post('password-update', [ProfileController::class, 'passwordUpdate'])->name('password.update');
});

Route::get('consultant-profile', [ConsulterController::class, 'profile'])->name('consultant_profile');
Route::post('consultant-profile', [ConsulterController::class, 'store'])->name('consultant_profile.update');

Route::get('consultant-student', [StudentController::class, 'list'])->name('student.list');

Route::group(['prefix' => 'review', 'as' => 'consultations.review.'], function () {

    Route::get('', [ReviewController::class, 'list'])->name('list');
    Route::get('status-change-modal/{id}', [ReviewController::class, 'statusChangeModal'])->name('status-change-modal');
    Route::post('update-status', [ReviewController::class, 'updateStatus'])->name('update-status');
    Route::post('delete/{id}', [ReviewController::class, 'delete'])->name('delete');

});

Route::group(['prefix' => 'appointments', 'as' => 'consultations.appointments.'], function () {
    Route::get('', [AppointmentController::class, 'index'])->name('index');
    Route::get('view/{id}', [AppointmentController::class, 'view'])->name('view');
    Route::get('status-change-modal/{id}', [AppointmentController::class, 'statusChangeModal'])->name('status_change_modal');
    Route::post('status-change/{id}', [AppointmentController::class, 'statusChange'])->name('status_change');
});

