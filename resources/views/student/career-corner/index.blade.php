@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-24 fw-600 lh-34 text-black-2">{{ __('Career Corner') }}</h4>
    </div>
    <div class="p-sm-30 p-15">
        <div class="bg-white bd-half bd-c-stroke br-8 p-30">
            <div class="text-center py-5">
                <h2 class="fs-32 fw-700 mb-4 text-primary">{{ __('Welcome to Career Corner') }}</h2>
                <div class="my-4">
                    <p class="fs-18 fw-600 text-dark mb-3">{{ __('This is test career page') }}</p>
                    <p class="fs-16 text-muted mb-2">{{ __('Test Data Line 1: Career guidance and counseling services') }}</p>
                    <p class="fs-16 text-muted mb-2">{{ __('Test Data Line 2: Job opportunities and placement assistance') }}</p>
                    <p class="fs-16 text-muted mb-2">{{ __('Test Data Line 3: Resume building and interview preparation') }}</p>
                    <p class="fs-16 text-muted mb-2">{{ __('Test Data Line 4: Professional development workshops') }}</p>
                    <p class="fs-16 text-muted">{{ __('Test Data Line 5: Industry networking events and seminars') }}</p>
                </div>
                <div class="mt-5">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="p-4 bg-light rounded">
                                <h5 class="fw-600 mb-2">{{ __('Career Resources') }}</h5>
                                <p class="text-muted mb-0">{{ __('Access to various career development materials') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-4 bg-light rounded">
                                <h5 class="fw-600 mb-2">{{ __('Job Board') }}</h5>
                                <p class="text-muted mb-0">{{ __('Browse latest job postings and opportunities') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-4 bg-light rounded">
                                <h5 class="fw-600 mb-2">{{ __('Mentorship') }}</h5>
                                <p class="text-muted mb-0">{{ __('Connect with industry professionals') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
