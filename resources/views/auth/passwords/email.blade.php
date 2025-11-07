@extends('layouts.app')

@push('title')
    {{ __('Forget Password') }}
@endpush

@section('content')
    <div class="signLog-section">
        <div class="right" data-background="{{ getFileUrl(getOption('login_right_image')) }}">
            <a href="#" class="logo"><img src="{{ getFileUrl(getOption('app_logo')) }}" alt="" /></a>
            <h4 class="title">
                {{ getOption('auth_page_title') }}
            </h4>
        </div>
        <div class="left">
            <div class="wrap">
                <div class="zMain-signLog-content">
                    <div class="pb-30">
                        <h4 class="fs-sm-36 fs-26 fw-600 lh-sm-46 lh-20 text-title-text pb-5">{{ __('Forgot Password') }}?</h4>
                        <p class="fs-18 fw-500 lh-28 text-para-text">{{ __('Remember password?') }} <a
                                    href="{{ route('login') }}"
                                    class="text-brand-primary">{{ __('Sign In') }}</a></p>
                    </div>
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="row rg-20 pb-sm-35 pb-20">
                            <div class="col-12">
                                <div>
                                    <label for="inputPhoneEmail" class="zForm-label">{{ __('Email') }}</label>
                                    <input type="email" name="email" class="form-control zForm-control" value="{{ old('email') }}"
                                           id="inputPhoneEmail"
                                           placeholder="{{ __('Enter email address') }}"/>
                                    @error('email')
                                    <span class="fs-12 text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="flipBtn sf-flipBtn-primary w-100">{{ __('Reset your password') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
