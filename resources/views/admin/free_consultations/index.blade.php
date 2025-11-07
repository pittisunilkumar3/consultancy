@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{$pageTitle}}</h4>
    </div>
    <div class="p-sm-30 p-15">
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
            <div class="">
                <table class="table zTable zTable-last-item-right pt-15" id="freeConsultationDataTable">
                    <thead>
                    <tr>
                        <th>
                            <div>{{__('Sl')}}</div>
                        </th>
                        <th>
                            <div>{{__('Name')}}</div>
                        </th>
                        <th>
                            <div>{{__('Email')}}</div>
                        </th>
                        <th>
                            <div>{{__('Mobile')}}</div>
                        </th>
                        <th>
                            <div>{{__('Study Level')}}</div>
                        </th>
                        <th>
                            <div>{{__('Status')}}</div>
                        </th>
                        <th>
                            <div>{{__('Action')}}</div>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- Page content area end -->

    <!-- Edit Modal section start -->
    <div class="modal fade" id="view-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">

            </div>
        </div>
    </div>

    <input type="hidden" id="free-consultations-route" value="{{ route('admin.free_consultations.index') }}">

    <!-- Edit Modal section end -->
@endsection
@push('script')
    <script src="{{asset('admin/custom/js/free_consultations.js')}}"></script>
@endpush
