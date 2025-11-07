@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="p-sm-30 p-15">
        <div class="">
            <div class="row rg-20">
                <div class="col-xl-3">
                    <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                        @include('admin.cms.partials.cms-sidebar')
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="align-items-center bd-b-one bd-c-one bd-c-stroke bd-c-stroke-2 d-flex justify-content-between mb-30 pb-15 pb-sm-30">
                        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{$pageTitle}}</h4>
                    </div>
                    <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                        <form class="ajax" action="{{ route('admin.setting.application-settings.update') }}"
                              method="POST" enctype="multipart/form-data" data-handler="settingCommonHandler">
                            @csrf
                            <div class="align-items-center bd-b-one bd-c-one bd-c-stroke bd-c-stroke-2 d-flex justify-content-between mb-30 pb-15 pb-sm-30">
                                <h4 class="fs-18 fw-700 lh-24 text-title-text">{{__('Free Consultation Image')}}</h4>
                            </div>
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12">
                                    <div class="primary-form-group">
                                        <div class="primary-form-group-wrap zImage-upload-details">
                                            <div class="zImage-inside">
                                                <div class="d-flex pb-12">
                                                    <img src="{{ asset('assets/images/icon/upload-img-1.svg') }}"
                                                         alt="" />
                                                </div>
                                                <p class="fs-15 fw-500 lh-16 text-1b1c17">
                                                    {{ __('Drag & drop file shere') }}
                                                </p>
                                            </div>
                                            <label for="ctaSidebarImage" class="zForm-label-alt">{{ __('Free Consultation Form Image') }} <span
                                                    class="text-danger">*</span></label>
                                            <div class="upload-img-box">
                                                @if (getOption('free_consultant_image'))
                                                    <img src="{{ getSettingImage('free_consultant_image') }}" />
                                                @else
                                                    <img src="" />
                                                @endif
                                                <input type="file" name="free_consultant_image" id="ctaSidebarImage"
                                                       accept="image/*,video/*" onchange="previewFile(this)" />
                                            </div>
                                        </div>
                                    </div>
                                    @if ($errors->has('free_consultant_image'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                {{ $errors->first('free_consultant_image') }}</span>
                                    @endif
                                    <p>
                                        <span class="text-black">
                                            <span class="text-black">{{ __('Recommend Size') }}:</span>
                                            260 x 235
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
                                <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">{{__('Submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
