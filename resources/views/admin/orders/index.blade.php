@extends('layouts.app')

@push('title')
    {{ $pageTitle }}
@endpush

@section('content')
    <div class="p-sm-30 p-15 pt-54">
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white">
            <div class="zTab-wrap">
                <ul class="nav nav-tabs zTab-reset zTab-one" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active orderStatusTab" data-status="All" id="all-tab"
                                data-bs-toggle="tab" data-bs-target="#all-tab-pane" type="button" role="tab"
                                aria-controls="all-tab-pane" aria-selected="true">
                            {{ __('All') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link orderStatusTab" data-status="Paid" id="paid-tab" data-bs-toggle="tab"
                                data-bs-target="#paid-tab-pane" type="button" role="tab" aria-controls="paid-tab-pane"
                                aria-selected="false">
                            {{ __('Paid') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link orderStatusTab" data-status="Pending" id="pending-tab"
                                data-bs-toggle="tab" data-bs-target="#pending-tab-pane" type="button" role="tab"
                                aria-controls="pending-tab-pane" aria-selected="false">
                            {{ __('Pending') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link orderStatusTab" data-status="Cancelled" id="cancelled-tab"
                                data-bs-toggle="tab" data-bs-target="#cancelled-tab-pane" type="button" role="tab"
                                aria-controls="cancelled-tab-pane" aria-selected="false">
                            {{ __('Cancelled') }}
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Filter Section -->
            <div class="pb-30">
                <div class="row rg-24 justify-content-center align-items-xl-end">
                    <div class="col-xl-9">
                        <div class="tableFilter-wrap tableFilter-wrap-alt">
                            <div class="item">
                                <label for="search-key" class="zForm-label-alt">{{__('Search')}}</label>
                                <input type="text" class="form-control zForm-control-alt" id="search-key"
                                       placeholder="{{__('Search here')}}"/>
                            </div>
                            <div class="item">
                                <label for="item_id" class="zForm-label-alt">{{ $filterFieldTitle }}</label>
                                <select class="sf-select-two" id="item_id">
                                    <option value="">{{ __('Select') }} {{ $filterFieldTitle }}</option>
                                    @foreach($items as $item)
                                        <option
                                            value="{{ $item->id }}">{{ $item->title ?? $item->first_name . ' ' . $item->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filterBtn">
                                <button class="flipBtn-icon sf-flipBtn-primary" id="filterButton">
                                    <div class="item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20"
                                             viewBox="0 0 21 20" fill="none">
                                            <ellipse cx="9.26461" cy="8.99077" rx="6.7712" ry="6.69878" stroke="#121D35"
                                                     stroke-width="1.5" stroke-linecap="square"/>
                                            <path d="M13.8879 13.9236L17.7235 17.7083" stroke="#121D35"
                                                  stroke-width="1.5" stroke-linecap="square"/>
                                        </svg>
                                        <span>{{__('Filter')}}</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                    @if($orderType == 'event')
                        <div class="col-xl-3">
                            <div
                                class="d-flex justify-content-xl-end justify-content-md-center justify-content-start g-10">
                                <button class="flipBtn-icon sf-flipBtn-brand" id="exportCsv">
                                    <div class="item">
                                        <span>{{__('CSV')}}</span>
                                    </div>
                                </button>

                                <button class="flipBtn-icon sf-flipBtn-brand" id="exportExcel">
                                    <div class="item">
                                        <span>{{__('Excel')}}</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="all-tab-pane" role="tabpanel" aria-labelledby="all-tab">
                    @include('admin.orders.table', ['status' => 'all'])
                </div>
                <div class="tab-pane fade" id="paid-tab-pane" role="tabpanel" aria-labelledby="paid-tab">
                    @include('admin.orders.table', ['status' => 'paid'])
                </div>
                <div class="tab-pane fade" id="pending-tab-pane" role="tabpanel" aria-labelledby="pending-tab">
                    @include('admin.orders.table', ['status' => 'pending'])
                </div>
                <div class="tab-pane fade" id="cancelled-tab-pane" role="tabpanel" aria-labelledby="cancelled-tab">
                    @include('admin.orders.table', ['status' => 'cancelled'])
                </div>
            </div>
        </div>
    </div>

    <!-- Modal section start -->
    <div class="modal fade" id="order-status-change-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">

            </div>
        </div>
    </div>

    <input type="hidden" id="ordersRoute" value="{{ route('admin.orders.index', ['orderType' => $orderType]) }}">
    <input type="hidden" id="orderType" value="{{ $orderType }}">

@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/orders.js') }}"></script>
@endpush
