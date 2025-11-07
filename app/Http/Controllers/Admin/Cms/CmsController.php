<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\Mail;

class CmsController extends Controller
{
    use ResponseTrait;

    public function sectionSettings(){
        $data['landingPage'] = 'active';
        $data['showCmsSettings'] = 'show';
        $data['pageTitle'] = __("Section Settings");
        $data['subSectionSettingsCmsSettingActiveClass'] = 'active';

        return view('admin.cms.section-setting',$data);
    }

    public function bannerSetting()
    {
        $data['landingPage'] = 'active';
        $data['showCmsSettings'] = 'show';
        $data['pageTitle'] = __("Banner Section");
        $data['subBannerCmsSettingActiveClass'] = 'active';

        return view('admin.cms.banner-setting',$data);
    }
    public function ourService()
    {
        $data['landingPage'] = 'active';
        $data['showCmsSettings'] = 'show';
        $data['pageTitle'] = __("Our Service Section");
        $data['subOurServiceActiveClass'] = 'active';

        return view('admin.cms.our-service-section',$data);
    }

    public function whyChooseUs(){

        $data['pageTitle'] = __("Why Choose Us");
        $data['landingPage'] = 'active';
        $data['showCmsSettings'] = 'show';
        $data['subWhyChooseUsCmsSettingsActiveClass'] = 'active';

        return view('admin.cms.why-choose-us',$data);
    }

    public function howWeWork(){

        $data['pageTitle'] = __("How We Work");
        $data['landingPage'] = 'active';
        $data['showCmsSettings'] = 'show';
        $data['subHowWeWorkCmsSettingsActiveClass'] = 'active';
        $howWeWorkDataJson = getOption('how-we-work');
        $howWeWorkData = json_decode($howWeWorkDataJson, true);
        $data['titles'] = $howWeWorkData['title'] ?? [];
        $data['descriptions'] = $howWeWorkData['description'] ?? [];
        return view('admin.cms.how-we-work',$data);
    }

    public function ctaSection(){

        $data['pageTitle'] = __("CTA Section");
        $data['landingPage'] = 'active';
        $data['showCmsSettings'] = 'show';
        $data['subCtaSectionCmsSettingsActiveClass'] = 'active';

        return view('admin.cms.cta-section',$data);
    }

    public function pageSection(){

        $data['pageTitle'] = __("Page Section");
        $data['showCmsSettings'] = 'show';
        $data['landingPage'] = 'active';
        $data['subPageSectionCmsSettingsActiveClass'] = 'active';

        return view('admin.cms.page-section',$data);
    }

    public function freeConsultant(){

        $data['pageTitle'] = __("Free Consultation");
        $data['showCmsSettings'] = 'show';
        $data['landingPage'] = 'active';
        $data['subFreeCosultantCmsSettingsActiveClass'] = 'active';

        return view('admin.cms.free-consultant',$data);
    }
}
