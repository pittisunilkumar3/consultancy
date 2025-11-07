<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.header')

<body
    class="font-family-nunitoSans {{ selectedLanguage()->rtl == 1 ? 'direction-rtl' : 'direction-ltr' }} {{ !(getOption('app_color_design_type', DEFAULT_COLOR) == DEFAULT_COLOR) }}">

<input type="hidden" id="lang_code" value="{{session('local')}}">
@if (getOption('app_preloader_status', 0) == STATUS_ACTIVE)
    <div id="preloader">
        <div id="preloader_status">
            <img src="{{ getSettingImage('app_preloader') }}" alt="{{ getOption('app_name') }}"/>
        </div>
    </div>
@endif
@if(auth()->check() && !isset($disableHeader))
    <div class="zMain-wrap">
        <!-- Sidebar -->
        @include(getPrefix().'.layouts.sidebar')
        <!-- Main Content -->
        <div class="zMainContent">
            <!-- Header -->
            @include('layouts.nav')
            <!-- Content -->
            @yield('content')
        </div>
    </div>
@else
    @yield('content')
@endif

@if (!empty(getOption('cookie_status')) && getOption('cookie_status') == STATUS_ACTIVE)
    <div class="cookie-consent-wrap shadow-lg">
        @include('cookie-consent::index')
    </div>
@endif
@include('layouts.script')
</body>

</html>
