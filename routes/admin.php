<?php

use App\Http\Controllers\Admin\CertificationAndDegreeController;
use App\Http\Controllers\Admin\Cms\AboutUsController;
use App\Http\Controllers\Admin\Cms\BlogCategoriesController;
use App\Http\Controllers\Admin\Cms\BlogController;
use App\Http\Controllers\Admin\Cms\BlogTagController;
use App\Http\Controllers\Admin\Cms\CmsController;
use App\Http\Controllers\Admin\FormStructureController;
use App\Http\Controllers\Admin\Cms\CountryController;
use App\Http\Controllers\Admin\Cms\FaqController;
use App\Http\Controllers\Admin\Cms\ScholarshipController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\Cms\TestimonialController;
use App\Http\Controllers\Admin\Cms\UniversityController;
use App\Http\Controllers\Admin\Consultation\AppointmentController;
use App\Http\Controllers\Admin\Consultation\ConsultationSlotController;
use App\Http\Controllers\Admin\Consultation\ConsulterController;
use App\Http\Controllers\Admin\Consultation\FreeConsultationController;
use App\Http\Controllers\Admin\Consultation\GoogleMeetController;
use App\Http\Controllers\Admin\Consultation\MeetingPlatformController;
use App\Http\Controllers\Admin\Consultation\ReviewController;
use App\Http\Controllers\Admin\Course\CourseController;
use App\Http\Controllers\Admin\Course\CourseLectureController;
use App\Http\Controllers\Admin\Course\CourseLessonController;
use App\Http\Controllers\Admin\Course\CourseResourceController;
use App\Http\Controllers\Admin\Course\ProgramController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\GatewayController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\LanguageProficiencyController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OnboardingFormSettingController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\UniversityCriteriaFieldController;
use App\Http\Controllers\Admin\CareerCornerSubmissionController;
use App\Http\Controllers\Admin\ServiceOrderController;
use App\Http\Controllers\Admin\ServiceOrderInvoiceController;
use App\Http\Controllers\Admin\ServiceOrderTaskBoardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\Staffs\RoleController;
use App\Http\Controllers\Admin\Staffs\StaffController;
use App\Http\Controllers\Admin\Student\StudentController;
use App\Http\Controllers\Admin\StudyLevelController;
use App\Http\Controllers\Admin\SubjectCategoriesController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Student\OnboardingController;
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

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::get('/2fa-setting', [ProfileController::class, 'google2faSetting'])->name('2fa');
    Route::post('update', [ProfileController::class, 'update'])->name('update');
    Route::get('password', [ProfileController::class, 'password'])->name('password');
    Route::post('password-update', [ProfileController::class, 'passwordUpdate'])->name('password.update');
});

Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
    Route::group(['middleware' => []], function () {
        Route::get('application-settings', [SettingController::class, 'applicationSetting'])->name('application-settings')->middleware('can:Manage Setting');
        Route::get('configuration-settings', [SettingController::class, 'configurationSetting'])->name('configuration-settings')->middleware('can:Manage Setting');
        Route::get('configuration-settings/configure', [SettingController::class, 'configurationSettingConfigure'])->name('configuration-settings.configure')->middleware('can:Manage Setting');
        Route::get('configuration-settings/help', [SettingController::class, 'configurationSettingHelp'])->name('configuration-settings.help')->middleware('can:Manage Setting');
        Route::post('application-settings-update', [SettingController::class, 'applicationSettingUpdate'])->name('application-settings.update')->middleware('can:Manage Setting');
        Route::post('configuration-settings-update', [SettingController::class, 'configurationSettingUpdate'])->name('configuration-settings.update')->middleware('can:Manage Setting');
        Route::post('application-env-update', [SettingController::class, 'saveSetting'])->name('settings_env.update')->middleware('can:Manage Setting');
        Route::get('logo-settings', [SettingController::class, 'logoSettings'])->name('logo-settings')->middleware('can:Manage Setting');
        Route::get('color-settings', [SettingController::class, 'colorSettings'])->name('color-settings')->middleware('can:Manage Setting');
        Route::get('auth-page-settings', [SettingController::class, 'authPageSettings'])->name('auth-page-settings')->middleware('can:Manage Setting');
        Route::get('color-settings', [SettingController::class, 'colorSettings'])->name('color-settings')->middleware('can:Manage Setting');
        Route::get('registration-form-settings', [SettingController::class, 'registrationFormSetting'])->name('registration-form-settings')->middleware('can:Manage Setting');
        Route::post('registration-form-settings', [SettingController::class, 'registrationFormSettingStore'])->name('registration-form-setting-store')->middleware('can:Manage Setting');

        Route::group(['prefix' => 'currency', 'as' => 'currencies.'], function () {
            Route::get('', [CurrencyController::class, 'index'])->name('index')->middleware('can:Manage Setting');
            Route::post('currency', [CurrencyController::class, 'store'])->name('store')->middleware('can:Manage Setting');
            Route::get('edit/{id}', [CurrencyController::class, 'edit'])->name('edit')->middleware('can:Manage Setting');
            Route::patch('update/{id}', [CurrencyController::class, 'update'])->name('update')->middleware('can:Manage Setting');
            Route::post('delete/{id}', [CurrencyController::class, 'delete'])->name('delete')->middleware('can:Manage Setting');
        });

        Route::get('storage-settings', [SettingController::class, 'storageSetting'])->name('storage.index')->middleware('can:Manage Setting');
        Route::post('storage-settings', [SettingController::class, 'storageSettingsUpdate'])->name('storage.update')->middleware('can:Manage Setting');
    });

    Route::get('mail-configuration', [SettingController::class, 'mailConfiguration'])->name('mail-configuration')->middleware('can:Manage Setting');
    Route::post('mail-configuration', [SettingController::class, 'mailConfiguration'])->name('mail-configuration')->middleware('can:Manage Setting');
    Route::post('mail-test', [SettingController::class, 'mailTest'])->name('mail.test')->middleware('can:Manage Setting');

    //Start:: Maintenance Mode
    Route::get('maintenance-mode-changes', [SettingController::class, 'maintenanceMode'])->name('maintenance')->middleware('can:Manage Setting');
    Route::post('maintenance-mode-changes', [SettingController::class, 'maintenanceModeChange'])->name('maintenance.change')->middleware('isDemo')->middleware('can:Manage Setting');
    //End:: Maintenance Mode

    Route::get('cache-settings', [SettingController::class, 'cacheSettings'])->name('cache-settings')->middleware('can:Manage Setting');
    Route::get('cache-update/{id}', [SettingController::class, 'cacheUpdate'])->name('cache-update')->middleware('can:Manage Setting');
    Route::get('storage-link', [SettingController::class, 'storageLink'])->name('storage.link')->middleware('can:Manage Setting');

    Route::group(['prefix' => 'gateway', 'as' => 'gateway.'], function () {
        Route::get('/', [GatewayController::class, 'index'])->name('index')->middleware('can:Manage Setting');
        Route::get('edit/{id}', [GatewayController::class, 'edit'])->name('edit')->middleware('can:Manage Setting');
        Route::post('store', [GatewayController::class, 'store'])->name('store')->middleware('isDemo')->middleware('can:Manage Setting');
        Route::get('get-info', [GatewayController::class, 'getInfo'])->name('get.info')->middleware('can:Manage Setting');
        Route::get('get-currency-by-gateway', [GatewayController::class, 'getCurrencyByGateway'])->name('get.currency')->middleware('can:Manage Setting');
        Route::get('syncs', [GatewayController::class, 'syncs'])->name('syncs')->middleware('can:Manage Setting');
    });

    Route::get('email-template', [EmailTemplateController::class, 'emailTemplate'])->name('email-template')->middleware('can:Manage Setting');
    Route::get('email-edit/{id}', [EmailTemplateController::class, 'emailTempEdit'])->name('email-template-edit')->middleware('can:Manage Setting');
    Route::post('email-temp-update', [EmailTemplateController::class, 'emailTempUpdate'])->name('email-temp-update')->middleware('can:Manage Setting');

    Route::group(['prefix' => 'language', 'as' => 'languages.'], function () {
        Route::get('/', [LanguageController::class, 'index'])->name('index')->middleware('can:Manage Setting');
        Route::post('store', [LanguageController::class, 'store'])->name('store')->middleware('can:Manage Setting');
        Route::get('edit/{id}/{iso_code?}', [LanguageController::class, 'edit'])->name('edit')->middleware('can:Manage Setting');
        Route::post('update/{id}', [LanguageController::class, 'update'])->name('update')->middleware('can:Manage Setting');
        Route::get('translate/{id}', [LanguageController::class, 'translateLanguage'])->name('translate')->middleware('can:Manage Setting');
        Route::post('update-translate/{id}', [LanguageController::class, 'updateTranslate'])->name('update.translate')->middleware('can:Manage Setting');
        Route::post('delete/{id}', [LanguageController::class, 'delete'])->name('delete')->middleware('can:Manage Setting');
        Route::post('update-language/{id}', [LanguageController::class, 'updateLanguage'])->name('update-language')->middleware('can:Manage Setting');
        Route::get('translate/{id}/{iso_code?}', [LanguageController::class, 'translateLanguage'])->name('translate')->middleware('can:Manage Setting');
        Route::get('update-translate/{id}', [LanguageController::class, 'updateTranslate'])->name('update.translate')->middleware('can:Manage Setting');
        Route::post('import', [LanguageController::class, 'import'])->name('import')->middleware('isDemo')->middleware('can:Manage Setting');
    });

    // designation
    Route::group(['prefix' => 'designation', 'as' => 'designation.'], function () {
        Route::get('/', [DesignationController::class, 'index'])->name('index')->middleware('can:Manage Setting');
        Route::post('store', [DesignationController::class, 'store'])->name('store')->middleware('can:Manage Setting');
        Route::get('edit/{id}', [DesignationController::class, 'edit'])->name('edit')->middleware('can:Manage Setting');
        Route::get('delete/{id}', [DesignationController::class, 'delete'])->name('delete')->middleware('can:Manage Setting');
    });

    Route::group(['prefix' => 'certificate-degrees', 'as' => 'certificate_degrees.'], function () {
        Route::get('/', [CertificationAndDegreeController::class, 'index'])->name('index')->middleware('can:Manage Setting');
        Route::post('store', [CertificationAndDegreeController::class, 'store'])->name('store')->middleware('can:Manage Setting');
        Route::get('edit/{id}', [CertificationAndDegreeController::class, 'edit'])->name('edit')->middleware('can:Manage Setting');
        Route::post('delete/{id}', [CertificationAndDegreeController::class, 'delete'])->name('delete')->middleware('can:Manage Setting');
    });

    Route::group(['prefix' => 'language-proficiencies', 'as' => 'language_proficiencies.'], function () {
        Route::get('/', [LanguageProficiencyController::class, 'index'])->name('index')->middleware('can:Manage Setting');
        Route::post('store', [LanguageProficiencyController::class, 'store'])->name('store')->middleware('can:Manage Setting');
        Route::get('edit/{id}', [LanguageProficiencyController::class, 'edit'])->name('edit')->middleware('can:Manage Setting');
        Route::post('delete/{id}', [LanguageProficiencyController::class, 'delete'])->name('delete')->middleware('can:Manage Setting');
    });

    Route::get('onboarding-form-settings', [OnboardingFormSettingController::class, 'index'])->name('onboarding_form_settings')->middleware('can:Manage Setting');
    Route::post('onboarding-form-settings', [OnboardingFormSettingController::class, 'update'])->name('onboarding_form_setting_update')->middleware('can:Manage Setting');
    Route::post('onboarding-custom-field-settings', [OnboardingFormSettingController::class, 'customField'])->name('onboarding_form_setting_custom_field')->middleware('can:Manage Setting');
});

