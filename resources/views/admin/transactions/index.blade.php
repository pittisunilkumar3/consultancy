@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{$pageTitle}}</h4>
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
    <div class="p-sm-30 p-15">
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
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
    <!-- Page content area end -->

    <div class="modal fade" id="view-invoice-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15 viewInvoiceModal">

            </div>
        </div>
    </div>

    <input type="hidden" id="transaction-route" value="{{ route(getPrefix().'.transactions') }}">

@endsection
@push('script')
    <script src="{{asset('admin/custom/js/transactions.js')}}"></script>
@endpush
