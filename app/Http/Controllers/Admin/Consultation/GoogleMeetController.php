<?php

namespace App\Http\Controllers\Admin\Consultation;

use App\Http\Controllers\Controller;
use App\Models\MeetingPlatform;
use App\Traits\ResponseTrait;
use Google_Client;
use Google_Service_Calendar;
use Illuminate\Http\Request;

class GoogleMeetController extends Controller
{
    use ResponseTrait;

    public function getClientToken($clientId, $clientSecret)
    {
        $redirectUri = route('admin.consultations.meeting_platforms.google_meet_callback');
        $client = new Google_Client();

        $client->setApplicationName(getOption('app_name', 'Studylifter'));
        $client->setRedirectUri($redirectUri);

        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false,),));
        $client->setHttpClient($guzzleClient);

        $authUrl = $client->createAuthUrl();
        return redirect($authUrl);
    }

    public function googleMeetCallback(Request $request)
    {
        $googleMeet = MeetingPlatform::where(['type' => MEETING_PLATFORMS_MEET])->first();
        if ($request->code) {
            $applicationName = getOption('app_name', 'Studylifter');
            $redirectUri = route('admin.consultations.meeting_platforms.google_meet_callback');
            $clientId = $googleMeet->key;
            $clientSecret = $googleMeet->secret;
            $client = new Google_Client();

            $client->setApplicationName($applicationName);
            $client->setRedirectUri($redirectUri);

            $client->setScopes(Google_Service_Calendar::CALENDAR);
            $client->setClientId($clientId);
            $client->setClientSecret($clientSecret);
            $client->setAccessType('offline');
            $client->setPrompt('select_account_consent');
            $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false,),));
            $client->setHttpClient($guzzleClient);

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode(trim($request->code));
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                return redirect()->route('admin.consultations.meeting_platforms.index')->with('error', __('Something went wrong with your credentials'));
            }

            $googleMeet->token = json_encode($accessToken);
            $googleMeet->status = STATUS_ACTIVE;
            $googleMeet->save();

            return redirect()->route('admin.consultations.meeting_platforms.index')->with('success', __('Credentials authorize successfully'));
        }

        return redirect()->route('admin.consultations.meeting_platforms.index')->with('error', __('Something went wrong with your credentials'));
    }

}