Route::group(['prefix' => 'staffs', 'as' => 'staffs.'], function () {
    Route::get('', [StaffController::class, 'index'])->name('index')->middleware('can:Manage Staffs');
    Route::post('store', [StaffController::class, 'store'])->name('store')->middleware('can:Manage Staffs');
    Route::get('edit/{id}', [StaffController::class, 'edit'])->name('edit')->middleware('can:Manage Staffs');
    Route::patch('update/{id}', [StaffController::class, 'update'])->name('update')->middleware('can:Manage Staffs');
    Route::post('delete/{id}', [StaffController::class, 'delete'])->name('delete')->middleware('can:Manage Staffs');

    Route::group(['prefix' => 'roles', 'as' => 'roles.'], function () {
        Route::get('', [RoleController::class, 'index'])->name('index')->middleware('can:Manage Staffs');
        Route::post('store', [RoleController::class, 'store'])->name('store')->middleware('can:Manage Staffs');
        Route::get('edit/{id}', [RoleController::class, 'edit'])->name('edit')->middleware('can:Manage Staffs');
        Route::patch('update/{id}', [RoleController::class, 'update'])->name('update')->middleware('can:Manage Staffs');
        Route::post('delete/{id}', [RoleController::class, 'delete'])->name('delete')->middleware('can:Manage Staffs');
    });
});

