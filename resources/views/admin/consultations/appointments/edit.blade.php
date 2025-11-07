@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{__('Edit Appointment')}}</h4>
        <a href="{{route(getPrefix().'.consultations.appointments.index')}}"
           class="flipBtn sf-flipBtn-secondary flex-shrink-0">{{__('Back')}}</a>
    </div>
    <div class="p-sm-30 p-15">
        <div class="row rg-20">
            <form class="ajax reset"
                  action="{{ route(getPrefix().'.consultations.appointments.update', encodeId($appointment->id)) }}"
                  method="post"
                  data-handler="commonResponseRedirect"
                  data-redirect-url="{{route(getPrefix().'.consultations.appointments.index')}}">
                @csrf
                <section class="section-gap bg-white">
                    <div class="container">
                        <div class="consultant-details" data-background="{{asset('assets/images/why-choose-us.png')}}">
                            @include('admin.consultations.appointments.form', ['appointment' => $appointment])
                            <div class="linkWrap">
                                <button type="submit" id="checkout-btn" class="flipBtn sf-flipBtn-primary">
                                    <div class="spinner-border w-25 h-25 ml-10 d-none" role="status">
                                        <span class="visually-hidden"></span>
                                    </div>
                                    {{__('Update Appointment')}}
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
    <!-- Page content area end -->
    <input type="hidden" id="getCurrencyByGatewayRoute" value="{{ route('gateway-currency') }}">
    <input type="hidden" id="get-slot-route" value="{{route('consultations.booking.slot', ['CONSULTANT_ID'])}}">
@endsection
@push('script')
    <script src="{{asset('admin/custom/js/booking.js')}}"></script>
@endpush
