@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="p-sm-30 p-15">
        <div class="row rg-20">
            <div class="col-xl-3">
                <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                    @include('admin.setting.sidebar')
                </div>
            </div>
            <div class="col-xl-9">
                <div class="align-items-center bd-b-one bd-c-one bd-c-stroke bd-c-stroke-2 d-flex justify-content-between mb-30 pb-15 pb-sm-30">
                    <h4 class="fs-18 fw-700 lh-24 text-title-text">{{$pageTitle}}</h4>
                </div>
                <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                    <div class="">
                        <table class="table zTable zTable-last-item-right pt-15" id="emailTemplateDataTable">
                            <thead>
                            <tr>
                                <th><div>{{__('Sl')}}</div></th>
                                <th><div>{{__('Title')}}</div></th>
                                <th><div>{{__('Subject')}}</div></th>
                                <th><div class="text-nowrap">{{__('Target Audience')}}</div></th>
                                <th><div>{{__('Action')}}</div></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
    <!-- Edit Modal section start -->
    <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">

            </div>
        </div>
    </div>
    <input type="hidden" id="email-template-route" value="{{ route('admin.setting.email-template') }}">

    <!-- Edit Modal section end -->
@endsection
@push('script')
    <script src="{{asset('admin/custom/js/email-template.js')}}"></script>
@endpush
