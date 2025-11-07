@extends('layouts.app')

@push('title')
    {{ __('Login') }}
@endpush

@section('content')
    <div class="signLog-section">
        <div class="right" data-background="{{ getFileUrl(getOption('login_right_image')) }}">
            <a href="{{ route('frontend') }}" class="logo"><img src="{{ getFileUrl(getOption('app_logo')) }}" alt="" /></a>
            <h4 class="title">
                {{ getOption('auth_page_title') }}
            </h4>
        </div>
        <div class="left">
            <div class="wrap">
                <div class="zMain-signLog-content">
                    <div class="pb-30">
                        <h4 class="fs-sm-36 fs-26 fw-600 lh-sm-46 lh-20 text-title-text pb-5">{{ __('Sign in') }}</h4>
                        @if (getOption('registration_status', 0) != ACTIVE)
                            <p class="fs-18 fw-500 lh-28 text-para-text">{{ __('Don’t have an account?') }} <a
                                    href="{{ route('register') }}"
                                    class="text-brand-primary">{{ __('Sign Up') }}</a></p>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row rg-20 pb-15">
                            <div class="col-md-6">
                                <div>
                                    <label for="inputPhoneEmail" class="zForm-label">{{ __('Email') }}</label>
                                    <input type="email" name="email" value="{{old('email')}}"
                                           class="form-control zForm-control"
                                           id="inputPhoneEmail"
                                           placeholder="{{ __('Enter email address') }}"/>
                                    @error('email')
                                    <span class="fs-12 text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="">
                                    <label for="inputPassword" class="zForm-label">{{ __('Password') }}</label>
                                    <div class="passShowHide">
                                        <input type="password" name="password"
                                               class="form-control zForm-control passShowHideInput"
                                               id="inputPassword" placeholder="{{ __('Enter your password') }}"/>
                                        <button type="button" toggle=".passShowHideInput"
                                                class="toggle-password fa-solid fa-eye"></button>
                                        @error('password')
                                        <span class="fs-12 text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pb-sm-35 pb-20 d-flex justify-content-end align-items-center flex-wrap g-10">
                            <a href="{{ route('password.request') }}"
                               class="fs-18 fw-500 lh-28 text-brand-primary text-decoration-underline">{{ __('Forgot Password?') }}</a>
                        </div>
                        @if(getOption('enable_recaptcha'))
                            <div class="g-recaptcha mt-20" data-sitekey={{getOption('recaptcha_key')}}></div>
                            @error('g-recaptcha-response')
                            <span class="fs-12 text-danger">{{ $message }}</span>
                            @enderror
                        @endif
                        <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                        <button type="submit" class="flipBtn sf-flipBtn-primary w-100">{{ __('Sign in') }}</button>
                    </form>
                    @if (getOption('google_login_status') == 1 || getOption('facebook_login_status') == 1)
                        <div class="otherAuthWrap">
                            <p class="text"><span>{{__('Or continue with')}}</span></p>
                            <ul class="authList">
                                @if (getOption('facebook_login_status') == 1)
                                    <li>
                                        <a href="{{ route('facebook-login') }}" class="item">
                                            <svg width="42" height="42" viewBox="0 0 42 42" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M39.6615 20.9999C39.6615 10.6906 31.3041 2.33325 20.9948 2.33325C10.6855 2.33325 2.32812 10.6906 2.32812 20.9999C2.32812 30.3168 9.15422 38.0394 18.0781 39.4398V26.3957H13.3385V20.9999H18.0781V16.8874C18.0781 12.2091 20.865 9.62492 25.1288 9.62492C27.1712 9.62492 29.3073 9.9895 29.3073 9.9895V14.5833H26.9535C24.6348 14.5833 23.9115 16.0223 23.9115 17.4985V20.9999H29.0885L28.2609 26.3957H23.9115V39.4398C32.8354 38.0394 39.6615 30.3172 39.6615 20.9999Z"
                                                    fill="#1877F2"/>
                                                <path
                                                    d="M28.2622 26.3958L29.0898 21H23.9128V17.4985C23.9128 16.0223 24.6361 14.5833 26.9548 14.5833H29.3086V9.98958C29.3086 9.98958 27.1725 9.625 25.1301 9.625C20.8663 9.625 18.0794 12.2092 18.0794 16.8875V21H13.3398V26.3958H18.0794V39.4399C19.0299 39.589 20.0037 39.6667 20.9961 39.6667C21.9885 39.6667 22.9623 39.589 23.9128 39.4399V26.3958H28.2622Z"
                                                    fill="white"/>
                                            </svg>
                                        </a>
                                    </li>
                                @endif
                                @if (getOption('google_login_status') == 1)
                                    <li>
                                        <a href="{{ route('google-login') }}" class="item">
                                            <svg width="35" height="35" viewBox="0 0 35 35" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <mask id="mask0_3019_2633" style="mask-type:luminance"
                                                      maskUnits="userSpaceOnUse" x="0" y="0" width="35" height="35">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M34.0998 14.5568H18.0096V21.0767H27.2713C26.4079 25.2188 22.7974 27.5966 18.0096 27.5966C12.3585 27.5966 7.80614 23.1477 7.80614 17.625C7.80614 12.1023 12.3585 7.65341 18.0096 7.65341C20.4428 7.65341 22.6404 8.49716 24.3672 9.87784L29.3904 4.96875C26.3294 2.3608 22.405 0.75 18.0096 0.75C8.43405 0.75 0.742188 8.26705 0.742188 17.625C0.742188 26.983 8.43405 34.5 18.0096 34.5C26.6434 34.5 34.4922 28.3636 34.4922 17.625C34.4922 16.6278 34.3352 15.554 34.0998 14.5568Z"
                                                          fill="white"/>
                                                </mask>
                                                <g mask="url(#mask0_3019_2633)">
                                                    <path
                                                        d="M-0.828125 27.5965V7.65332L12.5149 17.6249L-0.828125 27.5965Z"
                                                        fill="#FBBC05"/>
                                                </g>
                                                <mask id="mask1_3019_2633" style="mask-type:luminance"
                                                      maskUnits="userSpaceOnUse" x="0" y="0" width="35" height="35">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M34.0998 14.5568H18.0096V21.0767H27.2713C26.4079 25.2188 22.7974 27.5966 18.0096 27.5966C12.3585 27.5966 7.80614 23.1477 7.80614 17.625C7.80614 12.1023 12.3585 7.65341 18.0096 7.65341C20.4428 7.65341 22.6404 8.49716 24.3672 9.87784L29.3904 4.96875C26.3294 2.3608 22.405 0.75 18.0096 0.75C8.43405 0.75 0.742188 8.26705 0.742188 17.625C0.742188 26.983 8.43405 34.5 18.0096 34.5C26.6434 34.5 34.4922 28.3636 34.4922 17.625C34.4922 16.6278 34.3352 15.554 34.0998 14.5568Z"
                                                          fill="white"/>
                                                </mask>
                                                <g mask="url(#mask1_3019_2633)">
                                                    <path
                                                        d="M-0.828125 7.65332L12.5149 17.6249L18.0091 12.9459L36.8463 9.95446V-0.78418H-0.828125V7.65332Z"
                                                        fill="#EA4335"/>
                                                </g>
                                                <mask id="mask2_3019_2633" style="mask-type:luminance"
                                                      maskUnits="userSpaceOnUse" x="0" y="0" width="35" height="35">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M34.0998 14.5568H18.0096V21.0767H27.2713C26.4079 25.2188 22.7974 27.5966 18.0096 27.5966C12.3585 27.5966 7.80614 23.1477 7.80614 17.625C7.80614 12.1023 12.3585 7.65341 18.0096 7.65341C20.4428 7.65341 22.6404 8.49716 24.3672 9.87784L29.3904 4.96875C26.3294 2.3608 22.405 0.75 18.0096 0.75C8.43405 0.75 0.742188 8.26705 0.742188 17.625C0.742188 26.983 8.43405 34.5 18.0096 34.5C26.6434 34.5 34.4922 28.3636 34.4922 17.625C34.4922 16.6278 34.3352 15.554 34.0998 14.5568Z"
                                                          fill="white"/>
                                                </mask>
                                                <g mask="url(#mask2_3019_2633)">
                                                    <path
                                                        d="M-0.828125 27.5965L22.7184 9.95446L28.919 10.7215L36.8463 -0.78418V36.034H-0.828125V27.5965Z"
                                                        fill="#34A853"/>
                                                </g>
                                                <mask id="mask3_3019_2633" style="mask-type:luminance"
                                                      maskUnits="userSpaceOnUse" x="0" y="0" width="35" height="35">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M34.0998 14.5568H18.0096V21.0767H27.2713C26.4079 25.2188 22.7974 27.5966 18.0096 27.5966C12.3585 27.5966 7.80614 23.1477 7.80614 17.625C7.80614 12.1023 12.3585 7.65341 18.0096 7.65341C20.4428 7.65341 22.6404 8.49716 24.3672 9.87784L29.3904 4.96875C26.3294 2.3608 22.405 0.75 18.0096 0.75C8.43405 0.75 0.742188 8.26705 0.742188 17.625C0.742188 26.983 8.43405 34.5 18.0096 34.5C26.6434 34.5 34.4922 28.3636 34.4922 17.625C34.4922 16.6278 34.3352 15.554 34.0998 14.5568Z"
                                                          fill="white"/>
                                                </mask>
                                                <g mask="url(#mask3_3019_2633)">
                                                    <path
                                                        d="M36.8537 36.034L12.5223 17.6249L9.38281 15.3238L36.8537 7.65332V36.034Z"
                                                        fill="#4285F4"/>
                                                </g>
                                            </svg>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    @if (env('LOGIN_HELP') == 'active')
                        <div class="row pt-12 fs-14">
                            <div class="col-md-12 mb-25">
                                <div class="table-responsive login-info-table mt-3">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td colspan="2" id="adminCredentialShow" class="login-info">
                                                <b>{{ __('Admin ') }}:</b> {{ __('admin@gmail.com') }} | 123456
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" id="staffCredentialShow" class="login-info">
                                                <b>{{ __('Staff') }}:</b> {{ __('staff@gmail.com') }} |
                                                123456
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" id="consulterCredentialShow" class="login-info">
                                                <b>{{ __('Consultant') }}:</b> {{ __('consultant@gmail.com') }} |
                                                123456
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" id="studentCredentialShow" class="login-info">
                                                <b>{{ __('Student ') }}:</b> {{ __('student@gmail.com') }} | 123456
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict"
        $('#adminCredentialShow').on('click', function () {
            $('#inputPhoneEmail').val('admin@gmail.com');
            $('#inputPassword').val('123456');
        });
        $('#staffCredentialShow').on('click', function () {
            $('#inputPhoneEmail').val('staff@gmail.com');
            $('#inputPassword').val('123456');
        });
        $('#consulterCredentialShow').on('click', function () {
            $('#inputPhoneEmail').val('consultant@gmail.com');
            $('#inputPassword').val('123456');
        });
        $('#studentCredentialShow').on('click', function () {
            $('#inputPhoneEmail').val('student@gmail.com');
            $('#inputPassword').val('123456');
        });
    </script>

    @if (!empty(getOption('google_recaptcha_status')) && getOption('google_recaptcha_status') == 1)
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
