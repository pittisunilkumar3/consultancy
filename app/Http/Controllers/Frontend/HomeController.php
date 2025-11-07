<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\Blog;
use App\Models\ContactUs;
use App\Models\Country;
use App\Models\Event;
use App\Models\Faq;
use App\Models\Program;
use App\Models\Service;
use App\Models\StudyLevel;
use App\Models\Subject;
use App\Models\Testimonial;
use App\Models\University;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $data['serviceData'] = Service::where('status',STATUS_ACTIVE)->get();
        $howWeWork = json_decode(getOption('how-we-work'), true);
        $data['titles'] = $howWeWork['title'] ?? [];
        $data['descriptions'] = $howWeWork['description'] ?? [];
        $data['countryData'] = Country::where('status',STATUS_ACTIVE)->get();
        $data['universityData'] = University::where(['status'=>STATUS_ACTIVE, 'feature' => STATUS_ACTIVE])->get();
        $data['eventData'] = Event::where('status',STATUS_ACTIVE)->orderby('date_time',"ASC")->get();
        $data['testimonialData'] = Testimonial::where('status',STATUS_ACTIVE)->get();
        $data['faqData'] = Faq::where('status',STATUS_ACTIVE)->get();
        $data['studyLevelsData'] = StudyLevel::where('status',STATUS_ACTIVE)->get();
        $data['subjectData'] = Subject::where('status',STATUS_ACTIVE)->get();
        $data['aboutUs'] = AboutUs::first();
        $data['blogData'] = Blog::leftJoin('users', 'blogs.created_by', '=', 'users.id')
            ->leftJoin('designations', 'designations.id', '=', 'users.designation_id')
            ->select('blogs.*',  DB::raw("CONCAT(users.first_name, ' ', users.last_name) as userName"), 'users.image as userImage', 'designations.title as userDesignation')
            ->where('blogs.status', STATUS_ACTIVE)
            ->take(4)
            ->get();

        $data['studyLevels'] = StudyLevel::where('status', STATUS_ACTIVE)->get();


        return view('frontend.index',$data);
    }

    public function page($slug)
    {
        $data['bodyClass'] = 'bg-white';
        $data['policyPageTitle'] = getOption($slug . '_title');
        $data['policyPageDetails'] = getOption($slug . '_details');

        return view('frontend.page', $data);
    }

    public function getUniversitiesByCountry($country_id)
    {
        $universities = University::where('country_id', $country_id)
            ->where('status', STATUS_ACTIVE)
            ->get(['id', 'name']);

        return response()->json([
            'status' => true,
            'data' => $universities,
        ]);
    }
    public function getUniversityByCountry(Request $request)
    {
        // Convert comma-separated country IDs to an array
        $countryIdsArray = explode(',', $request->country_ids);

        // Retrieve universities for the given countries
        $universities = University::whereIn('country_id', $countryIdsArray)
            ->where('status', STATUS_ACTIVE)
            ->get(['id', 'name']);

        return response()->json([
            'status' => true,
            'data' => $universities,
        ]);
    }

    public function getSubjectByUniversity(Request $request)
    {
        // Convert comma-separated university IDs to an array
        $universityIdsArray = explode(',', $request->university_ids);

        // Retrieve subjects based on selected universities
        $subjects = Subject::whereIn('university_id', $universityIdsArray)
            ->where('status', STATUS_ACTIVE)
            ->get(['id', 'name']);

        return response()->json([
            'status' => true,
            'data' => $subjects,
        ]);
    }

    public function contactUs(){

        $data['bodyClass'] = '';
        $data['activeContactUsMenu'] = 'active';

        return view('frontend.contact-us',$data);
    }
    public function contactUsStore(Request $request) {

        if (!empty(getOption('recapture_in_contact_us')) && getOption('recapture_in_contact_us') == 1) {
            $validatedData = $request->validate([
                'recaptcha_token' => 'required'
            ]);
            if (!$this->verifyRecaptcha($validatedData['recaptcha_token'])) {
                return back()->withErrors(['recaptcha' => 'reCAPTCHA verification failed. Please try again.']);
            }
        }

        $validated = $request->validate([
            "first_name" => ['required', 'string', 'max:255'],
            "last_name" => ['required', 'string', 'max:255'],
            "email" => ['required', 'email', 'max:255'],
            'mobile' => ['required', 'string', 'min:2', 'max:60'],
            'message' => ['required', 'string'],
        ]);


        DB::beginTransaction();
        try {
            $contactUs = new ContactUs();

            $contactUs->first_name = $request->first_name;
            $contactUs->last_name = $request->last_name;
            $contactUs->mobile = $request->mobile;
            $contactUs->email = $request->email;
            $contactUs->message = $request->message;

            $contactUs->save();
            DB::commit();
            $message = SENT_SUCCESSFULLY;
            return $this->success([], getMessage($message));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getMessage($e->getMessage()));
        }

    }
    public function verifyRecaptcha($token)
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => getOption('google_recaptcha_secret_key'),
            'response' => $token,
        ]);

        $recaptchaData = $response->json();
        return isset($recaptchaData['success'], $recaptchaData['score']) && $recaptchaData['success'] && $recaptchaData['score'] >= 0.5;
    }

}
