@extends('layouts.app')
@push('title')
    {{ __('Dashboard') }}
@endpush
@section('content')
    <div class="p-sm-30 p-15">
        <!--  -->
        <div class=" pb-20">
            <div class="row rg-20">
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="home-card-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="no">{{ $totalPurchasedCourse}}</h4>
                            <div class="icon"><img src="{{ asset('assets/images/icon/course-purchased.svg') }}" alt="" /></div>
                        </div>
                        <p class="info">{{__('Total Course Purchased')}}</p>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="home-card-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="no">{{ $totalPurchasedService }}</h4>
                            <div class="icon"><img src="{{ asset('assets/images/icon/service-purchased.svg') }}" alt="" /></div>
                        </div>
                        <p class="info">{{__('Total Service Purchased')}}</p>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="home-card-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="no">{{ $totalTicketBooking }}</h4>
                            <div class="icon"><img src="{{ asset('assets/images/icon/event-ticket-booked2.svg') }}" alt="" /></div>
                        </div>
                        <p class="info">{{__('Total Event Ticket Booked')}}</p>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="home-card-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="no">{{ showPrice($totalPayment) }}</h4>
                            <div class="icon"><img src="{{ asset('assets/images/icon/total-payment.svg') }}" alt="" /></div>
                        </div>
                        <p class="info">{{__('Total Payment')}}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
            <h4 class="fs-18 lh-24 text-title-text">{{__('Transactions')}}</h4>
            <div class="">
                <table class="table zTable zTable-last-item-right pt-15" id="transactionDataTable">
                    <thead>
                    <tr>
                        <th>
                            <div class="text-nowrap">{{__('Transaction ID')}}</div>
                        </th>
                        <th>
                            <div>{{__('User')}}</div>
                        </th>
                        <th>
                            <div>{{__('Type')}}</div>
                        </th>
                        <th>
                            <div>{{__('Purpose')}}</div>
                        </th>
                        <th>
                            <div>{{__('Amount')}}</div>
                        </th>
                        <th>
                            <div class="text-nowrap">{{__('Payment Method')}}</div>
                        </th>
                        <th>
                            <div class="text-nowrap">{{__('Payment Time')}}</div>
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

    <input type="hidden" id="transaction-route" value="{{ route('student.transactions') }}">

    <div class="modal fade" id="view-invoice-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15 viewInvoiceModal">

            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{asset('admin/custom/js/transactions.js')}}"></script>
@endpush
