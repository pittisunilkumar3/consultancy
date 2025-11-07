@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{ $pageTitle }}</h4>
    </div>
    <div class="p-sm-30 p-15">
        <div class="row rg-20">
            <div class="col-xl-12">
                <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                    <div class="">
                        <table class="table zTable zTable-last-item-right pt-15" id="studentDataTable">
                            <thead>
                            <tr>
                                <th><div>{{__('#Sl')}}</div></th>
                                <th><div>{{__('Image')}}</div></th>
                                <th><div>{{__('Name')}}</div></th>
                                <th><div>{{__('Email')}}</div></th>
                                <th><div>{{__('Phone Number')}}</div></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="consulterStudentRoute" value="{{ route('consultant.student.list') }}">
@endsection

@push('script')
    <script src="{{ asset('consultant/js/student.js') }}"></script>
@endpush
