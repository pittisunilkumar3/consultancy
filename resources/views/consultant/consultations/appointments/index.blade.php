@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-18 fw-700 lh-24">{{__('Appointments List')}}</h4>
        <div class="search-one flex-grow-1 max-w-258">
            <input type="text" id="search-key" placeholder="{{__('Search here')}}...">
            <button class="icon">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8.71401 15.7857C12.6194 15.7857 15.7854 12.6197 15.7854 8.71428C15.7854 4.80884 12.6194 1.64285 8.71401 1.64285C4.80856 1.64285 1.64258 4.80884 1.64258 8.71428C1.64258 12.6197 4.80856 15.7857 8.71401 15.7857Z" stroke="#707070" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M18.3574 18.3571L13.8574 13.8571" stroke="#707070" stroke-width="1.35902" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </button>
        </div>
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

    <input type="hidden" id="appointmentIndexRoute" value="{{ route('consultant.consultations.appointments.index') }}">
@endsection

@push('script')
    <script src="{{ asset('consultant/js/consultant-appointments.js') }}"></script>
@endpush