Route::group(['prefix' => 'events', 'as' => 'events.'], function () {
    Route::get('', [EventController::class, 'index'])->name('index')->middleware('can:Manage Events');
    Route::post('store', [EventController::class, 'store'])->name('store')->middleware('can:Manage Events');
    Route::get('edit/{id}', [EventController::class, 'edit'])->name('edit')->middleware('can:Manage Events');
    Route::get('view/{slug}', [EventController::class, 'view'])->name('view')->middleware('can:Manage Events');
    Route::patch('update/{id}', [EventController::class, 'update'])->name('update')->middleware('can:Manage Events');
    Route::post('delete/{id}', [EventController::class, 'delete'])->name('delete')->middleware('can:Manage Events');
});

//consultations
Route::group(['prefix' => 'consultations', 'as' => 'consultations.'], function () {
    Route::get('', [ConsulterController::class, 'index'])->name('index')->middleware('can:Manage Consultations');
    Route::post('store', [ConsulterController::class, 'store'])->name('store')->middleware('can:Manage Consultations');
    Route::get('edit/{id}', [ConsulterController::class, 'edit'])->name('edit')->middleware('can:Manage Consultations');
    Route::post('delete/{id}', [ConsulterController::class, 'delete'])->name('delete')->middleware('can:Manage Consultations');

    Route::group(['prefix' => 'meeting-platforms', 'as' => 'meeting_platforms.'], function () {
        Route::get('', [MeetingPlatformController::class, 'index'])->name('index')->middleware('can:Manage Consultations');
        Route::get('edit/{id}', [MeetingPlatformController::class, 'edit'])->name('edit')->middleware('can:Manage Consultations');
        Route::post('update/{id}', [MeetingPlatformController::class, 'update'])->name('update')->middleware('can:Manage Consultations');
        Route::get('google-meet-callback', [GoogleMeetController::class, 'googleMeetCallback'])->name('google_meet_callback')->middleware('can:Manage Consultations');
    });

    Route::group(['prefix' => 'slots', 'as' => 'slots.'], function () {
        Route::get('', [ConsultationSlotController::class, 'index'])->name('index')->middleware('can:Manage Consultations');
        Route::post('store', [ConsultationSlotController::class, 'store'])->name('store')->middleware('can:Manage Consultations');
        Route::get('edit/{id}', [ConsultationSlotController::class, 'edit'])->name('edit')->middleware('can:Manage Consultations');
        Route::post('delete/{id}', [ConsultationSlotController::class, 'delete'])->name('delete')->middleware('can:Manage Consultations');
    });

    Route::group(['prefix' => 'appointments', 'as' => 'appointments.'], function () {
        Route::get('', [AppointmentController::class, 'index'])->name('index')->middleware('can:Manage Consultations');
        Route::get('create', [AppointmentController::class, 'create'])->name('create')->middleware('can:Manage Consultations');
        Route::post('store', [AppointmentController::class, 'store'])->name('store')->middleware('can:Manage Consultations');
        Route::post('update/{id}', [AppointmentController::class, 'update'])->name('update')->middleware('can:Manage Consultations');
        Route::get('edit/{id}', [AppointmentController::class, 'edit'])->name('edit')->middleware('can:Manage Consultations');
        Route::get('view/{id}', [AppointmentController::class, 'view'])->name('view')->middleware('can:Manage Consultations');
        Route::post('delete/{id}', [AppointmentController::class, 'delete'])->name('delete')->middleware('can:Manage Consultations');
        Route::get('status-change-modal/{id}', [AppointmentController::class, 'statusChangeModal'])->name('status_change_modal')->middleware('can:Manage Consultations');
        Route::post('status-change/{id}', [AppointmentController::class, 'statusChange'])->name('status_change')->middleware('can:Manage Consultations');
    });

    Route::group(['prefix' => 'review', 'as' => 'review.'], function () {
        Route::get('', [ReviewController::class, 'list'])->name('list')->middleware('can:Manage Consultations');
        Route::get('status-change-modal/{id}', [ReviewController::class, 'statusChangeModal'])->name('status-change-modal')->middleware('can:Manage Consultations');
        Route::post('update-status', [ReviewController::class, 'updateStatus'])->name('update-status')->middleware('can:Manage Consultations');
        Route::post('delete/{id}', [ReviewController::class, 'delete'])->name('delete')->middleware('can:Manage Consultations');
    });
});


