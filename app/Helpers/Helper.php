<?php

use App\Models\Currency;
use App\Models\EmailTemplate;
use App\Models\FileManager;
use App\Models\Language;
use App\Models\Notification;
use App\Models\Program;
use App\Models\User;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

if (!function_exists("getOption")) {
    function getOption($option_key, $default = NULL)
    {
        $system_settings = config('settings');

        if ($option_key && isset($system_settings[$option_key])) {
            return $system_settings[$option_key];
        } else {
            return $default;
        }
    }
}

function getSettingImage($option_key)
{

    if ($option_key && $option_key != null) {


        $setting = Setting::where('option_key', $option_key)->first();
        if (isset($setting->option_value) && isset($setting->option_value) != null) {

            $file = FileManager::select('path', 'storage_type')->find($setting->option_value);


            if (!is_null($file)) {
                if (Storage::disk($file->storage_type)->exists($file->path)) {

                    if ($file->storage_type == 'public') {
                        return asset('storage/' . $file->path);
                    }

                    return Storage::disk($file->storage_type)->url($file->path);
                }
            }
        }
    }
    return asset('assets/images/no-image.jpg');
}

function settingImageStoreUpdate($option_value, $requestFile)
{

    if ($requestFile) {

        /*File Manager Call upload*/
        if ($option_value && $option_value != null) {
            $new_file = FileManager::where('id', $option_value)->first();

            if ($new_file) {
                $new_file->removeFile();
                $uploaded = $new_file->upload('Setting', $requestFile, '', $new_file->id);
            } else {
                $new_file = new FileManager();
                $uploaded = $new_file->upload('Setting', $requestFile);
            }
        } else {
            $new_file = new FileManager();
            $uploaded = $new_file->upload('Setting', $requestFile);
        }

        /*End*/

        return $uploaded->id;
    }

    return null;
}

if (!function_exists("getFileSize")) {
    function getFileSize($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        // Calculate the logarithm to determine the unit
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;

        // Calculate the size in the selected unit
        $size = $bytes / pow(1024, $power);

        // Return the size with the appropriate unit
        return round($size, $precision) . ' ' . $units[$power];
    }
}


if (!function_exists("getDefaultImage")) {
    function getDefaultImage()
    {
        // return asset('assets/images/no-image.jpg');
        return asset('assets/images/icon/upload-img-1.svg');
    }
}

if (!function_exists("getDefaultLanguage")) {
    function getDefaultLanguage()
    {
        $language = Language::where('default', STATUS_ACTIVE)->first();
        if ($language) {
            $iso_code = $language->iso_code;
            return $iso_code;
        }

        return 'en';
    }
}

if (!function_exists("getCurrencySymbol")) {
    function getCurrencySymbol()
    {
        $currency = Currency::where('current_currency', 'on')
            ->orWhere(['current_currency' => 1])->first();
        if ($currency) {
            $symbol = $currency->symbol;
            return $symbol;
        }

        return '';
    }
}

if (!function_exists("getIsoCode")) {
    function getIsoCode()
    {
        $currency = Currency::where('current_currency', 'on')
            ->orWhere(['current_currency' => 1])->first();
        if ($currency) {
            $currency_code = $currency->currency_code;
            return $currency_code;
        }

        return '';
    }
}

if (!function_exists("getCurrencyPlacement")) {
    function getCurrencyPlacement()
    {
        $currency = Currency::where('current_currency', 'on')
            ->orWhere(['current_currency' => 1])->first();
        $placement = 'before';
        if ($currency) {
            $placement = $currency->symbol;
            return $placement;
        }

        return $placement;
    }
}

if (!function_exists("showPrice")) {
    function showPrice($price)
    {
        $price = getNumberFormat($price);
        if (config('app.currencyPlacement') == 'after') {
            return $price . config('app.currencySymbol');
        } else {
            return config('app.currencySymbol') . $price;
        }
    }
}


if (!function_exists("getNumberFormat")) {
    function getNumberFormat($amount)
    {
        return number_format($amount, 2, '.', '');
    }
}

if (!function_exists("decimalToInt")) {
    function decimalToInt($amount)
    {
        return number_format(number_format($amount, 2, '.', '') * 100, 0, '.', '');
    }
}

if (!function_exists("intToDecimal")) {
}
function intToDecimal($amount)
{
    return number_format($amount / 100, 2, '.', '');
}

if (!function_exists("appLanguages")) {
    function appLanguages()
    {
        return Language::where('status', 1)->get();
    }
}

if (!function_exists("selectedLanguage")) {
    function selectedLanguage()
    {
        $language = Language::where('iso_code', session()->get('local'))->first();
        if (!$language) {
            $language = Language::find(1);
            if ($language) {
                $ln = $language->iso_code;
                session(['local' => $ln]);
                App::setLocale(session()->get('local'));
            }
        }

        return $language;
    }
}

