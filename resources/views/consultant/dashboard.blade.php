@extends('layouts.app')
@push('title')
    {{ __('Home') }}
@endpush
@section('content')
    <div class="p-sm-30 p-15">
        <div class="pb-md-40 pb-20">
            <div class="row rg-20">
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="home-card-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="no">{{ $appointmentsCompleted }}</h4>
                            <div class="icon"><img src="{{asset('assets/images/icon/appointment-complete.svg')}}" alt="" /></div>
                        </div>
                        <p class="info">{{__('Completed Appointments')}}</p>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="home-card-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="no">{{ $appointmentsPending }}</h4>
                            <div class="icon"><img src="{{asset('assets/images/icon/appointment-pending.svg')}}" alt="" /></div>
                        </div>
                        <p class="info">{{__('Pending Appointments')}}</p>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="home-card-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="no">{{ $appointmentsProcessing }}</h4>
                            <div class="icon"><img src="{{asset('assets/images/icon/appointment-processing.svg')}}" alt="" /></div>
                        </div>
                        <p class="info">{{__('Processing Appointments')}}</p>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="home-card-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="no">{{ $appointmentsRefund }}</h4>
                            <div class="icon"><img src="{{asset('assets/images/icon/refund-payment.svg')}}" alt="" /></div>
                        </div>
                        <p class="info">{{__('Refund Appointments')}}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Table -->
        <h4 class="fs-18 fw-700 lh-18 text-title-text pb-20">{{__('Appointment List')}}</h4>
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white">
            <table class="table zTable zTable-last-item-right" id="appointmentDatatable">
                <thead>
                <tr>
                    <th><div class="text-nowrap">{{__('Booking ID')}}</div></th>
                    <th><div>{{__('Student')}}</div></th>
                    <th><div>{{__('Consultant')}}</div></th>
                    <th><div class="text-nowrap">{{__('Date & Time')}}</div></th>
                    <th><div>{{__('Type')}}</div></th>
                    <th><div>{{__('Status')}}</div></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <input type="hidden" value="{{ route('consultant.dashboard') }}" id="consultantIndexRoute">
@endsection

@push('script')
    <script src="{{ asset('consultant/js/dashboard.js') }}"></script>
@endpush