//courses
Route::group(['prefix' => 'courses', 'as' => 'courses.'], function () {
    Route::get('', [CourseController::class, 'index'])->name('index')->middleware('can:Manage Courses');
    Route::get('/create', [CourseController::class, 'create'])->name('create')->middleware('can:Manage Courses');
    Route::post('store', [CourseController::class, 'store'])->name('store')->middleware('can:Manage Courses');
    Route::get('edit/{id}', [CourseController::class, 'edit'])->name('edit')->middleware('can:Manage Courses');
    Route::post('delete/{id}', [CourseController::class, 'delete'])->name('delete')->middleware('can:Manage Courses');
    Route::get('enrolls/{id}', [CourseController::class, 'enrolls'])->name('enrolls')->middleware('can:Manage Courses');
    Route::post('enrolls/revoke/{enrollment_id}', [CourseController::class, 'enrollRevoke'])->name('enrolls.revoke')->middleware('can:Manage Courses');
    Route::post('enroll-user/{id}', [CourseController::class, 'enrollUser'])->name('enroll_user')->middleware('can:Manage Courses');

    Route::group(['prefix' => 'programs', 'as' => 'programs.'], function () {
        Route::get('', [ProgramController::class, 'index'])->name('index')->middleware('can:Manage Courses');
        Route::post('store', [ProgramController::class, 'store'])->name('store')->middleware('can:Manage Courses');
        Route::get('edit/{id}', [ProgramController::class, 'edit'])->name('edit')->middleware('can:Manage Courses');
        Route::post('delete/{id}', [ProgramController::class, 'delete'])->name('delete')->middleware('can:Manage Courses');
    });

    Route::group(['prefix' => '{course_id}/lessons', 'as' => 'lessons.'], function () {
        Route::get('', [CourseLessonController::class, 'index'])->name('index')->middleware('can:Manage Courses');
        Route::post('store', [CourseLessonController::class, 'store'])->name('store')->middleware('can:Manage Courses');
        Route::get('edit/{id}', [CourseLessonController::class, 'edit'])->name('edit')->middleware('can:Manage Courses');
        Route::post('delete/{id}', [CourseLessonController::class, 'delete'])->name('delete')->middleware('can:Manage Courses');
    });

    Route::group(['prefix' => '{course_id}-{lesson_id}/lectures', 'as' => 'lectures.'], function () {
        Route::post('store', [CourseLectureController::class, 'store'])->name('store')->middleware('can:Manage Courses');
        Route::post('delete/{id}', [CourseLectureController::class, 'delete'])->name('delete')->middleware('can:Manage Courses');
    });

    Route::group(['prefix' => '{course_id}-{lesson_id}-{lecture_id}/resources', 'as' => 'resources.'], function () {
        Route::post('store', [CourseResourceController::class, 'store'])->name('store')->middleware('can:Manage Courses');
        Route::post('delete/{id}', [CourseResourceController::class, 'delete'])->name('delete')->middleware('can:Manage Courses');
        Route::get('view/{id}', [CourseResourceController::class, 'view'])->name('view')->middleware('can:Manage Courses');
    });
});