if (!function_exists("getVideoFile")) {
    function getFile($path, $storageType)
    {
        if (!is_null($path)) {
            if (Storage::disk($storageType)->exists($path)) {

                if ($storageType == 'public') {
                    return asset('storage/' . $path);
                }

                if ($storageType == 'wasabi') {
                    return Storage::disk('wasabi')->url($path);
                }


                return Storage::disk($storageType)->url($path);
            }
        }

        return asset('assets/images/no-image.jpg');
    }
}

if (!function_exists("notificationForUser")) {
    function notificationForUser()
    {
        $instructor_notifications = \App\Models\Notification::where('user_id', auth()->user()->id)->where('user_type', 2)->where('is_seen', 'no')->orderBy('created_at', 'DESC')->get();
        $student_notifications = \App\Models\Notification::where('user_id', auth()->user()->id)->where('user_type', 3)->where('is_seen', 'no')->orderBy('created_at', 'DESC')->get();
        return array('instructor_notifications' => $instructor_notifications, 'student_notifications' => $student_notifications);
    }
}

if (!function_exists("adminNotifications")) {
    function adminNotifications()
    {
        return \App\Models\Notification::where('user_type', 1)->where('is_seen', 'no')->orderBy('created_at', 'DESC')->paginate(5);
    }
}

if (!function_exists('getSlug')) {
    function getSlug($text)
    {
        if ($text) {
            $data = preg_replace("/[~`{}.'\"\!\@\#\$\%\^\&\*\(\)\_\=\+\/\?\>\<\,\[\]\:\;\|\\\]/", "", $text);
            $slug = preg_replace("/[\/_|+ -]+/", "-", $data);
            return $slug;
        }
        return '';
    }
}


if (!function_exists('getCustomerCurrentBuildVersion')) {
    function getCustomerCurrentBuildVersion()
    {
        $buildVersion = getOption('build_version');

        if (is_null($buildVersion)) {
            return 1;
        }

        return (int)$buildVersion;
    }
}

if (!function_exists('setCustomerBuildVersion')) {
    function setCustomerBuildVersion($version)
    {
        $option = Setting::firstOrCreate(['option_key' => 'build_version']);
        $option->option_value = $version;
        $option->save();
    }
}

if (!function_exists('setCustomerCurrentVersion')) {
    function setCustomerCurrentVersion()
    {
        $option = Setting::firstOrCreate(['option_key' => 'current_version']);
        $option->option_value = config('app.current_version');
        $option->save();
    }
}




if (!function_exists('getAddonCodeBuildVersion')) {
    function getAddonCodeBuildVersion($appCode)
    {
        Artisan::call("config:clear");
        return config('addon.' . $appCode . '.build_version', 0);
    }
}

if (!function_exists('getCustomerAddonBuildVersion')) {
    function getCustomerAddonBuildVersion($code)
    {
        $buildVersion = getOption($code . '_build_version', 0);
        if (is_null($buildVersion)) {
            return 0;
        }
        return (int)$buildVersion;
    }
}

if (!function_exists('addLeadingZero')) {
    function addLeadingZero($number) {
        return str_pad($number, 2, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('isAddonInstalled')) {
    function isAddonInstalled($code)
    {
        $buildVersion = getOption($code . '_build_version', 0);
        $codeBuildVersion = config('addon.' . $code . '.build_version', 0);
        if (is_null($buildVersion) || $codeBuildVersion == 0) {
            return 0;
        }
        return (int)$buildVersion;
    }
}

if (!function_exists('setCustomerAddonCurrentVersion')) {
    function setCustomerAddonCurrentVersion($code)
    {
        $option = Setting::firstOrCreate(['option_key' => $code . '_current_version']);
        if (config($code . '.current_version', 0) > 0) {
            $option->option_value = config($code . '.current_version', 0);
            $option->save();
        }
    }
}

if (!function_exists('setCustomerAddonBuildVersion')) {
    function setCustomerAddonBuildVersion($code, $version)
    {
        $option = Setting::firstOrCreate(['option_key' => $code . '_build_version']);
        $option->option_value = $version;
        $option->save();
    }
}

if (!function_exists('getDomainName')) {
    function getDomainName($url)
    {
        $parseUrl = parse_url(trim($url));
        if (isset($parseUrl['host'])) {
            $host = $parseUrl['host'];
        } else {
            $path = explode('/', $parseUrl['path']);
            $host = $path[0];
        }
        return trim($host);
    }
}

if (!function_exists('updateEnv')) {
    function updateEnv($values)
    {
        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                setEnvironmentValue($envKey, $envValue);
            }
            return true;
        }
    }
}

