@extends('layouts.app')

@push('title')
    {{ __('Verify') }}
@endpush

@section('content')
    <div class="align-items-center d-flex justify-content-center mx-3 my-5">
        <div class="pb-sm-35 pb-20">
            <h4 class="fs-sm-36 fs-26 fw-600 lh-sm-46 lh-20 text-title-text pb-5">{{ __('Verify Your Email Address') }}</h4>
            <p class="fs-18 fw-500 lh-28 text-para-text">{{ __('Before proceeding, please check your email for a verification link.') }}</p>
            <form action="{{ route('verification.resend') }}" method="POST">
                @csrf
                <p class="fs-18 fw-500 lh-28 text-para-text">{{ __('If you did not receive the email') }},
                    <button type="submit" href="{{ route('verification.resend') }}"
                       class="border-0 text-brand-primary">{{ __('click here to request another') }}</button>
                </p>
            </form>
        </div>
    </div>
@endsection