Route::group(['prefix' => 'study-levels', 'as' => 'study_levels.'], function () {
    Route::get('', [StudyLevelController::class, 'index'])->name('index')->middleware('can:Manage Cms Settings');
    Route::post('store', [StudyLevelController::class, 'store'])->name('store')->middleware('can:Manage Cms Settings');
    Route::get('edit/{id}', [StudyLevelController::class, 'edit'])->name('edit')->middleware('can:Manage Cms Settings');
    Route::post('delete/{id}', [StudyLevelController::class, 'delete'])->name('delete')->middleware('can:Manage Cms Settings');
});

Route::group(['prefix' => 'notification', 'as' => 'notification.'], function () {
    Route::get('all-notification', [NotificationController::class, 'allNotification'])->name('all');
    Route::get('view/{id}', [NotificationController::class, 'notificationView'])->name('view');
    Route::get('notification-mark-as-read/{id}', [NotificationController::class, 'notificationMarkAsRead'])->name('notification-mark-as-read')->middleware('can:Manage Notification');
});

Route::group(['prefix' => 'cms-setting', 'as' => 'cms-settings.'], function () {

    Route::get('section-settings', [CmsController::class, 'sectionSettings'])->name('section-settings')->middleware('can:Manage Cms Settings');
    Route::get('banner-settings', [CmsController::class, 'bannerSetting'])->name('banner-settings')->middleware('can:Manage Cms Settings');
    Route::get('our-service-section', [CmsController::class, 'ourService'])->name('our-service-section')->middleware('can:Manage Cms Settings');
    Route::get('why-choose-us', [CmsController::class, 'whyChooseUs'])->name('why-choose-us')->middleware('can:Manage Cms Settings');
    Route::get('how-we-work', [CmsController::class, 'howWeWork'])->name('how-we-work')->middleware('can:Manage Cms Settings');
    Route::get('cta-section', [CmsController::class, 'ctaSection'])->name('cta-section')->middleware('can:Manage Cms Settings');
    Route::get('page-section', [CmsController::class, 'pageSection'])->name('page-section')->middleware('can:Manage Cms Settings');
    Route::get('free-consultation', [CmsController::class, 'freeConsultant'])->name('free-consultant')->middleware('can:Manage Cms Settings');

    Route::group(['prefix' => 'faq', 'as' => 'faqs.'], function () {
        Route::get('', [FaqController::class, 'index'])->name('index')->middleware('can:Manage Cms Settings');
        Route::post('store', [FaqController::class, 'store'])->name('store')->middleware('can:Manage Cms Settings');
        Route::get('edit/{id}', [FaqController::class, 'edit'])->name('edit')->middleware('can:Manage Cms Settings');
        Route::post('delete/{id}', [FaqController::class, 'delete'])->name('delete')->middleware('can:Manage Cms Settings');
    });
    Route::group(['prefix' => 'testimonial', 'as' => 'testimonials.'], function () {
        Route::get('', [TestimonialController::class, 'index'])->name('index')->middleware('can:Manage Cms Settings');
        Route::post('store', [TestimonialController::class, 'store'])->name('store')->middleware('can:Manage Cms Settings');
        Route::get('edit/{id}', [TestimonialController::class, 'edit'])->name('edit')->middleware('can:Manage Cms Settings');
        Route::post('delete/{id}', [TestimonialController::class, 'delete'])->name('delete')->middleware('can:Manage Cms Settings');
    });
    Route::group(['prefix' => 'country', 'as' => 'countries.'], function () {
        Route::get('', [CountryController::class, 'index'])->name('index')->middleware('can:Manage Cms Settings');
        Route::get('create', [CountryController::class, 'create'])->name('create')->middleware('can:Manage Cms Settings');
        Route::post('store', [CountryController::class, 'store'])->name('store')->middleware('can:Manage Cms Settings');
        Route::get('edit/{id}', [CountryController::class, 'edit'])->name('edit')->middleware('can:Manage Cms Settings');
        Route::post('delete/{id}', [CountryController::class, 'delete'])->name('delete')->middleware('can:Manage Cms Settings');
    });
    Route::group(['prefix' => 'university', 'as' => 'universities.'], function () {
        Route::get('', [UniversityController::class, 'index'])->name('index')->middleware('can:Manage Cms Settings');
        Route::get('create', [UniversityController::class, 'create'])->name('create')->middleware('can:Manage Cms Settings');
        Route::post('store', [UniversityController::class, 'store'])->name('store')->middleware('can:Manage Cms Settings');
        Route::get('edit/{id}', [UniversityController::class, 'edit'])->name('edit')->middleware('can:Manage Cms Settings');
        Route::post('delete/{id}', [UniversityController::class, 'delete'])->name('delete')->middleware('can:Manage Cms Settings');
    });
    Route::group(['prefix' => 'scholarship', 'as' => 'scholarships.'], function () {
        Route::get('', [ScholarshipController::class, 'index'])->name('index')->middleware('can:Manage Cms Settings');
        Route::post('store', [ScholarshipController::class, 'store'])->name('store')->middleware('can:Manage Cms Settings');
        Route::get('edit/{id}', [ScholarshipController::class, 'edit'])->name('edit')->middleware('can:Manage Cms Settings');
        Route::post('delete/{id}', [ScholarshipController::class, 'delete'])->name('delete')->middleware('can:Manage Cms Settings');
    });
    Route::group(['prefix' => 'about-us', 'as' => 'about-us.'], function (){
        Route::get('', [AboutUsController::class, 'index'])->name('index')->middleware('can:Manage Cms Settings');
        Route::post('store', [AboutUsController::class, 'store'])->name('store')->middleware('can:Manage Cms Settings');
    });

    Route::group(['prefix' => 'blog', 'as' => 'blogs.'], function (){
        Route::get('', [BlogController::class, 'index'])->name('index')->middleware('can:Manage Cms Settings');
        Route::post('store', [BlogController::class, 'store'])->name('store')->middleware('can:Manage Cms Settings');
        Route::get('edit/{id}', [BlogController::class, 'edit'])->name('edit')->middleware('can:Manage Cms Settings');
        Route::post('delete/{id}', [BlogController::class, 'delete'])->name('delete')->middleware('can:Manage Cms Settings');

        Route::group(['prefix' => 'tag', 'as' => 'tags.'], function (){
            Route::get('', [BlogTagController::class, 'index'])->name('index')->middleware('can:Manage Cms Settings');
            Route::post('store', [BlogTagController::class, 'store'])->name('store')->middleware('can:Manage Cms Settings');
            Route::get('edit/{id}', [BlogTagController::class, 'edit'])->name('edit')->middleware('can:Manage Cms Settings');
            Route::post('delete/{id}', [BlogTagController::class, 'delete'])->name('delete')->middleware('can:Manage Cms Settings');
        });
        Route::group(['prefix' => 'categories', 'as' => 'categories.'], function (){
            Route::get('', [BlogCategoriesController::class, 'index'])->name('index')->middleware('can:Manage Cms Settings');
            Route::post('store', [BlogCategoriesController::class, 'store'])->name('store')->middleware('can:Manage Cms Settings');
            Route::get('edit/{id}', [BlogCategoriesController::class, 'edit'])->name('edit')->middleware('can:Manage Cms Settings');
            Route::post('delete/{id}', [BlogCategoriesController::class, 'delete'])->name('delete')->middleware('can:Manage Cms Settings');
        });
    });
});
Route::group(['prefix' => 'subject', 'as' => 'subjects.'], function () {
    Route::group(['prefix' => 'category', 'as' => 'categories.'], function () {
        Route::get('', [SubjectCategoriesController::class, 'index'])->name('index')->middleware('can:Manage Cms Settings');
        Route::post('store', [SubjectCategoriesController::class, 'store'])->name('store')->middleware('can:Manage Cms Settings');
        Route::get('edit/{id}', [SubjectCategoriesController::class, 'edit'])->name('edit')->middleware('can:Manage Cms Settings');
        Route::post('delete/{id}', [SubjectCategoriesController::class, 'delete'])->name('delete')->middleware('can:Manage Cms Settings');
    });
    Route::get('', [SubjectController::class, 'index'])->name('index')->middleware('can:Manage Cms Settings');
    Route::post('store', [SubjectController::class, 'store'])->name('store')->middleware('can:Manage Cms Settings');
    Route::get('edit/{id}', [SubjectController::class, 'edit'])->name('edit')->middleware('can:Manage Cms Settings');
    Route::post('delete/{id}', [SubjectController::class, 'delete'])->name('delete')->middleware('can:Manage Cms Settings');
});

Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('order-status-modal/{id}', [OrderController::class, 'statusModal'])->name('orders.status_change_modal');
Route::post('order-status-change/{id}', [OrderController::class, 'statusChange'])->name('orders.status_change');
Route::get('transactions', [TransactionController::class, 'index'])->name('transactions');
Route::get('transactions/details/{id}', [TransactionController::class, 'invoiceDetails'])->name('transactions.details');
Route::get('transactions/print/{id}', [TransactionController::class, 'invoicePrint'])->name('transactions.print');
Route::get('contact-us-list', [DashboardController::class, 'contactUsList'])->name('contact_us.list');