if (!function_exists('setEnvironmentValue')) {
    function setEnvironmentValue($envKey, $envValue)
    {
        try {
            $envFile = app()->environmentFilePath();
            $str = file_get_contents($envFile);
            $str .= "\n"; // In case the searched variable is in the last line without \n
            $keyPosition = strpos($str, "{$envKey}=");
            if ($keyPosition) {
                if (PHP_OS_FAMILY === 'Windows') {
                    $endOfLinePosition = strpos($str, "\n", $keyPosition);
                } else {
                    $endOfLinePosition = strpos($str, PHP_EOL, $keyPosition);
                }
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                $envValue = str_replace(chr(92), "\\\\", $envValue);
                $envValue = str_replace('"', '\"', $envValue);
                $newLine = "{$envKey}=\"{$envValue}\"";
                if ($oldLine != $newLine) {
                    $str = str_replace($oldLine, $newLine, $str);
                    $str = substr($str, 0, -1);
                    $fp = fopen($envFile, 'w');
                    fwrite($fp, $str);
                    fclose($fp);
                }
            } else if (strtoupper($envKey) == $envKey) {
                $envValue = str_replace(chr(92), "\\\\", $envValue);
                $envValue = str_replace('"', '\"', $envValue);
                $newLine = "{$envKey}=\"{$envValue}\"\n";
                $str .= $newLine;
                $str = substr($str, 0, -1);
                $fp = fopen($envFile, 'w');
                fwrite($fp, $str);
                fclose($fp);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('base64urlEncode')) {
    function base64urlEncode($str)
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }
}

if (!function_exists('getTimeZone')) {
    function getTimeZone()
    {
        return DateTimeZone::listIdentifiers(
            DateTimeZone::ALL
        );
    }
}

function getErrorMessage($e, $customMsg = null)
{
    if ($customMsg != null) {
        return $customMsg;
    }
    if (env('APP_DEBUG')) {
        return $e->getMessage() . $e->getLine();
    } else {
        return SOMETHING_WENT_WRONG;
    }
}

if (!function_exists('getFileUrl')) {
    function getFileUrl($id = null)
    {

        $file = FileManager::select('path', 'storage_type')->find($id);

        if (!is_null($file)) {
            if (Storage::disk($file->storage_type)->exists($file->path)) {

                if ($file->storage_type == 'public') {
                    return asset('storage/' . $file->path);
                }

                if ($file->storage_type == 'wasabi') {
                    return Storage::disk('wasabi')->url($file->path);
                }


                return Storage::disk($file->storage_type)->url($file->path);
            }
        }

        return asset('assets/images/no-image.jpg');
    }
}

if (!function_exists('getFileData')) {
    function getFileData($id, $property)
    {
        $file = FileManager::find($id);
        if ($file) {
            return $file->{$property};
        }
        return null;
    }
}

if (!function_exists('emailTemplateStatus')) {
    function emailTemplateStatus($category)
    {
        $status = EmailTemplate::where('category', $category)->where('user_id', auth()->id())->pluck('status')->first();
        if ($status) {
            return $status;
        }
        return DEACTIVATE;
    }
}


if (!function_exists('languageLocale')) {
    function languageLocale($locale)
    {
        $data = Language::where('code', $locale)->first();
        if ($data) {
            return $data->code;
        }
        return 'en';
    }
}


if (!function_exists('getUseCase')) {
    function getUseCase($useCase = [])
    {
        if (in_array("-1", $useCase)) {
            return __("All");
        }
        return count($useCase);
    }
}

function currentCurrency($attribute = '')
{
    $currentCurrency =Currency::where('current_currency', 'on')
        ->orWhere(['current_currency' => 1])->first();
    if (isset($currentCurrency->{$attribute})) {
        return $currentCurrency->{$attribute};
    }
    return '';
}

function currentCurrencyType()
{
    $currentCurrency = Currency::where('current_currency', 'on')
        ->orWhere(['current_currency' => 1])->first();
    return $currentCurrency->currency_code;
}

function random_strings($length_of_string)
{
    $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($str_result), 0, $length_of_string);
}

function getUserId()
{
    try {
        return Auth::id();
    } catch (\Exception $e) {
        return 0;
    }
}


if (!function_exists('visual_number_format')) {
    function visual_number_format($value)
    {
        if (is_integer($value)) {
            return number_format($value, 2, '.', '');
        } elseif (is_string($value)) {
            $value = floatval($value);
        }
        $number = explode('.', number_format($value, 10, '.', ''));
        $intVal = (int)$value;
        if ($value > $intVal || $value < 0) {
            $intPart = $number[0];
            $floatPart = substr($number[1], 0, 8);
            $floatPart = rtrim($floatPart, '0');
            if (strlen($floatPart) < 2) {
                $floatPart = substr($number[1], 0, 2);
            }
            return $intPart . '.' . $floatPart;
        }
        return $number[0] . '.' . substr($number[1], 0, 2);
    }
}

function getError($e)
{
    if (env('APP_DEBUG')) {
        return " => " . $e->getMessage();
    }
    return '';
}

function notification($title = null, $body = null, $user_id = null, $link = null)
{
    try {
        $obj = new Notification();
        $obj->title = $title;
        $obj->body = $body;
        $obj->user_id = $user_id;
        $obj->link = $link;
        $obj->save();
        return "notification sent!";
    } catch (\Exception $e) {
        return "something error!";
    }
}

if (!function_exists('get_default_language')) {
    function get_default_language()
    {
        $language = Language::where('default', STATUS_ACTIVE)->first();
        if ($language) {
            $iso_code = $language->iso_code;
            return $iso_code;
        }

        return 'en';
    }
}

if (!function_exists('get_currency_symbol')) {
    function get_currency_symbol()
    {
        $currency = Currency::where('current_currency', 'on')
            ->orWhere(['current_currency' => 1])->first();
        if ($currency) {
            $symbol = $currency->symbol;
            return $symbol;
        }

        return '';
    }
}

if (!function_exists('get_currency_code')) {
    function get_currency_code()
    {
        $currency = Currency::where('current_currency', 'on')
            ->orWhere(['current_currency' => 1])->first();
        if ($currency) {
            $currency_code = $currency->currency_code;
            return $currency_code;
        }

        return '';
    }
}

if (!function_exists('get_currency_placement')) {
    function get_currency_placement()
    {
        $currency = Currency::where('current_currency', 'on')
            ->orWhere(['current_currency' => 1])->first();
        $placement = 'before';
        if ($currency) {
            $placement = $currency->currency_placement;
            return $placement;
        }

        return $placement;
    }
}


if (!function_exists('customNumberFormat')) {
    function customNumberFormat($value)
    {
        $number = explode('.', $value);
        if (!isset($number[1])) {
            return number_format($value, 8, '.', '');
        } else {
            $result = substr($number[1], 0, 8);
            if (strlen($result) < 8) {
                $result = number_format($value, 8, '.', '');
            } else {
                $result = $number[0] . "." . $result;
            }

            return $result;
        }
    }
}

if (!function_exists('getRandomDecimal')) {
    function getRandomDecimal($min, $max, $probabilityRatio)
    {
        // Calculate the adjusted maximum value based on the probability ratio
        $adjustedMax = $max + ($max - $min) * ($probabilityRatio - 1);

        // Generate a random decimal number within the range
        $randomDecimal = mt_rand($min * 10000, $adjustedMax * 10000) / 10000;

        // Check if the random decimal number needs to be adjusted
        if ($randomDecimal > $max) {
            // Set the number to the maximum value
            $randomDecimal = $max;
        }

        return $randomDecimal;
    }
}

if (!function_exists('privateUserNotification')) {
    function privateUserNotification()
    {
        return Notification::where('user_id', Auth::id())
            ->where('status', ACTIVE)
            ->orderBy('id', 'DESC')
            ->where('view_status', STATUS_PENDING)
            ->get();
    }
}
if (!function_exists('publicUserNotification')) {
    function publicUserNotification()
    {
        return Notification::where('user_id', null)
            ->where('status', ACTIVE)
            ->orderBy('id', 'DESC')
            ->where('view_status', STATUS_PENDING)
            ->get();
    }
}

function get_clientIp()
{
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
}

function humanFileSize($size, $unit = '')
{
    if ((!$unit && $size >= 1 << 30) || $unit == 'GB') {
        return number_format($size / (1 << 30), 2) . 'GB';
    }

    if ((!$unit && $size >= 1 << 20) || $unit == 'MB') {
        return number_format($size / (1 << 20), 2) . 'MB';
    }

    if ((!$unit && $size >= 1 << 10) || $unit == 'KB') {
        return number_format($size / (1 << 10), 2) . 'KB';
    }

    return number_format($size) . ' bytes';
}

if (!function_exists('getMeta')) {
    function getMeta($slug)
    {
        $metaData = [
            'meta_title' => null,
            'meta_description' => null,
            'meta_keyword' => null,
            'og_image' => null,
        ];

        $metaData['meta_title'] = $metaData['meta_title'] != NULL ? $metaData['meta_title'] : getOption('app_name');
        $metaData['meta_description'] = $metaData['meta_description'] != NULL ? $metaData['meta_description'] : getOption('app_name');
        $metaData['meta_keyword'] = $metaData['meta_keyword'] != NULL ? $metaData['meta_keyword'] : getOption('app_name');
        $metaData['og_image'] = $metaData['og_image'] != NULL ? getFileUrl($metaData['og_image']) : getFileUrl(getOption('app_logo'));

        return $metaData;
    }
}
function gatewaySettings()
{
    $settings = [
        "paypal" => [
            ["label" => "Url", "name" => "url", "is_show" => 0],
            ["label" => "Client ID", "name" => "key", "is_show" => 1],
            ["label" => "Secret", "name" => "secret", "is_show" => 1]
        ],
        "stripe" => [
            ["label" => "Url", "name" => "url", "is_show" => 0],
            ["label" => "Public Key", "name" => "key", "is_show" => 0],
            ["label" => "Secret Key", "name" => "secret", "is_show" => 1]
        ],
        "razorpay" => [
            ["label" => "Url", "name" => "url", "is_show" => 0],
            ["label" => "Key", "name" => "key", "is_show" => 1],
            ["label" => "Secret", "name" => "secret", "is_show" => 1]
        ],
        "instamojo" => [
            ["label" => "Url", "name" => "url", "is_show" => 0],
            ["label" => "Api Key", "name" => "key", "is_show" => 1],
            ["label" => "Auth Token", "name" => "secret", "is_show" => 1]
        ],
        "mollie" => [
            ["label" => "Url", "name" => "url", "is_show" => 0],
            ["label" => "Mollie Key", "name" => "key", "is_show" => 1],
            ["label" => "Secret", "name" => "secret", "is_show" => 0]
        ],
        "paystack" => [
            ["label" => "Url", "name" => "url", "is_show" => 0],
            ["label" => "Public Key", "name" => "key", "is_show" => 1],
            ["label" => "Secret Key", "name" => "secret", "is_show" => 0]
        ],
        "mercadopago" => [
            ["label" => "Url", "name" => "url", "is_show" => 0],
            ["label" => "Client ID", "name" => "key", "is_show" => 1],
            ["label" => "Client Secret", "name" => "secret", "is_show" => 1]
        ],
        "sslcommerz" => [
            ["label" => "Url", "name" => "url", "is_show" => 0],
            ["label" => "Store ID", "name" => "key", "is_show" => 1],
            ["label" => "Store Password", "name" => "secret", "is_show" => 1]
        ],
        "flutterwave" => [
            ["label" => "Hash", "name" => "url", "is_show" => 1],
            ["label" => "Public Key", "name" => "key", "is_show" => 1],
            ["label" => "Client Secret", "name" => "secret", "is_show" => 1]
        ],
        "coinbase" => [
            ["label" => "Hash", "name" => "url", "is_show" => 0],
            ["label" => "API Key", "name" => "key", "is_show" => 1],
            ["label" => "Client Secret", "name" => "secret", "is_show" => 0]
        ],
        "binance" => [
            ["label" => "Url", "name" => "url", "is_show" => 0],
            ["label" => "Client ID", "name" => "key", "is_show" => 1],
            ["label" => "Client Secret", "name" => "secret", "is_show" => 1]
        ],
        "bitpay" => [
            ["label" => "Url", "name" => "url", "is_show" => 0],
            ["label" => "Key", "name" => "key", "is_show" => 1],
            ["label" => "Client Secret", "name" => "secret", "is_show" => 0]
        ],
        "iyzico" => [
            ["label" => "Url", "name" => "url", "is_show" => 0],
            ["label" => "Key", "name" => "key", "is_show" => 1],
            ["label" => "Secret", "name" => "secret", "is_show" => 1]
        ],
        "payhere" => [
            ["label" => "Url", "name" => "url", "is_show" => 0],
            ["label" => "Merchant ID", "name" => "key", "is_show" => 1],
            ["label" => "Merchant Secret", "name" => "secret", "is_show" => 1]
        ],
        "maxicash" => [
            ["label" => "Url", "name" => "url", "is_show" => 0],
            ["label" => "Merchant ID", "name" => "key", "is_show" => 1],
            ["label" => "Password", "name" => "secret", "is_show" => 1]
        ],
        "paytm" => [
            ["label" => "Industry Type", "name" => "url", "is_show" => 1],
            ["label" => "Merchant Key", "name" => "key", "is_show" => 1],
            ["label" => "Merchant ID", "name" => "secret", "is_show" => 1]
        ],
        "zitopay" => [
            ["label" => "Industry Type", "name" => "url", "is_show" => 0],
            ["label" => "Key", "name" => "key", "is_show" => 1],
            ["label" => "Merchant ID", "name" => "secret", "is_show" => 0]
        ],
        "cinetpay" => [
            ["label" => "Industry Type", "name" => "url", "is_show" => 0],
            ["label" => "API Key", "name" => "key", "is_show" => 1],
            ["label" => "Site ID", "name" => "secret", "is_show" => 1]
        ],
        "voguepay" => [
            ["label" => "Industry Type", "name" => "url", "is_show" => 0],
            ["label" => "Merchant ID", "name" => "key", "is_show" => 1],
            ["label" => "Merchant ID", "name" => "secret", "is_show" => 0]
        ],
        "toyyibpay" => [
            ["label" => "Industry Type", "name" => "url", "is_show" => 0],
            ["label" => "Secret Key", "name" => "key", "is_show" => 1],
            ["label" => "Category Code", "name" => "secret", "is_show" => 1]
        ],
        "paymob" => [
            ["label" => "Industry Type", "name" => "url", "is_show" => 0],
            ["label" => "API Key", "name" => "key", "is_show" => 1],
            ["label" => "Integration ID", "name" => "secret", "is_show" => 1]
        ],
        "alipay" => [
            ["label" => "APP ID", "name" => "url", "is_show" => 1],
            ["label" => "Public Key", "name" => "key", "is_show" => 1],
            ["label" => "Private Key", "name" => "secret", "is_show" => 1]
        ],
        "xendit" => [
            ["label" => "APP ID", "name" => "url", "is_show" => 0],
            ["label" => "Public Key", "name" => "key", "is_show" => 1],
            ["label" => "Secret", "name" => "secret", "is_show" => 0]
        ],
        "authorize" => [
            ["label" => "Industry Type", "name" => "url", "is_show" => 0],
            ["label" => "Login ID", "name" => "key", "is_show" => 1],
            ["label" => "Transaction Key", "name" => "secret", "is_show" => 1]
        ],
        "cash" => [
            ["label" => "Industry Type", "name" => "url", "is_show" => 0],
            ["label" => "Login ID", "name" => "key", "is_show" => 0],
            ["label" => "Transaction Key", "name" => "secret", "is_show" => 0]
        ]
    ];

    return json_encode($settings);
}


function replaceBrackets($content, $customizedFieldsArray)
{
    $pattern = '/{{(.*?)}}/';
    $content = preg_replace_callback($pattern, function ($matches) use ($customizedFieldsArray) {
        $field = trim($matches[1]);
        if (array_key_exists($field, $customizedFieldsArray)) {
            return $customizedFieldsArray[$field];
        }
        return $matches[0];
    }, $content);
    return $content;
}

function custom_number_format($value)
{
    if (is_integer($value)) {
        return number_format($value, 8, '.', '');
    } elseif (is_string($value)) {
        $value = floatval($value);
    }
    $number = explode('.', number_format($value, 10, '.', ''));
    return $number[0] . '.' . substr($number[1], 0, 8);
}

if (!function_exists('setCommonNotification')) {
    function setCommonNotification($userId, $title, $details, $link = NULL,)
    {
        try {
            DB::beginTransaction();
            $obj = new Notification();
            $obj->user_id = $userId != NULL ? $userId : NULL;
            $obj->title = $title;
            $obj->body = $details;
            $obj->link = $link != NULL ? $link : NULL;
            $obj->save();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}


if (!function_exists('userNotification')) {
    function userNotification($type)
    {
        if ($type == 'seen') {
            return Notification::leftJoin('notification_seens', 'notifications.id', '=', 'notification_seens.notification_id')
                ->where(function ($query) {
                    $query->where('notifications.user_id', null)->orWhere('notifications.user_id', Auth::id());
                })
                ->where('notifications.status', ACTIVE)
                ->where('notification_seens.id', '!=', null)
                ->orderBy('id', 'DESC')
                ->get([
                    'notifications.*',
                    'notification_seens.id as seen_id',
                ]);
        } else if ($type == 'unseen') {
            $test = Notification::leftJoin('notification_seens', 'notifications.id', '=', 'notification_seens.notification_id')
                ->where(function ($query) {
                    $query->where('notifications.user_id', null)->orWhere('notifications.user_id', Auth::id());
                })
                ->where('notifications.status', ACTIVE)
                ->where('notification_seens.id', null)
                ->orderBy('id', 'DESC')
                ->get([
                    'notifications.*',
                    'notification_seens.id as seen_id',
                ]);
            return $test;
        } else if ($type == 'seen-unseen') {
            return Notification::leftJoin('notification_seens', 'notifications.id', '=', 'notification_seens.notification_id')
                ->where(function ($query) {
                    $query->where('notifications.user_id', null)->orWhere('notifications.user_id', Auth::id());
                })
                ->where('notifications.status', ACTIVE)
                ->orderBy('id', 'DESC')
                ->limit(4)
                ->get([
                    'notifications.*',
                    'notification_seens.id as seen_id',
                ]);
        }
    }
}

if (!function_exists('getSubText')) {
    function getSubText($html, $limit = 100000)
    {
        return \Illuminate\Support\Str::limit(strip_tags($html), $limit);
    }
}
if (!function_exists('getPaymentType')) {
    function getPaymentType($object)
    {
        return $className = class_basename(get_class($object));
    }
}
if (!function_exists('thousandFormat')) {
    function thousandFormat($number)
    {
        $number = (int)preg_replace('/[^0-9]/', '', $number);
        if ($number >= 1000) {
            $rn = round($number);
            $format_number = number_format($rn);
            $ar_nbr = explode(',', $format_number);
            $x_parts = array('K', 'M', 'B', 'T', 'Q');
            $x_count_parts = count($ar_nbr) - 1;
            $dn = $ar_nbr[0] . ((int)$ar_nbr[1][0] !== 0 ? '.' . $ar_nbr[1][0] : '');
            $dn .= $x_parts[$x_count_parts - 1];

            return $dn;
        }
        return $number;
    }
}

function currencyPrice($price)
{
    if ($price == null) {
        return 0;
    }
    if (getCurrencyPlacement() == 'after')
        return number_format($price, 2) . '' . getCurrencySymbol();
    else {
        return getCurrencySymbol() . number_format($price, 2);
    }
}

function getEmailByUserId($user_id)
{
    return User::where('id', $user_id)->first(['email'])?->email;
}

function getUserData($user_id, $property)
{
    $data = User::where('id', $user_id)->first();
    if (!is_null($data)) {
        return $data->{$property};
    }
    return null;
}

function getRoleByUserId($user_id)
{
    return User::where('id', $user_id)->first(['role'])->role;
}

if (!function_exists('getAddonCodeCurrentVersion')) {
    function getAddonCodeCurrentVersion($appCode)
    {
        Artisan::call("config:clear");
        if ($appCode == 'ENCYSAAS') {
            return config('addon.ENCYSAAS.current_version', 0);
        }
    }
}

if (!function_exists('get_domain_name')) {
    function get_domain_name($url)
    {
        $parseUrl = parse_url(trim($url));
        if (isset($parseUrl['host'])) {
            $host = $parseUrl['host'];
        } else {
            $path = explode('/', $parseUrl['path']);
            $host = $path[0];
        }
        return trim($host);
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date = null, $format = 'M d')
    {
        // Use current date if no date is provided
        $date = $date ? Carbon::parse($date) : Carbon::now();
        $formattedDate = $date->format($format);

        return $formattedDate;
    }
}
if (!function_exists('getPrefix')) {
    function getPrefix()
    {
        $userRole = auth()->user()->role;
        if($userRole == USER_ROLE_STUDENT){
            return 'student';
        }elseif($userRole == USER_ROLE_ADMIN || $userRole == USER_ROLE_STAFF){
            return 'admin';
        }elseif($userRole == USER_ROLE_CONSULTANT){
            return 'consultant';
        }
    }
}

if (!function_exists('getOnboardingPrefix')) {
    function getOnboardingPrefix()
    {
        $userRole = auth()->user()->role;
        if($userRole == USER_ROLE_STUDENT){
            return 'student.service_orders';
        }elseif($userRole == USER_ROLE_ADMIN || $userRole == USER_ROLE_STAFF){
            return 'admin.service_orders';
        }
    }
}

if (!function_exists('syncMissingGateway')) {
    function syncMissingGateway(): void
    {
        $users = \App\Models\User::where('role', USER_ROLE_ADMIN)->get();
        $gateways = getPaymentServiceClass(); // Get all the available gateways

        // Loop through each user
        foreach ($users as $user) {
            // Get all existing gateways for the current user
            $existingGateways = \App\Models\Gateway::where('user_id', $user->id)->pluck('slug')->toArray();

            // Loop through each gateway in the payment services list
            foreach ($gateways as $gatewaySlug => $gatewayService) {
                // If the gateway doesn't exist for the user, insert it
                if (!in_array($gatewaySlug, $existingGateways)) {
                    // Insert missing gateway for the user
                    $gateway = new \App\Models\Gateway();
                    $gateway->tenant_id = $user->tenant_id;
                    $gateway->user_id = $user->id;
                    $gateway->title = ucfirst($gatewaySlug);
                    $gateway->slug = $gatewaySlug;
                    $gateway->image = 'assets/images/gateway-icon/' . $gatewaySlug . '.png';
                    $gateway->status = 1;
                    $gateway->mode = 2; // Assuming '2' is the default mode
                    $gateway->created_at = now();
                    $gateway->updated_at = now();
                    $gateway->save();

                    // Insert currency for the new gateway
                    $currency = new \App\Models\GatewayCurrency();
                    $currency->gateway_id = $gateway->id;
                    $currency->currency = 'USD';
                    $currency->conversion_rate = 1.0;
                    $currency->created_at = now();
                    $currency->updated_at = now();
                    $currency->save();
                }
            }
        }

        // Now handle user_id = null (global gateways)
        $existingGatewaysForNullTenant = \App\Models\Gateway::whereNull('tenant_id')->pluck('slug')->toArray();
        $findSuperAdmin = User::where('role', USER_ROLE_SUPER_ADMIN)->first();

        foreach ($gateways as $gatewaySlug => $gatewayService) {
            // If the gateway doesn't exist for user_id = null, insert it
            if (!in_array($gatewaySlug, $existingGatewaysForNullTenant)) {
                // Insert missing gateway for user_id = null
                $gateway = new \App\Models\Gateway();
                $gateway->user_id = $findSuperAdmin->id;
                $gateway->tenant_id = null;
                $gateway->title = ucfirst($gatewaySlug);
                $gateway->slug = $gatewaySlug;
                $gateway->image = 'assets/images/gateway-icon/' . $gatewaySlug . '.png';
                $gateway->status = 1;
                $gateway->mode = 2;
                $gateway->created_at = now();
                $gateway->updated_at = now();
                $gateway->save();

                // Insert currency for the new gateway (user_id = null)
                $currency = new \App\Models\GatewayCurrency();
                $currency->gateway_id = $gateway->id;
                $currency->currency = 'USD';
                $currency->conversion_rate = 1.0;
                $currency->created_at = now();
                $currency->updated_at = now();
                $currency->save();
            }
        }
    }
}


if (!function_exists('getEmailTemplate')) {
    function getEmailTemplate($slug, $data)
    {
        $template = EmailTemplate::where(['slug' => $slug])->first();
        $emailData['content'] = replaceTemplateFields($template->body ?? '', $data);
        $emailData['subject'] = replaceTemplateFields($template->subject ?? '', $data);

        return $emailData;
    }

}

if (!function_exists('getEmailTemplateById')) {
    function getEmailTemplateById($id, $data)
    {
        $template = EmailTemplate::where(['id' => $id])->first();
        $emailData['content'] = replaceTemplateFields($template->body ?? '', $data);
        $emailData['subject'] = replaceTemplateFields($template->subject ?? '', $data);

        return $emailData;
    }

}

if (!function_exists('replaceTemplateFields')) {
    function replaceTemplateFields($template, $data)
    {
        $replacements = [
            '{{app_contact_number}}' => getOption('app_contact_number'),
            '{{app_email}}' => getOption('app_email'),
            '{{app_name}}' => getOption('app_name'),
        ];

        // Exclude 'user' key from $data
        unset($data['user']);

        $replacements = array_merge($replacements, $data);

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }
}

if (!function_exists('getResourceDuration')) {
    function getResourceDuration($duration)
    {
        // Convert the duration from seconds to hours, minutes, and seconds
        $hours = floor($duration / 3600);  // Get hours
        $minutes = floor(($duration % 3600) / 60);  // Get minutes
        $seconds = $duration % 60;  // Get seconds

        // Format the time as HH:MM:SS if there are hours, otherwise as MM:SS
        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);  // Format as HH:MM:SS
        } else {
            return sprintf('%02d:%02d', $minutes, $seconds);  // Format as MM:SS
        }
    }
}


if (!function_exists('courseProgram')) {
    function courseProgram()
    {
        return Program::where('status', STATUS_ACTIVE)->get();
    }
}

if (!function_exists('encodeId')) {
    function encodeId($num)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($chars);
        $encoded = '';

        while ($num > 0) {
            $encoded = $chars[$num % $base] . $encoded;
            $num = intdiv($num, $base);
        }

        // Use fixed prefix and suffix for consistent output
        $prefix = 'ab'; // Fixed prefix
        $suffix = 'xy'; // Fixed suffix

        return $prefix . $encoded . $suffix;
    }
}

