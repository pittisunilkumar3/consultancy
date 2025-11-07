@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-18 fw-700 lh-24">{{__('Service Order Invoice List')}}</h4>
        @if(auth()->user()->role != USER_ROLE_STUDENT)
            <button type="button" data-bs-toggle="modal" data-bs-target="#add-modal"
                    class="flipBtn sf-flipBtn-primary flex-shrink-0">{{__('+ Create New')}}</button>
        @endif
    </div>
    <div class="p-sm-30 p-15 pt-54">
        <!--  -->
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white">
            <div class="zTab-wrap">
                <ul class="nav nav-tabs zTab-reset zTab-one" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active serviceOrderInvoiceStatusTab" data-status="All" id="all-tab"
                                data-bs-toggle="tab" data-bs-target="#all-tab-pane" type="button" role="tab"
                                aria-controls="all-tab-pane" aria-selected="true">
                            {{ __('All') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link serviceOrderInvoiceStatusTab" data-status="Paid" id="paid-tab"
                                data-bs-toggle="tab" data-bs-target="#paid-tab-pane" type="button" role="tab"
                                aria-controls="paid-tab-pane" aria-selected="false">
                            {{ __('Paid') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link serviceOrderInvoiceStatusTab" data-status="Pending" id="pending-tab"
                                data-bs-toggle="tab" data-bs-target="#pending-tab-pane" type="button" role="tab"
                                aria-controls="pending-tab-pane" aria-selected="false">
                            {{ __('Pending') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link serviceOrderInvoiceStatusTab" data-status="Cancelled" id="cancelled-tab"
                                data-bs-toggle="tab" data-bs-target="#cancelled-tab-pane" type="button" role="tab"
                                aria-controls="cancelled-tab-pane" aria-selected="false">
                            {{ __('Cancelled') }}
                        </button>
                    </li>
                </ul>
            </div>
            <div class="tableFilter-wrap">
                <div class="item">
                    <label for="search_key" class="zForm-label-alt">{{__('Search')}}</label>
                    <input type="text" class="form-control zForm-control-alt" name="search_key" id="search_key"
                           placeholder="{{__('Search By student, Invoice/Order ID')}}"/>
                </div>
                <div class="item">
                    <label for="service-search" class="zForm-label-alt">{{__('Service')}}</label>
                    <select class="sf-select-two" name="service_id" id="service_id">
                        <option value="">{{ __('Select Service') }}</option>
                        @foreach($services as $service)
                            <option
                                value="{{ $service->id }}">{{ $service->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filterBtn">
                    <button class="flipBtn-icon sf-flipBtn-primary" id="filterButton">
                        <div class="item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20" fill="none">
                                <ellipse cx="9.26461" cy="8.99077" rx="6.7712" ry="6.69878" stroke="#121D35"
                                         stroke-width="1.5" stroke-linecap="square"/>
                                <path d="M13.8879 13.9236L17.7235 17.7083" stroke="#121D35" stroke-width="1.5"
                                      stroke-linecap="square"/>
                            </svg>
                            <span>{{__('Filter')}}</span>
                        </div>
                    </button>
                </div>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="all-tab-pane" role="tabpanel" aria-labelledby="all-tab"
                     tabindex="0">
                    @include('admin.services.invoices.table', ['status' => 'all'])
                </div>
                <div class="tab-pane fade" id="paid-tab-pane" role="tabpanel" aria-labelledby="paid-tab"
                     tabindex="0">
                    @include('admin.services.invoices.table', ['status' => 'paid'])
                </div>
                <div class="tab-pane fade" id="pending-tab-pane" role="tabpanel" aria-labelledby="pending-tab"
                     tabindex="0">
                    @include('admin.services.invoices.table', ['status' => 'pending'])
                </div>
                <div class="tab-pane fade" id="cancelled-tab-pane" role="tabpanel" aria-labelledby="cancelled-tab"
                     tabindex="0">
                    @include('admin.services.invoices.table', ['status' => 'cancelled'])
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal section start -->
    <div class="modal fade" id="add-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
                <div
                    class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Create Service Order Invoice') }}</h2>
                    <div class="mClose">
                        <button type="button"
                                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                                data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                </div>
                <form class="ajax reset" action="{{ route('admin.service_invoices.store') }}" method="post"
                      data-handler="commonResponseForModal">
                    @csrf
                    @include('admin.services.invoices.form')
                    <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
                        <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">

            </div>
        </div>
    </div>

    <div class="modal fade" id="view-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15 viewInvoiceModal">

            </div>
        </div>
    </div>

    <input type="hidden" id="serviceOrderInvoiceIndexRoute" value="{{ route(getPrefix().'.service_invoices.index') }}">
    <input type="hidden" id="get-service-order-route"
           value="{{ route('admin.get_service_order', ['student_id' => '__STUDENT_ID__']) }}">
    <input type="hidden" id="getCurrencyByGatewayRoute" value="{{ route('gateway-currency') }}">
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/service_order_invoices.js') }}"></script>
@endpush
