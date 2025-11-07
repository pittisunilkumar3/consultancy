@extends('layouts.app')
@push('title')
    {{ __($pageTitle) }}
@endpush
@section('content')
    <div class="p-30">
        <div class="checkout-wrap">
            <form action="{{ route('student.checkout-pay') }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="type" name="type" value="{{$type}}">
                <input type="hidden" id="slug" name="slug" value="{{$slug}}">
                <div class="row rg-20 pb-20">
                    <div class="col-lg-5 col-md-6">
                        <div class="bd-one bd-c-stroke bd-ra-8 p-sm-25 p-15 bg-white">
                            <!--  -->
                            <h4 class="fs-18 fw-500 lh-22 text-title-text pb-12">{{ __('Payment Details') }}</h4>
                            <!--  -->
                            <ul class="zList-pb-12">
                                @if($type == 'course')
                                    <li class="d-flex justify-content-between align-items-center">
                                        <p class="fs-14 fw-400 lh-16 text-para-text">{{ __('Checkout Item') }}</p>
                                        <p class="fs-14 fw-400 lh-16 text-title-text">{{__('Course Purchase')}}</p>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <p class="fs-14 fw-400 lh-16 text-para-text">{{ __('Course Title') }}</p>
                                        <p class="fs-14 fw-400 lh-16 text-title-text">{{$itemTitle}}</p>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <p class="fs-14 fw-400 lh-16 text-para-text">{{ __('Duration') }}</p>
                                        <p class="fs-14 fw-400 lh-16 text-title-text">{{$course->duration}} {{__('Days')}}</p>
                                    </li>
                                @elseif($type == 'event')
                                    <li class="d-flex justify-content-between align-items-center">
                                        <p class="fs-14 fw-400 lh-16 text-para-text">{{ __('Event Title') }}</p>
                                        <p class="fs-14 fw-400 lh-16 text-title-text">{{$itemTitle}}</p>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <p class="fs-14 fw-400 lh-16 text-para-text">{{ __('Checkout Item') }}</p>
                                        <p class="fs-14 fw-400 lh-16 text-title-text">{{__('Event Booking')}}</p>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <p class="fs-14 fw-400 lh-16 text-para-text">{{ __('Date') }}</p>
                                        <p class="fs-14 fw-400 lh-16 text-title-text">{{$event->date_time}}</p>
                                    </li>
                                @elseif($type == 'service')
                                    <li class="d-flex justify-content-between align-items-center">
                                        <p class="fs-14 fw-400 lh-16 text-para-text">{{ __('Service Title') }}</p>
                                        <p class="fs-14 fw-400 lh-16 text-title-text">{{$itemTitle}}</p>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <p class="fs-14 fw-400 lh-16 text-para-text">{{ __('Checkout Item') }}</p>
                                        <p class="fs-14 fw-400 lh-16 text-title-text">{{__('Service Order')}}</p>
                                    </li>
                                @elseif($type == 'service-invoice')
                                    <li class="d-flex justify-content-between align-items-center">
                                        <p class="fs-14 fw-400 lh-16 text-para-text">{{ __('Service/Details') }}</p>
                                        <p class="fs-14 fw-400 lh-16 text-title-text">{{$itemTitle}}</p>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <p class="fs-14 fw-400 lh-16 text-para-text">{{ __('Checkout Item') }}</p>
                                        <p class="fs-14 fw-400 lh-16 text-title-text">{{__('Service Invoice')}}</p>
                                    </li>
                                @elseif($type == 'consultation')
                                    <li class="d-flex justify-content-between align-items-center">
                                        <p class="fs-14 fw-400 lh-16 text-para-text">{{ __('Consulter') }}</p>
                                        <input type="hidden" name="consulter_id" value="{{$id}}">
                                        <input type="hidden" name="consultation_slot_id" value="{{$consultation_slot_id}}">
                                        <input type="hidden" name="date" value="{{$date}}">
                                        <input type="hidden" name="consultation_type" value="{{$consultationType}}">
                                        <p class="fs-14 fw-400 lh-16 text-title-text">{{$itemTitle}}</p>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <p class="fs-14 fw-400 lh-16 text-para-text">{{ __('Date') }}</p>
                                        <p class="fs-14 fw-400 lh-16 text-title-text">{{$date}}</p>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <p class="fs-14 fw-400 lh-16 text-para-text">{{ __('Slot Time') }}</p>
                                        <p class="fs-14 fw-400 lh-16 text-title-text">{{$slot}}</p>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <p class="fs-14 fw-400 lh-16 text-para-text">{{ __('Consultation Type') }}</p>
                                        <p class="fs-14 fw-400 lh-16 text-title-text">{{getConsultationType($consultationType)}}</p>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <p class="fs-14 fw-400 lh-16 text-para-text">{{ __('Checkout Item') }}</p>
                                        <p class="fs-14 fw-400 lh-16 text-title-text">
                                            {{__('Consultation Booking')}}
                                        </p>
                                    </li>
                                @endif

                                <li class="d-flex justify-content-between align-items-center">
                                    <p class="fs-14 fw-400 lh-16 text-para-text">{{ __('Amount') }}</p>
                                    <p class="fs-14 fw-400 lh-16 text-title-text">
                                        {{showPrice($itemPrice)}}
                                        <input type="hidden" id="amount" value="{{$itemPrice}}">
                                    </p>
                                </li>
                            </ul>
                            <div class="pt-20">
                                <label for="gateway_currency"
                                       class="zForm-label-alt">{{ __('Gateway Currency') }}<span>*</span></label>
                                <select required class="form-control zForm-control-alt" id="gateway_currency"
                                        name="gateway_currency">

                                </select>
                            </div>
                            <!--  -->
                            <div class="d-none mt-20" id="bankSection">
                                <h4 class="fs-18 fw-500 lh-22 text-title-text pb-12">{{ __('Bank Deposit') }}</h4>
                                <div class="bd-c-stroke-2 bd-one m-0 py-20 rg-20 rounded row">
                                    <div class="col-md-12">
                                        <label for="bank_id"
                                               class="zForm-label-alt">{{ __('Bank Name') }}<span>*</span></label>
                                        <select required class="form-control zForm-control-alt" id="bank_id"
                                                name="bank_id">
                                            @foreach ($banks as $bank)
                                                <option value="{{ $bank->id }}"
                                                        data-details="{{ nl2br($bank->details) }}">
                                                    {{ $bank->name }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="file-upload-one">
                                            <p class="fs-14 fw-700 lh-24 text-para-text pb-8">{{ __('Upload Deposit Slip (png, jpg)') }}<span>*</span></p>
                                            <label for="bank_slip">
                                                <p class="fileName fs-14 fw-500 lh-24 text-para-text">{{__('Choose File to upload')}}</p>
                                                <p class="fs-15 fw-700 lh-28 text-white">{{__('Browse File')}}</p>
                                            </label>
                                            <input type="file" name="bank_slip" id="bank_slip" class="fileUploadInput invisible position-absolute">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-6">
                        <div class="row rg-20">
                            @if ($gateways != null && count($gateways) > 0)
                                @foreach ($gateways as $singleGateway)
                                    <div class="col-xl-4 col-sm-6">
                                        <div class="bd-one bd-c-stroke-2 bd-ra-10 bg-white p-sm-20 p-10 payment-item">
                                            <h6 class="py-8 p-sm-20 p-10 bd-ra-20 mb-20 bg-secondary text-center fs-14 fw-400 lh-16 text-title-text">
                                                {{ $singleGateway->title }}
                                            </h6>
                                            <div class="text-center mb-20">
                                                <img src="{{ asset($singleGateway->image) }}" alt=""/>
                                            </div>
                                            <div class="position-relative">
                                                <input {{$loop->first ? 'checked' : ''}} type="radio"
                                                       id="gateway_{{ $singleGateway->id }}" name="gateway"
                                                       value="{{ $singleGateway->slug }}"
                                                       class="paymentGateway position-absolute invisible"/>
                                                <label for="gateway_{{ $singleGateway->id }}"
                                                       class="paymentGatewaySelectLabel">{{ __('Select') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="cg-10 d-flex justify-content-end mt-18">
                        <button type="submit" class="sf-btn-primary">
                            <div class="spinner-border w-25 h-25 d-none" role="status">
                                <span class="visually-hidden"></span>
                            </div>
                            {{ __('Pay Now') }}
                            <span class="ms-1" id="gatewayCurrencyAmount"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <input type="hidden" id="getCurrencyByGatewayRoute" value="{{ route('gateway-currency') }}">
@endsection

@push('script')
    <script src="{{ asset('frontend/js/checkout.js') }}"></script>
@endpush
