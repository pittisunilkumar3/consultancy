@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')

    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center flex-wrap g-20">
        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{$pageTitle}}</h4>
        <a href="{{route('admin.cms-settings.universities.create')}}" class="flipBtn sf-flipBtn-primary flex-shrink-0">
            + {{__('Add University')}}</a>
    </div>
    <div class="p-sm-30 p-15">
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
            <div class="">
                <table class="table zTable zTable-last-item-right pt-15" id="universityDataTable">
                    <thead>
                        <tr>
                            <th>
                                <div>{{__('Sl')}}</div>
                            </th>
                            <th>
                                <div>{{__('Name')}}</div>
                            </th>
                            <th>
                                <div>{{__('Banner Image')}}</div>
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

    <input type="hidden" id="universities-route" value="{{ route('admin.cms-settings.universities.index') }}">
@endsection
@push('script')
    <script src="{{asset('admin/custom/js/cms/universities.js')}}"></script>
@endpush
