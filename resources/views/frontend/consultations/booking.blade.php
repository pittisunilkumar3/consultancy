@extends('frontend.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <!-- Breadcrumb -->
    <section class="inner-breadcrumb-section">
        <div class="container">
            <div class="inner-breadcrumb-wrap" data-aos="fade-up" data-aos-duration="1000">
                <h4 class="inner-breadcrumb-title">{{$pageTitle}}</h4>
                <ol class="breadcrumb inner-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('frontend')}}">{{__('Home')}}</a></li>
                    <li class="breadcrumb-item"><a href="{{route('consultations.list')}}">{{__('Consultation')}}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{$pageTitle}}</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Consultant Details -->
    <section class="section-gap-top">
        <div class="container">
            <form action="{{route('student.checkout')}}" id="booking-form" method="GET">
                @csrf
                <input type="hidden" name="type" value="consultation">
                <input type="hidden" name="id" value="{{encodeId($consultant->id)}}">
                <div class="consultant-details" data-aos="fade-up" data-aos-duration="1000">
                    <div class="infoPrice">
                        <div class="left">
                            <div class="img"><img class="w-100" src="{{getFileUrl($consultant->image)}}"
                                                  alt="{{$consultant->name}}"/>
                            </div>
                            <div class="text-content">
                                <h4 class="title">{{$consultant->name}}</h4>
                                <p class="info">{{$consultant->professional_title}}</p>
                            </div>
                        </div>
                        <p class="fs-20 fw-600 lh-30 text-red">{{showPrice($consultant->fee)}}
                            /{{__('Hour')}}</p>
                    </div>
                    <div class="row rg-30">
                        <div class="col-lg-6">
                            <label for="inputDateTime" class="zForm-label">{{__('Date')}} <span>*</span></label>
                            <input type="date" name="date" required class="form-control zForm-control"
                                   id="inputDateTime"
                                   placeholder="{{__('Enter Date')}}"/>
                            <span id="date-error" class="text-danger"></span>
                        </div>
                        <div class="col-lg-6">
                            <label for="inputSelectTimeSlot" class="zForm-label">{{ __('Select Time Slot') }}
                                <span>*</span></label>
                            <div class="dropdown slotDropdown">
                                <button class="dropdown-toggle d-flex align-items-center cg-8 dropdownToggle-slot" id="dropdown-text" type="button"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    -- {{__('Select a Time Slot')}} --
                                </button>
                                <ul class="dropdown-menu" id="slot-block">
                                    <li>
                                        <span>{{__('No slot found')}}</span>
                                    </li>
                                </ul>
                                <span id="slot-error" class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label for="" class="zForm-label">{{__('Consultation Type')}}</label>
                            <div class="d-flex align-items-center g-13">
                                <div class="zForm-wrap-radio">
                                    <input required type="radio" checked value="{{CONSULTATION_TYPE_PHYSICAL}}"
                                           class="form-check-input"
                                           id="inputRadioOffline" name="consultationType"/>
                                    <label for="inputRadioOffline">{{__('In Person')}}</label>
                                </div>
                                <div class="zForm-wrap-radio">
                                    <input required type="radio" value="{{CONSULTATION_TYPE_VIRTUAL}}"
                                           class="form-check-input"
                                           id="inputRadioOnline" name="consultationType"/>
                                    <label for="inputRadioOnline">{{__('Virtual')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="linkWrap">
                        <button type="button" id="checkout-btn" class="sf-btn-icon-primary">
                            {{__('Checkout Now')}}
                            <span class="icon">
                                    <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                    <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <section class="section-gap-top">
        <div class="container" data-aos="fade-up" data-aos-duration="1000">
            <!--  -->
            <div class="section-content-wrap">
                <h4 class="section-title text-center">{{__('More Consultant')}}</h4>
            </div>
            <!--  -->
            <div class="row rg-20">
                @foreach($moreConsultants as $moreConsultant)
                    <div class="col-lg-4 col-sm-6">
                        <div class="consultant-item-one">
                            <div class="info-content">
                                <div class="img"><img class="w-100" src="{{getFileUrl($moreConsultant->image)}}"
                                                      alt="{{$moreConsultant->name}}"/></div>
                                <h4 class="title">{{$moreConsultant->name}}</h4>
                                <p class="info">{{$moreConsultant->professional_title}}</p>
                                <p class="price">{{showPrice($moreConsultant->fee)}}/{{__('Hour')}}</p>
                            </div>
                            <div class="linkWrap">
                                <a href="{{route('consultations.details', encodeId($consultant->id))}}" class="sf-btn-icon-primary">
                                    {{__('Book a Consultation')}}
                                    <span class="icon">
                                        <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                        <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <input type="hidden" id="validate-booking-route"
           value="{{route('consultations.booking.validation', encodeId($consultant->id))}}">
    <input type="hidden" id="get-slot-route" value="{{route('consultations.booking.slot', $consultant->id)}}">
@endsection
@push('script')
    <script src="{{asset('frontend/js/booking.js')}}"></script>
@endpush