Route::group(['prefix' => 'free-consultations', 'as' => 'free_consultations.'], function () {
    Route::get('', [FreeConsultationController::class, 'index'])->name('index')->middleware('can:Manage Free Consultations');
    Route::get('view/{id}', [FreeConsultationController::class, 'view'])->name('view')->middleware('can:Manage Free Consultations');
    Route::post('change-status/{id}', [FreeConsultationController::class, 'changeStatus'])->name('change_status')->middleware('can:Manage Free Consultations');
});

Route::group(['prefix' => 'students', 'as' => 'students.'], function () {
    Route::get('', [StudentController::class, 'index'])->name('index')->middleware('can:Manage Students');
    Route::post('save', [StudentController::class, 'store'])->name('store')->middleware('can:Manage Students');
    Route::get('status-change-modal/{id}', [StudentController::class, 'statusChangeModal'])->name('status_change_modal')->middleware('can:Manage Students');
    Route::post('status-change/{id}', [StudentController::class, 'statusChange'])->name('status_change')->middleware('can:Manage Students');
    Route::post('delete/{id}', [StudentController::class, 'delete'])->name('delete')->middleware('can:Manage Students');
    Route::get('courses/{id}', [StudentController::class, 'courses'])->name('courses')->middleware('can:Manage Students');
});

