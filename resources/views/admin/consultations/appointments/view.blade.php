@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{__('Appointment Details')}}</h4>
        <a href="{{route(getPrefix().'.consultations.appointments.index')}}"
           class="flipBtn sf-flipBtn-secondary flex-shrink-0">{{__('Back')}}</a>
    </div>
    <div class="p-sm-30 p-15">
        <div class="row rg-20">
            <section class="">
                <div class="consultant-details" data-background="{{asset('assets/images/why-choose-us.png')}}">
                    <div class="row rg-30">
                        <div class="col-md-6">
                            <label class="zForm-label-alt">{{ __('Consultant') }}</label>
                            <input disabled type="text" value="{{$appointment->consulter->name}}"
                                   class="form-control zForm-control pe-none" id="consultant"/>
                        </div>
                        <div class="col-md-6">
                            <label class="zForm-label-alt">{{ __('Student') }}</label>
                            <input disabled type="text" value="{{$appointment->user->name}}"
                                   class="form-control zForm-control pe-none"
                                   id="student"/>
                        </div>
                        <div class="col-lg-6">
                            <label class="zForm-label-alt">{{__('Date')}}</label>
                            <input disabled type="text" value="{{$appointment->date}}"
                                   class="form-control zForm-control pe-none"
                                   id="inputDateTime"/>
                        </div>
                        <div class="col-lg-6">
                            <label class="zForm-label-alt">{{__('Time Slot')}}</label>
                            <input disabled type="text"
                                   value="{{$appointment->slot->start_time.'-'.$appointment->slot->end_time}}"
                                   class="form-control zForm-control pe-none" id="slot"/>
                        </div>
                        <div class="col-md-6">
                            <label class="zForm-label-alt">{{ __('Gateway') }}</label>
                            <input disabled type="text" value="{{$appointment->payment?->gateway?->title}}"
                                   class="form-control zForm-control pe-none" id="gateway"/>
                        </div>
                        <div class="col-md-6">
                            <label
                                class="zForm-label-alt">{{ __('Payment Currency') }}</label>
                            <input disabled type="text" value="{{$appointment->payment?->payment_currency}}"
                                   class="form-control zForm-control pe-none" id="gateway_currency"/>
                        </div>
                        <div class="col-lg-6">
                            <label class="zForm-label-alt">{{__('Consultation Type')}}</label>
                            <input disabled type="text" value="{{getConsultationType($appointment->consultation_type)}}"
                                   class="form-control zForm-control pe-none" id="consultation_type"/>
                        </div>
                        @if($appointment->consultation_type == CONSULTATION_TYPE_VIRTUAL)
                            <div class="col-lg-6">
                                <label class="zForm-label-alt">{{__('Meeting Provider')}}</label>
                                <input disabled type="text"
                                       value="{{$appointment->meeting_provider->name ?? __('N/A')}}"
                                       class="form-control zForm-control pe-none" id="meeting_provider"/>
                            </div>
                            <div class="col-lg-6">
                                <label class="zForm-label-alt">{{__('Meeting Link')}}</label>
                                <input disabled type="text" value="{{$appointment->consultation_link ?? __('N/A')}}"
                                       class="form-control zForm-control" id="meeting_provider"/>
                            </div>
                        @endif
                        <div class="col-lg-6">
                            <label class="zForm-label-alt">{{__('Status')}}</label>
                            <input disabled type="text"
                                   value="@switch($appointment->status)
                                      @case(STATUS_PENDING) Pending @break
                                      @case(STATUS_PROCESSING) Processing @break
                                      @case(STATUS_ACTIVE) Completed @break
                                      @case(STATUS_REFUNDED) Refunded @break
                                      @default Unknown
                                  @endswitch"
                                   class="form-control zForm-control pe-none" id="status"/>
                        </div>
                        <div class="col-lg-6">
                            <label class="zForm-label-alt">{{__('Payment Status')}}</label>
                            @if($appointment->is_free)
                                <input disabled type="text"
                                       value="{{__('Free')}}"
                                       class="form-control zForm-control pe-none" id="payment_status"/>
                            @else
                                <input disabled type="text"
                                       value="@switch($appointment->payment?->payment_status)
                                      @case(PAYMENT_STATUS_PENDING) Pending @break
                                      @case(PAYMENT_STATUS_PAID) Paid @break
                                      @default Unknown
                                  @endswitch"
                                       class="form-control zForm-control pe-none" id="payment_status"/>
                            @endif
                        </div>
                        @if($appointment->payment?->paidByUser?->role == USER_ROLE_ADMIN)
                            <div class="col-lg-6">
                                <label class="zForm-label-alt">{{__('Paid By')}}</label>
                                <input disabled type="text" value="{{ __('Admin')  }}"
                                       class="form-control zForm-control pe-none" id="paid_by"/>
                            </div>
                        @endif
                        <div class="col-lg-6">
                            <label class="zForm-label-alt">{{__('Transaction ID')}}</label>
                            <input disabled type="text" value="{{ $appointment->payment?->tnxId }}"
                                   class="form-control zForm-control pe-none" id="transaction_id"/>
                        </div>
                        <div class="col-lg-6">
                            <label class="zForm-label-alt">{{__('Payment Time')}}</label>
                            <input disabled type="text"
                                   value="{{ Carbon\Carbon::parse($appointment->payment?->created_at)->format('Y-m-d - H:i:s ') }}"
                                   class="form-control zForm-control pe-none" id="payment_time"/>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
