@extends('layouts.app')
@section('content')
    @push('title')
        {{$pageTitle}}
    @endpush
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
                        {{ __('+ Add Language') }}
                    </button>
                </div>
                <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                    <table class="table zTable zTable-last-item-right" id="languageDataTable">
                        <thead>
                        <tr>
                            <th scope="col">
                                <div>{{ __('Flag') }}</div>
                            </th>
                            <th scope="col">
                                <div>{{ __('Language') }}</div>
                            </th>
                            <th scope="col">
                                <div class="text-nowrap">{{ __('ISO code') }}</div>
                            </th>
                            <th scope="col">
                                <div>{{ __('RTL') }}</div>
                            </th>
                            <th scope="col">
                                <div>{{ __('Action') }}</div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
                <div
                    class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Add Language') }}</h2>
                    <div class="mClose">
                        <button type="button"
                                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                                data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                </div>

                <form class="ajax reset" action="{{ route('admin.setting.languages.store') }}" method="post"
                      data-handler="languageHandler" enctype="multipart/form-data">
                    @csrf

                    <div class="row rg-20">
                        <div class="">
                            <label for="languageName" class="zForm-label-alt">{{ __('Language') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control zForm-control-alt" name="language"
                                   placeholder="{{ __('Language') }}">
                        </div>
                        <div class="">
                            <label for="iso_code" class="zForm-label-alt">{{ __('ISO Code') }} <span
                                    class="text-danger">*</span></label>
                            <select name="iso_code" class="primary-form-control" id="sf-select-modal-add">
                                <option value="">--{{ __('Select ISO Code') }}--</option>
                                @foreach (languageIsoCode() as $code => $isoCountryName)
                                    <option value="{{ $code }}">
                                        {{ $isoCountryName . '(' . $code . ')' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="primary-form-group">
                                <div class="primary-form-group-wrap zImage-upload-details mw-100">
                                    <div class="zImage-inside">
                                        <div class="d-flex pb-12"><img
                                                src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt=""/>
                                        </div>
                                        <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}
                                        </p>
                                    </div>
                                    <label for="zImageUpload" class="zForm-label-alt">{{ __('Flag') }} <span
                                            class="text-mime-type">(jpeg,png,jpg,svg,webp)</span> <span
                                            class="text-danger">*</span></label>
                                    <div class="upload-img-box">
                                        <img src=""/>
                                        <input type="file" name="flag" id="flag" accept="image/*"
                                               onchange="previewFile(this)"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="primary-form-group d-none">
                            <div class="primary-form-group-wrap">
                                <label for="attachmentFile" class="zForm-label-alt">{{ __('Font File') }}</label>
                                <input type="file" class="form-control zForm-control-alt" id="attachmentFile"
                                       accept="application/pdf" name="font">
                                @if ($errors->has('font'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('font') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label-alt" for="rtl">{{ __('RTL Supported') }} <span
                                    class="text-danger">*</span></label>
                            <select name="rtl" class="sf-select-without-search">
                                <option value="0">{{ __('No') }}</option>
                                <option value="1">{{ __('Yes') }}</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="d-flex form-check ps-0">
                                <div class="zCheck form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value="1" name="default"
                                           role="switch"
                                           id="flexCheckChecked"/>
                                </div>
                                <label class="form-check-label ps-3" for="flexCheckChecked">
                                    {{ __('Default Language') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex g-12 flex-wrap mt-25">
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
    <!-- Edit Modal section end -->
    <input type="hidden" id="language-route" value="{{ route('admin.setting.languages.index') }}">
@endsection
@push('script')
    <script src="{{asset('admin/custom/js/languages.js')}}"></script>
@endpush
