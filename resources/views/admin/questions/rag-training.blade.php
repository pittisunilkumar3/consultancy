@extends('layouts.app')

@push('title')
    {{ $pageTitle }}
@endpush

@push('style')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endpush

@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{ $pageTitle }}</h4>
    </div>

    <div class="p-sm-30 p-15">
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
            <div
                id="rag-training-root"
                data-upload-url="{{ route('admin.questions.rag-training.upload') }}"
                data-files-url="{{ route('admin.questions.rag-training.files') }}"
            ></div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('js/rag-training.js') }}"></script>
@endpush
