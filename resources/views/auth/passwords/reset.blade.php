@extends('layouts.app')

@push('title')
    {{ __('Reset Password') }}
@endpush

@section('content')
    <div class="signLog-section">
        <div class="right" data-background="{{ getSettingImage('login_right_image') }}"></div>
        <div class="left">
            <div class="wrap">
                <div class="zMain-signLog-content">
                    <div class="pb-30">
                        <h4 class="fs-sm-36 fs-26 fw-600 lh-sm-46 lh-20 text-title-text pb-5">{{ __('Reset Password') }}
                            ?</h4>
                        <p class="fs-18 fw-500 lh-28 text-para-text">{{ __('Remember password?') }}
                            <a href="{{ route('login') }}" class="text-brand-primary">{{ __('Sign In') }}</a></p>
                    </div>
                    <form method="POST" action="{{ route('password.update', $token) }}">
                        @csrf
                        <div class="row rg-20 pb-sm-35 pb-20">
                            <div class="col-12">
                                <div>
                                    <label for="inputPhoneEmail" class="zForm-label">{{ __('Email') }}</label>
                                    <input type="email" name="email" class="form-control zForm-control"
                                           value="{{ old('email') }}"
                                           id="inputPhoneEmail"
                                           placeholder="{{ __('Enter email address') }}"/>
                                    @error('email')
                                    <span class="fs-12 text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div>
                                    <label for="inputNewPassword" class="zForm-label">{{ __('New Password') }}</label>
                                    <input type="password" name="password" class="form-control zForm-control"
                                           id="inputNewPassword"
                                           placeholder="{{ __('Enter new password') }}"/>
                                    @error('password')
                                    <span class="fs-12 text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div>
                                    <label for="inputConfirmPassword"
                                           class="zForm-label">{{ __('Confirm Password') }}</label>
                                    <input type="password" name="password_confirmation"
                                           class="form-control zForm-control"
                                           id="inputConfirmPassword"
                                           placeholder="{{ __('Confirm password') }}"/>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="flipBtn sf-flipBtn-primary w-100">{{ __('Sign in') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
