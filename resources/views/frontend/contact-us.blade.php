@extends('frontend.layouts.app')
@push('title')
    {{ __('Home') }}
@endpush
@section('content')
    <!-- Breadcrumb -->
    <section class="inner-breadcrumb-section">
        <div class="container">
            <div class="inner-breadcrumb-wrap" data-aos="fade-up" data-aos-duration="1000">
                <h4 class="inner-breadcrumb-title">Contact Us</h4>
                <ol class="breadcrumb inner-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('frontend')}}">{{__('Home')}}</a></li>
                    <li class="breadcrumb-item"><a href="{{route('frontend')}}">{{__('Contact Us')}}</a></li>
                </ol>
            </div>
        </div>
    </section>
    <!-- About destination -->
    <section class="section-gap-top">
        <div class="container">
            <form class="ajax" action="{{ route('contact-us.store') }}" method="post"
                  data-handler="commonResponseWithPageLoad">
                @csrf
                <div class="contact-us-content max-w-721 m-auto" data-aos="fade-up" data-aos-duration="1000">
                    <div class="pb-md-40 pb-30">
                        <h4 class="section-title text-center pb-0">{{__('Get in Touch')}}</h4>
                    </div>
                    <div class="row rg-30">
                        <div class="col-md-6">
                            <label class="zForm-label" for="contactUsFirstName">{{__('First Name')}} <span class="text-danger">*</span></label>
                            <input type="text" class="zForm-control" name="first_name" placeholder="{{__('Enter First Name')}}" id="contactUsFirstName">
                        </div>
                        <div class="col-md-6">
                            <label class="zForm-label" for="contactUsLastName">{{__('Last Name')}} <span class="text-danger">*</span></label>
                            <input type="text" class="zForm-control" name="last_name" placeholder="{{__('Enter Last Name')}}" id="contactUsLastName">
                        </div>
                        <div class="col-md-6">
                            <label class="zForm-label" for="contactUsMobileNumber">{{__('Mobile Number')}} <span class="text-danger">*</span></label>
                            <input type="number" class="zForm-control" name="mobile" placeholder="{{__('Enter Mobile Number')}}" id="contactUsMobileNumber">
                        </div>
                        <div class="col-md-6">
                            <label class="zForm-label" for="contactUsEmailAddress">{{__('Email Address')}} <span class="text-danger">*</span></label>
                            <input type="email" class="zForm-control" name="email" placeholder="{{__('Enter Email Address')}}" id="contactUsEmailAddress">
                        </div>
                        <div class="col-12">
                            <label class="zForm-label" for="contactUsMessage">{{__('Message')}} <span class="text-danger">*</span></label>
                            <textarea class="zForm-control contactUsMessage bd-ra-10" id="contactUsMessage" name="message" placeholder="{{__('Leave your message')}}"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="pt-10">
                                <button type="submit" class="sf-btn-icon-primary">
                                    {{__('Submit Now')}}
                                    <span class="icon">
                                        <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                        <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    @if(getOption('enable_recaptcha'))
        <div class="g-recaptcha mt-20" data-sitekey={{getOption('recaptcha_key')}}></div>
        @error('g-recaptcha-response')
        <span class="fs-12 text-danger">{{ $message }}</span>
        @enderror
    @endif
    <input type="hidden" name="recaptcha_token" id="recaptcha_token">
@endsection
@push('script')
    @if (!empty(getOption('recapture_in_contact_us')) && getOption('recapture_in_contact_us') == 1)
        <script
            src="https://www.google.com/recaptcha/api.js?render={{ getOption('google_recaptcha_site_key') }}"></script>
        <script>
            grecaptcha.ready(function () {
                grecaptcha.execute('{{ getOption('google_recaptcha_site_key') }}', {action: 'submit'}).then(function (token) {
                    document.getElementById('recaptcha_token').value = token;
                });
            });
        </script>
    @endif
@endpush
