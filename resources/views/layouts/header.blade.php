<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <title>{{ getOption('app_name') }} - @stack('title' ?? '')</title>
    @hasSection('meta')
        @stack('meta')
    @else
        @php
            $metaData = getMeta('home');
        @endphp

            <!-- Open Graph meta tags for social sharing -->
        <meta property="og:type" content="{{ __('studylifter') }}">
        <meta property="og:title" content="{{ $metaData['meta_title'] ?? getOption('app_name') }}">
        <meta property="og:description" content="{{ $metaData['meta_description'] ?? getOption('app_name') }}">
        <meta property="og:image" content="{{ $metaData['og_image'] ?? getSettingImage('app_logo_black') }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:site_name" content="{{getOption('app_name') }}">

        <!-- Twitter Card meta tags for Twitter sharing -->
        <meta name="twitter:card" content="{{ __('studylifter') }}">
        <meta name="twitter:title" content="{{ $metaData['meta_title'] ?? getOption('app_name') }}">
        <meta name="twitter:description" content="{{ $metaData['meta_description'] ?? getOption('app_name') }}">
        <meta name="twitter:image" content="{{ $metaData['og_image'] ?? getSettingImage('app_logo_black') }}">

        <meta name="csrf-token" content="{{ csrf_token() }}"/>
        <meta name="base-url" content="{{ url('/') }}"/>
    @endif

    <!-- Place favicon.ico in the root directory -->
    <link rel="icon" href="{{ getSettingImage('app_fav_icon') }}" type="image/png" sizes="16x16">
    <link rel="shortcut icon" href="{{ getSettingImage('app_fav_icon') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ getSettingImage('app_fav_icon') }}">

    <!-- css file  -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/plugins.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/dataTables.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/dataTables.responsive.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/summernote/summernote-lite.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/scss/style.css')}}?v={{env('VERSION', 1)}}"/>
    @include('layouts.dynamic-color')
    @stack('style')
    @if(getOption('google_analytics_status', 0))
        <!-- Google tag (gtag.js) -->
        <script async
                src="https://www.googletagmanager.com/gtag/js?id={{ getOption('google_analytics_tracking_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());
            gtag('config', "{{ getOption('google_analytics_tracking_id') }}");
        </script>
    @endif
</head>
