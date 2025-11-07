@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="p-sm-30 p-15">
        <!--  -->
        <div class="row rg-20">
            <div class="col-xll-2 col-lg-5 col-md-6">
                <div class="bg-white p-sm-25 p-15 bd-ra-5">
                    <ul class="zList-pb-5">
                        <li class="d-flex g-8">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 23 24"
                                     fill="none">
                                    <path
                                        d="M7.1875 5.23926H15.3333C16.1272 5.23926 16.7708 5.88285 16.7708 6.67676V8.11426"
                                        stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path d="M14.375 12.9059H8.625" stroke="#636370" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M11.5 16.7393H8.625" stroke="#636370" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path
                                        d="M17.7263 2.41667L6.04481 2.41669C5.56856 2.41669 5.08144 2.48657 4.69646 2.76916C3.47582 3.66517 2.53513 5.66351 4.43478 7.46766C4.96816 7.97423 5.71371 8.15787 6.44659 8.15787H17.5216C18.2821 8.15787 19.6458 8.26671 19.6458 10.5887V17.732C19.6458 19.859 17.9307 21.5833 15.815 21.5833H7.16107C5.04928 21.5833 3.51339 20.0908 3.39207 17.8183L3.35997 5.45111"
                                        stroke="#636370" stroke-width="1.5" stroke-linecap="round"></path>
                                </svg>
                            </div>
                            <p class="fs-14 fw-500 lh-28 text-para-text d-flex g-5">
                                <span class="flex-shrink-0 text-title-text fw-600">{{__('Event Title')}} :</span>
                                {{ $eventData->title }}</p>
                        </li>
                        <li class="d-flex g-8">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                     fill="none">
                                    <path
                                        d="M17.5 8.33325C17.5 14.1666 10 19.1666 10 19.1666C10 19.1666 2.5 14.1666 2.5 8.33325C2.5 6.34413 3.29018 4.43647 4.6967 3.02995C6.10322 1.62343 8.01088 0.833252 10 0.833252C11.9891 0.833252 13.8968 1.62343 15.3033 3.02995C16.7098 4.43647 17.5 6.34413 17.5 8.33325Z"
                                        stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                    <path
                                        d="M10 10.8333C11.3807 10.8333 12.5 9.71396 12.5 8.33325C12.5 6.95254 11.3807 5.83325 10 5.83325C8.61929 5.83325 7.5 6.95254 7.5 8.33325C7.5 9.71396 8.61929 10.8333 10 10.8333Z"
                                        stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <p class="fs-14 fw-500 lh-28 text-para-text d-flex g-5">
                                <span class="flex-shrink-0 text-title-text fw-600">{{__('Location')}} :</span>
                                {{ $eventData->location }}
                            </p>
                        </li>
                        @if(count($eventData->countryName))
                            <li class="d-flex g-8">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/icon/globe.svg') }}" alt=""/>
                                </div>
                                <p class="fs-14 fw-500 lh-28 text-para-text d-flex g-5">
                                    <span class="flex-shrink-0 text-title-text fw-600">{{__('Country')}} :</span>
                                    @foreach($eventData->countryName as $level)
                                        <span>{{ $level }}</span>@if(!$loop->last)
                                            ,
                                        @endif
                                    @endforeach</p>
                            </li>
                        @endif
                        @if(count($eventData->universityName))
                            <li class="d-flex g-8">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/icon/world-ranking.svg') }}" alt=""/>
                                </div>
                                <p class="fs-14 fw-500 lh-28 text-para-text d-flex g-5">
                                    <span class="flex-shrink-0 text-title-text fw-600">{{__('University')}} :</span>
                                    @foreach($eventData->universityName as $level)
                                        <span>{{ $level }}</span>@if(!$loop->last)
                                            ,
                                        @endif
                                    @endforeach</p>
                            </li>
                        @endif
                        @if(count($eventData->studyLevelsName))
                            <li class="d-flex g-8">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="23" height="24" viewBox="0 0 23 24"
                                         fill="none">
                                        <g clip-path="url(#clip0_2350_5356)">
                                            <path
                                                d="M22.8026 16.2851L21.0212 14.5036V8.39283L22.1842 7.97352C22.4512 7.87722 22.6292 7.62402 22.6295 7.34014C22.6297 7.05635 22.452 6.80279 22.1851 6.70617L11.5441 2.85251C11.396 2.79884 11.2335 2.79884 11.0853 2.85251L0.444374 6.70617C0.178507 6.80247 0.00108217 7.05446 4.14973e-06 7.33722C-0.000984037 7.61997 0.174599 7.87326 0.439702 7.9715L4.3668 9.42625V13.8666C4.3668 14.0044 4.40902 14.139 4.48785 14.252C4.5312 14.3141 4.94179 14.8748 5.99304 15.404C7.32112 16.0725 9.06302 16.4115 11.1703 16.4115C13.276 16.4115 15.0267 16.0732 16.3738 15.406C17.4354 14.8801 17.8596 14.3253 17.9046 14.2638C17.9886 14.1485 18.034 14.0095 18.034 13.8667V9.46972L19.6737 8.87856V14.5036L17.8922 16.2851C17.7658 16.4115 17.6948 16.5828 17.6948 16.7615C17.6948 16.9402 17.7658 17.1116 17.8922 17.2379L19.6737 19.0194V20.5141C19.6737 20.8862 19.9753 21.1879 20.3474 21.1879C20.7195 21.1879 21.0212 20.8862 21.0212 20.5141V19.0194L22.8027 17.2379C23.0658 16.9749 23.0658 16.5482 22.8026 16.2851ZM11.3147 4.2026L19.9731 7.33825L11.1735 10.5107L2.63267 7.34683L11.3147 4.2026ZM16.6864 13.5981C14.3831 15.6707 7.53861 15.433 5.71433 13.6048V9.92546L10.9362 11.8599C11.0191 11.873 11.0973 11.9445 11.3988 11.8619L16.6864 9.95564L16.6864 13.5981ZM20.3474 17.7875L19.3213 16.7615L20.3474 15.7355L21.3734 16.7615L20.3474 17.7875Z"
                                                fill="#636370"
                                            />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_2350_5356">
                                                <rect width="23" height="23" fill="white" transform="translate(0 0.5)"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>
                                <p class="fs-14 fw-500 lh-28 text-para-text d-flex g-5">
                                    <span class="flex-shrink-0 text-title-text fw-600">{{__('Study Level')}} :</span>
                                    @foreach($eventData->studyLevelsName as $level)
                                        <span>{{ $level }}</span>@if(!$loop->last)
                                            ,
                                        @endif
                                    @endforeach</p>
                            </li>
                        @endif
                        <li class="d-flex g-8">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                     fill="none">
                                    <path
                                        d="M15.8333 3.33325H4.16667C3.24619 3.33325 2.5 4.07944 2.5 4.99992V16.6666C2.5 17.5871 3.24619 18.3333 4.16667 18.3333H15.8333C16.7538 18.3333 17.5 17.5871 17.5 16.6666V4.99992C17.5 4.07944 16.7538 3.33325 15.8333 3.33325Z"
                                        stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                    <path d="M13.3335 1.66675V5.00008" stroke="#636370" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M6.6665 1.66675V5.00008" stroke="#636370" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M2.5 8.33325H17.5" stroke="#636370" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <p class="fs-14 fw-500 lh-28 text-para-text d-flex g-5">
                                <span class="flex-shrink-0 text-title-text fw-600">{{__('Date & Time')}} :</span>
                                {{ \Carbon\Carbon::parse($eventData->date_time)->format('Y-m-d , h:i:s') }}</p>
                        </li>
                        <li class="d-flex g-8">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                     fill="none">
                                    <path
                                        d="M1.66699 8.33325C1.66699 5.97623 1.66699 4.79772 2.39923 4.06549C3.13146 3.33325 4.30997 3.33325 6.66699 3.33325H13.3337C15.6907 3.33325 16.8692 3.33325 17.6014 4.06549C18.3337 4.79772 18.3337 5.97623 18.3337 8.33325V11.6666C18.3337 14.0236 18.3337 15.2021 17.6014 15.9343C16.8692 16.6666 15.6907 16.6666 13.3337 16.6666H6.66699C4.30997 16.6666 3.13146 16.6666 2.39923 15.9343C1.66699 15.2021 1.66699 14.0236 1.66699 11.6666V8.33325Z"
                                        stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                    <path
                                        d="M5 8.25366C5 4.46665 10 8.32521 10 10.8334H7.08333C5.636 10.8334 5 9.55833 5 8.25366Z"
                                        stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                    <path
                                        d="M15 8.25366C15 4.46665 10 8.32521 10 10.8334H12.9167C14.364 10.8334 15 9.55833 15 8.25366Z"
                                        stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                    <path d="M10 3.33325V16.6666" stroke="#636370" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M1.66699 10.8333H18.3337" stroke="#636370" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12.5 13.3333L10 10.8333L7.5 13.3333" stroke="#636370" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            @if($eventData->type == EVENT_TYPE_PHYSICAL)
                                <p class="fs-14 fw-500 lh-28 text-para-text d-flex g-5">
                                    <span class="flex-shrink-0 text-title-text fw-600">{{__('Event Type')}} :</span>
                                    {{__('Physical')}}</p>
                            @elseif($eventData->type == EVENT_TYPE_VIRTUAL)
                                <p class="fs-14 fw-500 lh-28 text-para-text d-flex g-5">
                                    <span class="flex-shrink-0 text-title-text fw-600">{{__('Event Type')}} :</span>
                                    {{__('Virtual')}}</p>
                            @endif
                        </li>
                        <li class="d-flex g-8">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                     fill="none">
                                    <path
                                        d="M18.3337 10.0001C18.3337 14.6024 14.6027 18.3334 10.0003 18.3334C5.39795 18.3334 1.66699 14.6024 1.66699 10.0001C1.66699 5.39771 5.39795 1.66675 10.0003 1.66675C14.6027 1.66675 18.3337 5.39771 18.3337 10.0001Z"
                                        stroke="#636370" stroke-width="1.5"/>
                                    <path
                                        d="M12.2588 8.38433C12.1762 7.74878 11.4465 6.72193 10.1343 6.7219C8.60966 6.72188 7.96812 7.5663 7.83795 7.9885C7.63486 8.55325 7.67548 9.71433 9.46257 9.84091C11.6965 9.99925 12.5914 10.2629 12.4776 11.6301C12.3637 12.9972 11.1184 13.2926 10.1343 13.2608C9.15016 13.2292 7.54002 12.7772 7.47754 11.5612M9.97816 5.83179V6.72493M9.97816 13.2527V14.1651"
                                        stroke="#636370" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <p class="fs-14 fw-500 lh-28 text-para-text d-flex g-5">
                                <span class="flex-shrink-0 text-title-text fw-600">{{__("Price")}} :</span>
                                {{ showPrice($eventData->price) }}</p>
                        </li>
                        @if($paymentData->subtotal > 0)
                            <li class="d-flex g-8">
                                <div class="icon"><img src="{{asset('assets/images/icon/payment.svg')}}"
                                                       alt=""/>
                                </div>
                                <p class="fs-14 fw-500 lh-28 text-para-text d-flex g-5">
                                    <span class="flex-shrink-0 text-title-text fw-600">{{__('Gateway Name')}} :</span>
                                    {{ $paymentData->gateway->title }}</p>
                            </li>
                            <li class="d-flex g-8">
                                <div class="icon"><img src="{{asset('assets/images/icon/currency.svg')}}"
                                                       alt=""/>
                                </div>
                                <p class="fs-14 fw-500 lh-28 text-para-text d-flex g-5">
                                    <span
                                        class="flex-shrink-0 text-title-text fw-600"> {{__('Payment Currency')}} :</span>
                                    {{ $paymentData->payment_currency }}</p>
                            </li>
                            <li class="d-flex g-8">
                                <div class="icon"><img src="{{asset('assets/images/icon/transaction.svg')}}"
                                                       alt=""/>
                                </div>
                                <p class="fs-14 fw-500 lh-28 text-para-text d-flex g-5">
                                    <span class="flex-shrink-0 text-title-text fw-600">{{__('Transaction ID')}} :</span>
                                    {{ $paymentData->tnxId}}</p>
                            </li>
                            <li class="d-flex g-8">
                                <div class="icon"><img src="{{asset('assets/images/icon/time-2.svg')}}"
                                                       alt=""/>
                                </div>
                                <p class="fs-14 fw-500 lh-28 text-para-text d-flex g-5">
                                    <span class="flex-shrink-0 text-title-text fw-600">{{__('Payment Time')}} :</span>
                                    {{ Carbon\Carbon::parse($paymentData->payment_time)->format('Y-m-d - H:i:s ') }}</p>
                            </li>
                        @endif
                    </ul>

                    @if($paymentData->payment_status == PAYMENT_STATUS_PAID && ($paymentData->transaction))
                        <div class="mt-20">
                            <button type="button"
                                    onclick="getEditModal('{{ route(getPrefix().'.transactions.details', encodeId($paymentData->transaction->id)) }}',  '#view-invoice-modal')"
                                    class="flipBtn sf-flipBtn-primary flex-shrink-0">{{ __('Download Invoice') }}</button>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-xll-10 col-lg-7 col-md-6">
                <div class="event-details-content">
                    <div class="img"><img src="{{ getFileUrl($eventData->image) }}" alt=""/></div>
                    <div>
                        {!! $eventData->description !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="view-invoice-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15 viewInvoiceModal">

            </div>
        </div>
    </div>
@endsection

