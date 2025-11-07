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
                            <div class="row rg-20">
                                <div class="col-xxl-12 col-lg-12">
                                    <label class="zForm-label-alt">{{ __('Banner Title') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="banner_title" value="{{ getOption('banner_title') }}"
                                           class="form-control zForm-control-alt">
                                </div>
                            </div>
                            <div class="row rg-20">
                                <div class="col-xxl-6 col-lg-6 pt-15">
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
                                            <label class="zForm-label-alt">{{ __('Banner Image') }} <span
                                                    class="text-danger">*</span></label>
                                            <div class="upload-img-box">
                                                @if (getOption('banner_image'))
                                                    <img src="{{ getSettingImage('banner_image') }}" />
                                                @else
                                                    <img src="" />
                                                @endif
                                                <input type="file" name="banner_image" id="zImageUpload"
                                                       accept="image/*" onchange="previewFile(this)" />
                                            </div>
                                        </div>
                                    </div>
                                    @if ($errors->has('banner_image'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                {{ $errors->first('banner_image') }}</span>
                                    @endif
                                    <p>
                                        <span class="text-black">
                                            <span class="text-black">{{ __('Recommend Size') }}:</span>
                                            140 x 40
                                        </span>
                                    </p>
                                </div>
                                <div class="col-xxl-6 col-lg-6 pt-15">
                                    <label class="zForm-label-alt">{{ __('Banner Description') }} <span
                                            class="text-danger">*</span></label>
                                    <textarea name="banner_description" id="" class="form-control zForm-control-alt" cols="10" rows="5">{{ getOption('banner_description') }}</textarea>
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
