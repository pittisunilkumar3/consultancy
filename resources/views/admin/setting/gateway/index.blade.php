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
                <div class="d-flex flex-wrap g-12 justify-content-end pb-3">
                    <a href="{{route('admin.setting.gateway.syncs')}}" class="border-0 bg-main-color py-3 px-26 bd-ra-8 fs-15 fw-600 lh-25 text-white" onclick="return confirm('Are you sure you want to sync gateways?');"><i class="fa fa-sync-alt"></i></a>
                </div>
                <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                    <div class="row rg-20">
                        @foreach ($gateways as $gateway)
                            <div class="col-sm-6">
                                <div
                                    class="bg-white bd-one bd-c-stroke bd-ra-8 p-sm-16 p-10 d-flex justify-content-between flex-wrap h-100">
                                    <div class="">
                                        <div class="max-w-110 pb-10"><img src="{{ asset($gateway->image) }}"
                                                alt="" /></div>
                                        <div class="d-flex flex-wrap g-5">
                                            @if ($gateway->status == ACTIVE)
                                                <p class="zBadge zBadge-active">{{ __('Active') }}</p>
                                            @else
                                                <p class="zBadge zBadge-inactive">{{ __('Deactivate') }}</p>
                                            @endif
                                            @if ($gateway->mode == GATEWAY_MODE_LIVE)
                                                <p class="zBadge zBadge-active">{{ __('Live') }}</p>
                                            @elseif($gateway->slug != 'bank')
                                                <p class="zBadge zBadge-inactive">{{ __('Sandbox') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="dropdown dropdown-one">
                                        <button
                                            class="dropdown-toggle p-0 bg-transparent w-30 h-30 ms-auto bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false"><i
                                                class="fa-solid fa-ellipsis"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end dropdownItem-two">
                                            <li>
                                                <a class="d-flex align-items-center cg-8"
                                                   href="javascript:void(0)"
                                                   onclick="getEditModal('{{ route('admin.setting.gateway.edit', encodeId($gateway->id)) }}', '#edit-modal')">
                                                    <div class="d-flex">
                                                        <svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z"
                                                                fill="#5D697A" />
                                                        </svg>
                                                    </div>
                                                    <p class="fs-14 fw-500 lh-17 text-para-text text-nowrap">{{ __('Edit') }}</p>
                                                </a>

                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal section start -->
    <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">

            </div>
        </div>
    </div>
    <input type="hidden" id="getCurrencySymbol" value="{{ getCurrencySymbol() }}">
    <input type="hidden" id="allCurrency" value="{{ json_encode(getCurrency()) }}">
@endsection
@push('script')
    <script src="{{ asset('admin/custom/js/gateway.js') }}"></script>
@endpush
