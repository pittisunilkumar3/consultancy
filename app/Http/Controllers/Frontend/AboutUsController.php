<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\Faq;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function details(Request $request)
    {
        $data['testimonialData'] = Testimonial::where('status', STATUS_ACTIVE)->get();
        $data['faqData'] = Faq::where('status', STATUS_ACTIVE)->get();

        $data['aboutUs'] = AboutUs::first();
        $data['activeAboutUsMenu'] = 'active';
        $data['pageTitle'] = __('About Us');

        return view('frontend.about-us', $data);
    }


}
