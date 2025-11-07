<?php

use App\Http\Controllers\Frontend\AboutUsController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\ConsultationController;
use App\Http\Controllers\Frontend\CountryController;
use App\Http\Controllers\Frontend\CourseController;
use App\Http\Controllers\Frontend\EventController;
use App\Http\Controllers\Frontend\FreeConsultationController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ScholarshipController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\Frontend\SubjectController;
use App\Http\Controllers\Frontend\UniversityController;
use App\Http\Controllers\Student\CheckoutController;
use App\Models\Language;
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

Route::get('/local/{ln}', function ($ln) {
    $language = Language::where('iso_code', $ln)->first();
    if (!$language) {
        $language = Language::where('default', 1)->first();
        if ($language) {
            $ln = $language->iso_code;
        }
    }
    session()->put('local', $ln);
    return redirect()->back();
})->name('local');

Route::get('', [HomeController::class, 'index'])->name('frontend');
Route::get('/universities/{country_id?}', [HomeController::class, 'getUniversitiesByCountry'])->name('universities.byCountry');
Route::get('universities-list', [UniversityController::class, 'list'])->name('universities.list');
Route::get('universities-{slug}', [UniversityController::class, 'details'])->name('universities.details');
Route::get('page-{slug}', [HomeController::class, 'page'])->name('page');
Route::get('contact-us', [HomeController::class, 'contactUs'])->name('contact-us');
Route::post('contact-us', [HomeController::class, 'contactUsStore'])->name('contact-us.store');
Route::get('courses-programs-{slug}', [CourseController::class, 'courseProgram'])->name('courses.program');
Route::get('courses-{slug}', [CourseController::class, 'course'])->name('courses.single');
Route::get('courses/{slug}', [CourseController::class, 'video'])->name('courses.video');
Route::get('scholarship-list', [ScholarshipController::class, 'list'])->name('scholarship.list');
Route::get('scholarship-{slug}', [ScholarshipController::class, 'details'])->name('scholarship.details');
Route::get('subject-list', [SubjectController::class, 'list'])->name('subject.list');
Route::get('subject-{slug}', [SubjectController::class, 'details'])->name('subject.details');
Route::get('blog-list', [BlogController::class, 'list'])->name('blog.list');
Route::get('blog-{slug}', [BlogController::class, 'details'])->name('blog.details');
Route::get('about-us-details', [AboutUsController::class, 'details'])->name('about-us.details');
Route::get('event-list', [EventController::class, 'list'])->name('event.list');
Route::get('event-{slug}', [EventController::class, 'details'])->name('event.details');
Route::get('service-{slug}', [ServiceController::class, 'details'])->name('service.details');
Route::get('country-{slug}', [CountryController::class, 'details'])->name('country.details');
Route::post('free-consultation', [FreeConsultationController::class, 'store'])->name('free_consultation');
Route::get('consultations', [ConsultationController::class, 'list'])->name('consultations.list');
Route::get('consultations-{id}', [ConsultationController::class, 'details'])->name('consultations.details');
Route::get('consultation-bookings-{id}', [ConsultationController::class, 'booking'])->name('consultations.booking');
Route::post('consultation-bookings-{id}', [ConsultationController::class, 'bookingValidation'])->name('consultations.booking.validation');
Route::post('consultation-slots-{id}', [ConsultationController::class, 'bookingSlot'])->name('consultations.booking.slot');
Route::get('gateway-currency', [CheckoutController::class, 'getCurrencyByGateway'])->name('gateway-currency');

Route::get('get-university-list', [HomeController::class, 'getUniversityByCountry'])->name('get_universities');
Route::get('get-subject-list', [HomeController::class, 'getSubjectByUniversity'])->name('get_subjects');
