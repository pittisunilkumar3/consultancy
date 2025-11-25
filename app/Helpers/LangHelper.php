<?php

if (!function_exists('__')) {
    function __($key = null, $replace = [], $locale = null)
    {
        if (session()->get('local') != null) {
            $path = resource_path() . "/lang/" . session()->get('local') . ".json";

            // Avoid writing translation files in production; just fall back to the key
            if (!file_exists($path)) {
                if (!app()->environment('production')) {
                    file_put_contents($path, '{}');
                } else {
                    return $key;
                }
            }

            $website = json_decode(file_get_contents($path), true) ?: [];

            $key = preg_replace('/\s+/S', " ", $key);

            if (array_key_exists($key, $website)) {
                if (session()->get('local') == null) {
                    return $key;
                }
                return $website[$key];
            }

            $website[$key] = $key;

            // Only auto-update translation JSON in non-production environments
            if (!app()->environment('production')) {
                file_put_contents($path, json_encode($website));
            }

            if (session()->get('local') == null) {
                return $key;
            }
        }
        if (is_null($key)) {
            return $key;
        }
        return trans($key, $replace, $locale);
    }
}


if (!function_exists('test')) {
    function test()
    {
        return 132;
    }
}