if (!function_exists('decodeId')) {
    function decodeId($slug)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($chars);

        // Remove the fixed prefix and suffix
        $encoded = substr($slug, 2, -2);
        $decoded = 0;

        for ($i = 0; $i < strlen($encoded); $i++) {
            $decoded = $decoded * $base + strpos($chars, $encoded[$i]);
        }

        return $decoded;
    }
}

if (!function_exists("getOnboardingField")) {
    function getOnboardingField($collection, $slug, $column, $default = 0)
    {
        $item = $collection->where('field_slug', $slug)->first();

        if ($item) {
            $column = 'field_'.$column;
            return $item->$column;
        } else {
            return $default;
        }
    }
}

if (!function_exists('generateUniqueTaskboardId')) {
    function generateUniqueTaskboardId($taskNumber)
    {
        // Ensure the task number is a 2-digit number
        $taskID = str_pad($taskNumber % 100, 2, '0', STR_PAD_LEFT);

        // Check for uniqueness in the database
        while (\App\Models\StudentServiceOrderTask::where('taskID', $taskID)->exists()) {
            $taskNumber++;
            $taskNumberFormatted = str_pad($taskNumber % 100, 2, '0', STR_PAD_LEFT);
            $taskID = $taskNumberFormatted;
        }

        return $taskID;
    }
}


