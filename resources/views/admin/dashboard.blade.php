@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush

@section('content')
    <div class="p-sm-30 p-15">
        <div class="pb-md-40 pb-20">
            <div class="row rg-20">
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="home-card-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="no">{{ $totalEvent }}</h4>
                            <div class="icon"><img src="{{ asset('assets/images/icon/service-purchased.svg') }}" alt="" /></div>
                        </div>
                        <p class="info">{{__('Total Event')}}</p>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="home-card-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="no">{{ $totalService }}</h4>
                            <div class="icon"><img src="{{ asset('assets/images/icon/event-ticket-booked') }}.svg" alt="" /></div>
                        </div>
                        <p class="info">{{__('Total Service')}}</p>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="home-card-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="no">{{ $totalAppointment }}</h4>
                            <div class="icon"><img src="{{ asset('assets/images/icon/refund-payment.svg') }}" alt="" /></div>
                        </div>
                        <p class="info">{{__('Total Appointments')}}</p>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="home-card-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="no">{{ $totalCourse }}</h4>
                            <div class="icon"><img src="{{ asset('assets/images/icon/course-purchased.svg') }}" alt="" /></div>
                        </div>
                        <p class="info">{{__('Total Course')}}</p>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="home-card-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="no">{{ $totalStudent }}</h4>
                            <div class="icon"><img src="{{ asset('assets/images/icon/total-student.svg') }}" alt="" /></div>
                        </div>
                        <p class="info">{{__('Total Student')}}</p>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="home-card-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="no">{{ $totalStaff }}</h4>
                            <div class="icon"><img src="{{ asset('assets/images/icon/total-staff.svg') }}" alt="" /></div>
                        </div>
                        <p class="info">{{__('Total Staff')}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="pb-30">
            <div class="row rg-20">
                <div class="col-lg-6">
                    <div class="p-25 bd-one bd-c-stroke bd-ra-10 bg-white h-100">
                        <div>
                            <h4 class="fs-18 fw-500 lh-22 text-title-text">{{__('Top 5 Event')}}</h4>
                            <div id="event-order-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-25 bd-one bd-c-stroke bd-ra-10 bg-white h-100">
                        <div>
                            <h4 class="fs-18 fw-500 lh-22 text-title-text">{{__('Top 5 Consultant')}}</h4>
                             <div id="consultant-order-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-25 bd-one bd-c-stroke bd-ra-10 bg-white h-100">
                        <div>
                            <h4 class="fs-18 fw-500 lh-22 text-title-text">{{__('Service Order History')}}</h4>
                            <div id="service-order-chart"></div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-25 bd-one bd-c-stroke bd-ra-10 bg-white h-100">
                        <div>
                            <h4 class="fs-18 fw-500 lh-22 text-title-text">{{__('Course Order History')}}</h4>
                            <div id="course-order-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="service-month-category-list" value="{{ $serviceMonth }}">
        <input type="hidden" id="yearly-chart-service-amount" value="{{ $yearlyChartDataService }}">
        <input type="hidden" id="course-month-category-list" value="{{ $courseMonth }}">
        <input type="hidden" id="yearly-chart-course-amount" value="{{ $yearlyChartDataCourse }}">
        <input type="hidden" id="event-order-list" value="{{ $eventOrder }}">
        <input type="hidden" id="event-name-list" value="{{ $eventNames }}">
        <input type="hidden" id="consultant-name-list" value="{{ $consultantNames }}">
        <input type="hidden" id="consultant-order-list" value="{{ $consultantOrder }}">
    </div>

@endsection

@push('script')
    <script src="{{ asset('assets/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin/custom/js/charts.js') }}"></script>
@endpush
