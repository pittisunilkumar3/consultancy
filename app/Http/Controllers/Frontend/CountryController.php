<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\University;

class CountryController extends Controller
{
    public function details($slug)
    {

        $data['countryData'] = Country::where('slug',$slug)->first();
        $data['universityData'] = University::where(['status'=> STATUS_ACTIVE, 'top_university' => STATUS_ACTIVE,'country_id' => $data['countryData']->id])->take(12)->get();
        $data['bodyClass'] = 'bg-white';

        return view('frontend.country-details', $data);
    }


}
