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
                            <div class="bd-c-stroke-2 justify-content-between align-items-center text-end d-flex">
                                <h4 class="fs-18 fw-700 lh-24">{{ __('How We Work') }}</h4>
                                <button type="button" class="flipBtn sf-flipBtn-primary flex-shrink-0 addHowWeWorkBtn">
                                    {{ __('+ Add More') }}
                                </button>
                            </div>
                            <div id="howWeWorkContainer">
                                @if(empty($titles))
                                    <div class="howWeWorkItemsContainer mt-3">
                                        <div class="row rg-20 bd-b-one bd-c-one bd-c-stroke-2 pb-15">
                                            <div class="col-6 pt-14">
                                                <label class="zForm-label-alt">{{ __('Title') }}</label>
                                                <input type="text" class="form-control zForm-control-alt"
                                                       name="how-we-work[title][]"
                                                       value=""
                                                       placeholder="{{ __('Type How We Work Title') }}">
                                            </div>
                                            <div class="col-6 pt-14 position-relative">
                                                <label class="zForm-label-alt">{{ __('Description') }}</label>
                                                <input type="text" class="form-control zForm-control-alt"
                                                       name="how-we-work[description][]"
                                                       value=""
                                                       placeholder="{{ __('Type How We Work Description') }}">
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    @foreach ($titles as $index => $title)
                                        <div class="howWeWorkItemsContainer mt-3">
                                            <div class="row rg-20 bd-b-one bd-c-one bd-c-stroke-2 pb-15">
                                                <div class="col-6 pt-14">
                                                    <label class="zForm-label-alt">{{ __('Title') }}</label>
                                                    <input type="text" class="form-control zForm-control-alt"
                                                           name="how-we-work[title][]"
                                                           value="{{ $title }}"
                                                           placeholder="{{ __('Type How We Work Title') }}">
                                                </div>
                                                <div class="col-6 pt-14 position-relative">
                                                    <label class="zForm-label-alt">{{ __('Description') }}</label>
                                                        <input type="text" class="form-control zForm-control-alt flex-grow-1"
                                                               name="how-we-work[description][]"
                                                               value="{{ $descriptions[$index] ?? '' }}"
                                                               placeholder="{{ __('Type How We Work Description') }}">
                                                        <button type="button" class="btn ms-2 position-absolute removeHowWeWorkBtn end-0 top-0">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                                <path d="M16.25 4.58334L15.7336 12.9376C15.6016 15.072 15.5357 16.1393 15.0007 16.9066C14.7361 17.2859 14.3956 17.6061 14.0006 17.8467C13.2017 18.3333 12.1325 18.3333 9.99392 18.3333C7.8526 18.3333 6.78192 18.3333 5.98254 17.8458C5.58733 17.6048 5.24667 17.284 4.98223 16.904C4.4474 16.1355 4.38287 15.0668 4.25384 12.9293L3.75 4.58334" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
                                                                <path d="M2.5 4.58333H17.5M13.3797 4.58333L12.8109 3.40977C12.433 2.63021 12.244 2.24043 11.9181 1.99734C11.8458 1.94341 11.7693 1.89545 11.6892 1.85391C11.3283 1.66666 10.8951 1.66666 10.0287 1.66666C9.14067 1.66666 8.69667 1.66666 8.32973 1.86176C8.24842 1.90501 8.17082 1.95491 8.09774 2.01097C7.76803 2.26391 7.58386 2.66796 7.21551 3.47605L6.71077 4.58333" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
                                                                <path d="M7.91669 13.75V8.75" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
                                                                <path d="M12.0833 13.75V8.75" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
                                                            </svg>
                                                        </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-sm-6 pt-15">
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
                                            <label class="zForm-label-alt">{{ __('How We Work Image') }}</label>
                                            <div class="upload-img-box">
                                                @if (getOption('how_we_work_image'))
                                                    <img src="{{ getSettingImage('how_we_work_image') }}" />
                                                @else
                                                    <img src="" />
                                                @endif
                                                <input type="file" name="how_we_work_image" id="zImageUpload"
                                                       accept="image/*" onchange="previewFile(this)" />
                                            </div>
                                        </div>
                                    </div>
                                    @if ($errors->has('how_we_work_image'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                {{ $errors->first('how_we_work_image') }}</span>
                                    @endif
                                    <p>
                                        <span class="text-black">
                                            <span class="text-black">{{ __('Recommend Size') }}:</span>
                                            140 x 40
                                        </span>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <div class="primary-form-group  d-flex justify-content-between">
                                        <div class="primary-form-group-wrap zImage-upload-details mw-100 mt-12">
                                            <label for="howWeWork"
                                                   class="zForm-label-alt">{{ __('How We Work Video') }}
                                                @if(getOption('how_we_work_video'))
                                                    <small class="preview-image-div">
                                                        <a href="{{ getFileUrl(getOption('how_we_work_video')) }}" target="_blank"><i class="fa-solid fa-eye"></i></a>
                                                    </small>
                                                @endif
                                            </label>
                                            <div class="d-flex align-items-center g-10">
                                                <div class="file-upload-one d-flex flex-column g-10 w-100">
                                                    <label for="howWeWork">
                                                        <p class="fileName fs-12 fw-500 lh-16 text-para-text">{{__("Choose Image to upload")}}</p>
                                                        <p class="fs-12 fw-500 lh-16 text-white">{{__("Browse File")}}</p>
                                                    </label>
                                                    <div class="max-w-150 flex-shrink-0">
                                                        <input type="file" name="how_we_work_video" value="{{getOption('how_we_work_video')}}"
                                                               id="howWeWork" accept="video/*"
                                                               class="fileUploadInput position-absolute invisible how_we_work_video"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
@push('script')
    <script src="{{asset('admin/custom/js/cms/how-we-work.js')}}"></script>
@endpush
