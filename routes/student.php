<?php

use App\Http\Controllers\Admin\Consultation\ReviewController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ServiceOrderController;
use App\Http\Controllers\Admin\ServiceOrderInvoiceController;
use App\Http\Controllers\Admin\ServiceOrderTaskBoardController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Student\CheckoutController;
use App\Http\Controllers\Student\ConsultationController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\EventController;
use App\Http\Controllers\Student\MyCourseController;
use App\Http\Controllers\Student\OnboardingController;
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
    Route::get('/2fa-setting', [ProfileController::class, 'google2faSetting'])->name('2fa');
    Route::post('update', [ProfileController::class, 'update'])->name('update');
    Route::get('password', [ProfileController::class, 'password'])->name('password');
    Route::post('password-update', [ProfileController::class, 'passwordUpdate'])->name('password.update');
});

Route::group(['prefix' => 'event', 'as' => 'event.'], function () {
    Route::get('/', [EventController::class, 'list'])->name('list');
    Route::get('details/{id}', [EventController::class, 'details'])->name('details');
});

Route::group(['prefix' => 'services', 'as' => 'services.'], function () {
    Route::get('/', [ServiceController::class, 'index'])->name('index');
    Route::get('details/{id}', [ServiceController::class, 'details'])->name('details');
});

Route::group(['prefix' => 'consultation-appointment', 'as' => 'consultation-appointment.'], function () {
    Route::get('/', [ConsultationController::class, 'list'])->name('list');
    Route::get('details/{id}', [ConsultationController::class, 'details'])->name('details');
    Route::get('review/{id}', [ConsultationController::class, 'review'])->name('review');
    Route::post('review', [ConsultationController::class, 'reviewStore'])->name('review-store');
});

Route::get('review', [ReviewController::class, 'list'])->name('review');

Route::get('checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::post('checkout-pay', [CheckoutController::class, 'pay'])->name('checkout-pay');
Route::get('checkout-response', [CheckoutController::class, 'successOrFail'])->name('checkout.success');

Route::get('transactions', [TransactionController::class, 'index'])->name('transactions');
Route::get('transactions/details/{id}', [TransactionController::class, 'invoiceDetails'])->name('transactions.details');
Route::get('transactions/print/{id}', [TransactionController::class, 'invoicePrint'])->name('transactions.print');

Route::group(['prefix' => 'my-courses', 'as' => 'my_courses.'], function () {
    Route::get('', [MyCourseController::class, 'list'])->name('list');
    Route::get('view/{enrollment_id}', [MyCourseController::class, 'view'])->name('view');
});

Route::get('service-orders', [ServiceOrderController::class, 'index'])->name('service_orders');
Route::get('service-orders-documents/{service_order_id}', [ServiceOrderController::class, 'documents'])->name('service_orders.documents');
Route::get('service-orders-onboarding/{service_order_id}', [OnboardingController::class, 'onboarding'])->name('service_orders.onboarding');
Route::get('onboarding/edit-modal/{service_order_id}/{section}/{id?}', [OnboardingController::class, 'editModal'])->name('service_orders.onboarding.edit-modal');
Route::post('onboarding/update/{service_order_id}/{section}/{id?}', [OnboardingController::class, 'update'])->name('service_orders.onboarding.update');
Route::post('onboarding/delete/{service_order_id}/{section}/{id}', [OnboardingController::class, 'delete'])->name('service_orders.onboarding.delete');
Route::post('onboarding/finish/{service_order_id}', [OnboardingController::class, 'onboardFinish'])->name('service_orders.onboarding.finish');

Route::get('service-invoices', [ServiceOrderInvoiceController::class, 'index'])->name('service_invoices.index');
Route::get('get-service-order-{student_id}', [ServiceOrderInvoiceController::class, 'getServiceOrder'])->name('get_service_order');
Route::get('service-invoice-details/{id}', [ServiceOrderInvoiceController::class, 'invoiceDetails'])->name('service_invoices.details');
Route::get('service-invoice-print/{id}', [ServiceOrderInvoiceController::class, 'invoicePrint'])->name('service_invoices.print');

Route::group(['prefix' => 'service-order-task-board', 'as' => 'service_orders.task-board.'], function () {
    Route::get('/{order_id}', [ServiceOrderTaskBoardController::class, 'list'])->name('index');
    Route::get('/{order_id}/view/{id}', [ServiceOrderTaskBoardController::class, 'view'])->name('view');
    Route::group(['prefix' => 'conversation', 'as' => 'conversation.'], function () {
        Route::post('{order_id}/{id}', [ServiceOrderTaskBoardController::class, 'conversationStore'])->name('store');
    });
});
