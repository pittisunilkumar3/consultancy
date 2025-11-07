@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-18 fw-700 lh-24">{{__('Appointments List')}}</h4>
        <a href="{{route('admin.consultations.appointments.create')}}"
           class="flipBtn sf-flipBtn-primary flex-shrink-0">{{__('+ Add New')}}</a>
    </div>
    <div class="p-sm-30 p-15 pt-54">
        <!--  -->
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white">
            <div class="zTab-wrap">
                <ul class="nav nav-tabs zTab-reset zTab-one" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active appointmentStatusTab" data-status="All" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-tab-pane" type="button" role="tab" aria-controls="all-tab-pane" aria-selected="true">
                            {{ __('All') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link appointmentStatusTab" data-status="Pending" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending-tab-pane" type="button" role="tab" aria-controls="pending-tab-pane" aria-selected="false">
                            {{ __('Pending') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link appointmentStatusTab" data-status="Processing" id="processing-tab" data-bs-toggle="tab" data-bs-target="#processing-tab-pane" type="button" role="tab" aria-controls="processing-tab-pane" aria-selected="false">
                            {{ __('Processing') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link appointmentStatusTab" data-status="Completed" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed-tab-pane" type="button" role="tab" aria-controls="completed-tab-pane" aria-selected="false">
                            {{ __('Completed') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link appointmentStatusTab" data-status="Refunded" id="refunded-tab" data-bs-toggle="tab" data-bs-target="#refunded-tab-pane" type="button" role="tab" aria-controls="refunded-tab-pane" aria-selected="false">
                            {{ __('Refunded') }}
                        </button>
                    </li>
                </ul>
            </div>
            <div class="tableFilter-wrap">
                <div class="item">
                    <label for="search_key" class="zForm-label-alt">{{__('Search')}}</label>
                    <input type="text" class="form-control zForm-control-alt" name="search_key" id="search_key"
                           placeholder="{{__('Search By student, Booking ID')}}"/>
                </div>
                <div class="item">
                    <label for="consulter" class="zForm-label-alt">{{__('Consulter')}}</label>
                    <select class="sf-select-two" name="consulter_id" id="consulter">
                        <option
                            value="">{{ __('Select Consultant') }}</option>
                        @foreach($consultants as $consultant)
                            <option
                                value="{{ $consultant->id }}">{{ $consultant->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filterBtn">
                    <button class="flipBtn-icon sf-flipBtn-primary" id="filterButton">
                        <div class="item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20" fill="none">
                                <ellipse cx="9.26461" cy="8.99077" rx="6.7712" ry="6.69878" stroke="#121D35"
                                         stroke-width="1.5" stroke-linecap="square"/>
                                <path d="M13.8879 13.9236L17.7235 17.7083" stroke="#121D35" stroke-width="1.5"
                                      stroke-linecap="square"/>
                            </svg>
                            <span>{{__('Filter')}}</span>
                        </div>
                    </button>
                </div>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="all-tab-pane" role="tabpanel" aria-labelledby="all-tab"
                     tabindex="0">
                    @include('admin.consultations.appointments.table', ['status' => 'all'])
                </div>
                <div class="tab-pane fade" id="pending-tab-pane" role="tabpanel" aria-labelledby="pending-tab"
                     tabindex="0">
                    @include('admin.consultations.appointments.table', ['status' => 'pending'])
                </div>
                <div class="tab-pane fade" id="processing-tab-pane" role="tabpanel" aria-labelledby="processing-tab"
                     tabindex="0">
                    @include('admin.consultations.appointments.table', ['status' => 'processing'])
                </div>
                <div class="tab-pane fade" id="completed-tab-pane" role="tabpanel" aria-labelledby="completed-tab"
                     tabindex="0">
                    @include('admin.consultations.appointments.table', ['status' => 'completed'])
                </div>
                <div class="tab-pane fade" id="refunded-tab-pane" role="tabpanel" aria-labelledby="refunded-tab"
                     tabindex="0">
                    @include('admin.consultations.appointments.table', ['status' => 'refunded'])
                </div>
            </div>
        </div>
    </div>

    <!-- Modal section start -->
    <div class="modal fade" id="status-change-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">

            </div>
        </div>
    </div>

    <input type="hidden" id="appointmentIndexRoute" value="{{ route('admin.consultations.appointments.index') }}">
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/appointments.js') }}"></script>
@endpush
