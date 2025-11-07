@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="row rg-20">
            <div class="col-xl-3">
                <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                    @include('admin.setting.sidebar')
                </div>
            </div>
            <div class="col-xl-9">
                <div
                    class="align-items-center bd-b-one bd-c-one bd-c-stroke bd-c-stroke-2 d-flex justify-content-between mb-30 pb-15 pb-sm-30">
                    <h4 class="fs-18 fw-700 lh-24 text-title-text">{{$pageTitle}}</h4>
                    <!--  -->
                    <button class="flipBtn sf-flipBtn-primary" type="button" data-bs-toggle="modal" data-bs-target="#add-modal">
                        {{ __('+ Add Currency') }}
                    </button>
                </div>
                <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                    <table class="table zTable zTable-last-item-right" id="currencyDataTable">
                        <thead>
                            <tr>
                                <th>
                                    <div>{{ __("SL#") }}</div>
                                </th>
                                <th>
                                    <div>{{ __("Code") }}</div>
                                </th>
                                <th>
                                    <div>{{ __("Symbol") }}</div>
                                </th>
                                <th>
                                    <div>{{ __("Placemnent") }}</div>
                                </th>
                                <th>
                                    <div>{{ __("Action") }}</div>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Page content area end -->
    <!-- Add Modal section start -->
    <div class="modal fade" id="add-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
                <div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Add Currency') }}</h2>
                    <div class="mClose">
                        <button type="button"
                                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                                data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                </div>
                <form class="ajax reset" action="{{ route('admin.setting.currencies.store') }}" method="post"
                      data-handler="settingCommonHandler">
                    @csrf
                    <div class="row rg-20">
                        <div class="col-12">
                            <div class="primary-form-group">
                                <div class="primary-form-group-wrap">
                                    <label for="currency_code" class="form-label">{{ __('Currency ISO Code') }} <span
                                            class="text-danger">*</span></label>
                                    <select id="sf-select-currency-add" class="primary-form-control"
                                            name="currency_code">
                                        @foreach (getCurrency() as $code => $currencyItem)
                                            <option value="{{ $code }}">{{ $currencyItem }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <label for="symbol" class="zForm-label-alt">{{ __('Symbol') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="symbol" id="symbol" placeholder="{{ __('Type Symbol') }}"
                                   class="form-control zForm-control-alt">
                        </div>
                        <div class="">
                            <label for="currency_placement" class="zForm-label-alt">{{ __('Currency Placement') }}
                                <span class="text-danger">*</span></label>
                            <select class="sf-select-without-search" id="eventType" name="currency_placement">
                                <option value="">--{{ __('Select Option') }}--</option>
                                <option value="before">{{ __('Before Amount') }}</option>
                                <option value="after">{{ __('After Amount') }}</option>
                            </select>
                        </div>
                        <div class="col-12 mt-4">
                            <div class="d-flex form-check ps-0 mb-0 pl-0">
                                <div class="zCheck form-check form-switch">
                                    <input class="form-check-input mt-0" value="1" name="current_currency" type="checkbox"
                                           id="flexCheckChecked">
                                </div>
                                <label class="form-check-label ps-3 d-flex" for="flexCheckChecked">
                                    {{ __('Current Currency') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="pt-20">
                        <button
                            class="flipBtn sf-flipBtn-primary"
                            type="submit">{{
                        __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Modal section end -->

    <!-- Edit Modal section start -->
    <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">

            </div>
        </div>
    </div>
    <input type="hidden" id="currency-route" value="{{ route('admin.setting.currencies.index') }}">

    <!-- Edit Modal section end -->
@endsection
@push('script')
    <script src="{{asset('admin/custom/js/currencies.js')}}"></script>
@endpush