Route::group(['prefix' => 'service', 'as' => 'services.'], function () {
    Route::get('', [ServiceController::class, 'index'])->name('index')->middleware('can:Manage Service');
    Route::post('store', [ServiceController::class, 'store'])->name('store')->middleware('can:Manage Service');
    Route::get('create', [ServiceController::class, 'create'])->name('create')->middleware('can:Manage Service');
    Route::get('edit/{id}', [ServiceController::class, 'edit'])->name('edit')->middleware('can:Manage Service');
    Route::get('details/{id}', [ServiceController::class, 'details'])->name('details')->middleware('can:Manage Service');
    Route::post('delete/{id}', [ServiceController::class, 'delete'])->name('delete')->middleware('can:Manage Service');
});

// Questions (admin)
Route::group(['prefix' => 'questions', 'as' => 'questions.', 'middleware' => 'can:Manage Questions'], function () {
    Route::get('', [QuestionController::class, 'index'])->name('index');
    Route::post('store', [QuestionController::class, 'store'])->name('store');
    Route::get('show/{id}', [QuestionController::class, 'show'])->name('show');
    Route::post('update/{id}', [QuestionController::class, 'update'])->name('update');
    Route::delete('delete/{id}', [QuestionController::class, 'delete'])->name('delete');
    // further CRUD routes will be added later
});

// University Criteria Fields (admin)
Route::group(['prefix' => 'university-criteria-fields', 'as' => 'university-criteria-fields.', 'middleware' => 'can:Manage Questions'], function () {
    Route::get('', [UniversityCriteriaFieldController::class, 'index'])->name('index');
    Route::post('store', [UniversityCriteriaFieldController::class, 'store'])->name('store');
    Route::get('show/{id}', [UniversityCriteriaFieldController::class, 'show'])->name('show');
    Route::post('update/{id}', [UniversityCriteriaFieldController::class, 'update'])->name('update');
    Route::delete('delete/{id}', [UniversityCriteriaFieldController::class, 'delete'])->name('delete');
});
Route::group(['prefix' => 'form-structure', 'as' => 'form-structure.', 'middleware' => 'can:Manage Questions'], function () {
    Route::get('/', [FormStructureController::class, 'index'])->name('index');
    Route::get('/{id}', [FormStructureController::class, 'getStructure'])->name('get');
    Route::post('/{id}/save', [FormStructureController::class, 'saveStructure'])->name('save');
    Route::post('/{id}/toggle-publish', [FormStructureController::class, 'togglePublish'])->name('toggle-publish');
});

// Career Corner Submissions (admin)
Route::group(['prefix' => 'career-corner-submissions', 'as' => 'career-corner-submissions.', 'middleware' => 'can:Manage Career Corner Submissions'], function () {
    Route::get('/', [CareerCornerSubmissionController::class, 'index'])->name('index');
    Route::get('/{id}', [CareerCornerSubmissionController::class, 'show'])->name('show');
});

