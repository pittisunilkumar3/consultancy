@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center rg-20 flex-wrap">
        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{$pageTitle}}</h4>
        <a href="{{route('admin.cms-settings.countries.create')}}" class="flipBtn sf-flipBtn-primary flex-shrink-0">
            + {{__('Add Country')}}</a>
    </div>
    <div class="p-sm-30 p-15">
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
            <div class="">
                <table class="table zTable zTable-last-item-right pt-15" id="countryDataTable">
                    <thead>
                        <tr>
                            <th>
                                <div>{{__('Sl')}}</div>
                            </th>
                            <th>
                                <div>{{__('Name')}}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{__('Banner Image')}}</div>
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

    <input type="hidden" id="countries-route" value="{{ route('admin.cms-settings.countries.index') }}">
@endsection
@push('script')
    <script src="{{asset('admin/custom/js/cms/countries.js')}}"></script>
@endpush
