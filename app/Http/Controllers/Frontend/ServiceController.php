<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;

class ServiceController extends Controller
{
    public function details($slug)
    {
        $data['bodyClass'] = 'bg-white';
        $data['serviceData'] = Service::where('slug',$slug)->first();

        return view('frontend.service-details', $data);
    }


}