Route::get('service-orders', [ServiceOrderController::class, 'index'])->name('service_orders')->middleware('can:Manage Service');
Route::post('service-orders-store/{id?}', [ServiceOrderController::class, 'store'])->name('service_orders.store')->middleware('can:Manage Service');
Route::get('service-orders-edit-{id}', [ServiceOrderController::class, 'edit'])->name('service_orders.edit')->middleware('can:Manage Service');
Route::post('service-orders-delete-{id}', [ServiceOrderController::class, 'delete'])->name('service_orders.delete')->middleware('can:Manage Service');
Route::get('service-orders-view-{id}', [ServiceOrderController::class, 'view'])->name('service_orders.view')->middleware('can:Manage Service');
Route::get('service-orders-status-change/{order_id}/{status}', [ServiceOrderController::class, 'statusChange'])->name('service_orders.status.change')->middleware('can:Manage Service');
Route::get('service-orders-assign-member', [ServiceOrderController::class, 'assignMember'])->name('service_orders.assign.member')->middleware('can:Manage Service');
Route::post('service-orders-note-store', [ServiceOrderController::class, 'noteStore'])->name('service_orders.note.store')->middleware('can:Manage Service');
Route::post('service-orders-note-delete/{id}', [ServiceOrderController::class, 'noteDelete'])->name('service_orders.note.delete')->middleware('can:Manage Service');
Route::post('service-orders-save-file/{id}', [ServiceOrderController::class, 'saveFile'])->name('service_orders.save-file')->middleware('can:Manage Service');
Route::get('service-orders-documents/{id}', [ServiceOrderController::class, 'documents'])->name('service_orders.documents')->middleware('can:Manage Service');
Route::get('service-orders-documents/{id}/edit', [ServiceOrderController::class, 'editDocument'])->name('service_orders.documents.edit')->middleware('can:Manage Service');
Route::post('service-orders-documents/{id}/delete', [ServiceOrderController::class, 'deleteDocument'])->name('service_orders.documents.delete')->middleware('can:Manage Service');
Route::post('service-orders-onboarding-enable/{service_order_id}', [OnboardingController::class, 'onboardingEnable'])->name('service_orders.onboarding_enable')->middleware('can:Manage Service');
Route::get('service-orders-onboarding/{service_order_id}', [OnboardingController::class, 'onboarding'])->name('service_orders.onboarding')->middleware('can:Manage Service');
Route::get('onboarding/edit-modal/{service_order_id}/{section}/{id?}', [OnboardingController::class, 'editModal'])->name('service_orders.onboarding.edit-modal');
Route::post('onboarding/update/{service_order_id}/{section}/{id?}', [OnboardingController::class, 'update'])->name('service_orders.onboarding.update');
Route::post('onboarding/delete/{service_order_id}/{section}/{id}', [OnboardingController::class, 'delete'])->name('service_orders.onboarding.delete');


Route::get('service-invoices', [ServiceOrderInvoiceController::class, 'index'])->name('service_invoices.index')->middleware('can:Manage Service');
Route::post('service-invoice-store/{id?}', [ServiceOrderInvoiceController::class, 'store'])->name('service_invoices.store')->middleware('can:Manage Service');
Route::get('service-invoice-edit-{id}', [ServiceOrderInvoiceController::class, 'edit'])->name('service_invoices.edit')->middleware('can:Manage Service');
Route::post('service-invoice-delete-{id}', [ServiceOrderInvoiceController::class, 'delete'])->name('service_invoices.delete')->middleware('can:Manage Service');
Route::get('get-service-order-{student_id}', [ServiceOrderInvoiceController::class, 'getServiceOrder'])->name('get_service_order')->middleware('can:Manage Service');
Route::get('service-invoice-status-change-modal/{id}', [ServiceOrderInvoiceController::class, 'statusChangeModal'])->name('service_invoices.status_change_modal')->middleware('can:Manage Service');
Route::post('service-invoice-status-change/{id}', [ServiceOrderInvoiceController::class, 'statusChange'])->name('service_invoices.status_change')->middleware('can:Manage Service');
Route::get('service-invoice-details/{id}', [ServiceOrderInvoiceController::class, 'invoiceDetails'])->name('service_invoices.details')->middleware('can:Manage Service');
Route::get('service-invoice-print/{id}', [ServiceOrderInvoiceController::class, 'invoicePrint'])->name('service_invoices.print')->middleware('can:Manage Service');

Route::group(['prefix' => 'service-order-task-board', 'as' => 'service_orders.task-board.', 'middleware' => 'can:Manage Service'], function () {
    Route::get('/{order_id}', [ServiceOrderTaskBoardController::class, 'list'])->name('index');
    Route::post('/{order_id}/{id?}', [ServiceOrderTaskBoardController::class, 'store'])->where(['order_id' => '[0-9]+', 'id' => '[0-9]*'])->name('store');
    Route::post('/{order_id}/update-task-status', [ServiceOrderTaskBoardController::class, 'updateStatus'])->name('update_status');
    Route::get('/{order_id}/edit/{id}', [ServiceOrderTaskBoardController::class, 'edit'])->name('edit');
    Route::post('/{order_id}/delete/{id}', [ServiceOrderTaskBoardController::class, 'delete'])->name('delete');
    Route::get('/{order_id}/view/{id}', [ServiceOrderTaskBoardController::class, 'view'])->name('view');
    Route::post('/{order_id}/delete-attachment/{id}/{attachment_id}', [ServiceOrderTaskBoardController::class, 'deleteAttachment'])->name('delete-attachment');
    Route::post('{order_id}/change-progress/{id}', [ServiceOrderTaskBoardController::class, 'changeProgress'])->name('change_progress');

    Route::group(['prefix' => 'conversation', 'as' => 'conversation.'], function () {
        Route::post('{order_id}/{id}', [ServiceOrderTaskBoardController::class, 'conversationStore'])->name('store');
    });
});
