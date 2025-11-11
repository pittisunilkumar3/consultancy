@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="p-sm-30 p-15">
        <div class="row rg-20">
            <div class="">
                <div
                    class="align-items-center bd-b-one bd-c-one bd-c-stroke bd-c-stroke-2 d-flex justify-content-between mb-30 pb-15 pb-sm-30">
                    <h4 class="fs-18 fw-700 lh-24 text-title-text">{{$pageTitle}}</h4>
                    <a href="{{route('admin.cms-settings.universities.index')}}"
                       class="flipBtn sf-flipBtn-secondary flex-shrink-0">{{__('Back')}}</a>
                </div>
                <form class="ajax reset" action="{{ route('admin.cms-settings.universities.store') }}" method="post"
                      data-handler="commonResponseRedirect"
                      data-redirect-url="{{route('admin.cms-settings.universities.index')}}">
                    @csrf
                    <div class="p-sm-25 p-15 mb-20 bd-one bd-c-stroke bd-ra-10 bg-white">
                        <input type="hidden" value="{{$universityData->id}}" name="id">
                        <div class="row rg-20">
                            <div class="col-md-6">
                                <label for="name" class="zForm-label-alt">{{ __('Name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" placeholder="{{ __('Type Name') }}"
                                       value="{{$universityData->name}}"
                                       class="form-control zForm-control-alt">
                            </div>
                            <div class="col-md-6">
                                <label for="avgCost" class="zForm-label-alt">{{ __('Average Cost') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="avg_cost" id="avgCost" placeholder="{{ __('Type Average Cost') }}" value="{{$universityData->avg_cost}}"
                                       class="form-control zForm-control-alt">
                            </div>
                            <div class="col-md-6">
                                <label for="worldRanking" class="zForm-label-alt">{{ __('World Ranking') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="world_ranking" id="worldRanking" placeholder="{{ __('Type World Ranking') }}" value="{{$universityData->world_ranking}}"
                                       class="form-control zForm-control-alt">
                            </div>
                            <div class="col-md-6">
                                <label for="internationalStudent" class="zForm-label-alt">{{ __('International Student') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="international_student" id="internationalStudent" placeholder="{{ __('Type International Student') }}" value="{{$universityData->international_student}}"
                                       class="form-control zForm-control-alt">
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="zForm-label-alt">{{ __('Status') }}
                                    <span class="text-danger">*</span></label>
                                <select class="sf-select-without-search" id="status" name="status">
                                    <option
                                        {{ $universityData->status == STATUS_ACTIVE ? 'selected' : '' }} value="{{STATUS_ACTIVE}}">{{ __('Active') }}</option>
                                    <option
                                        {{ $universityData->status == STATUS_DEACTIVATE ? 'selected' : '' }} value="{{STATUS_DEACTIVATE}}">{{ __('Deactivate') }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-5 pt-sm-45 flex-wrap rg-20">
                                    <div class="zCheck form-switch">
                                        <input class="form-check-input" type="checkbox" {{ $universityData->feature ? 'checked' : '' }} name="feature" id="feature" value="1" role="switch">
                                        <label for="feature" class="zForm-label-alt ms-2">{{ __('Featured') }}</label>
                                    </div>

                                    <div class="zCheck form-switch">
                                        <input class="form-check-input" type="checkbox" {{ $universityData->top_university ? 'checked' : '' }} name="top_university" id="topUniversity" value="1" role="switch">
                                        <label for="topUniversity" class="zForm-label-alt ms-2">{{ __('Top University') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="country" class="zForm-label-alt">{{ __('Country') }}
                                    <span class="text-danger">*</span></label>
                                <select class="sf-select-checkbox-search country_id" id="country" name="country_id">
                                    @foreach($countryData as $data)
                                        <option {{ $universityData->country_id == $data->id ? 'selected' : '' }} value="{{$data->id}}">{{ $data->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="details" class="zForm-label-alt">{{ __('Details') }} <span
                                        class="text-danger">*</span></label>
                                <textarea class="summernoteOne" name="details" id="details"
                                          placeholder="{{__('Details')}}">{!! $universityData->details !!} </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="p-sm-25 p-15 bd-one bd-c-stroke mb-20 bd-ra-10 bg-white">
                        <div class="row rg-20">
                            <div class="col-md-6">
                                <div class="bd-c-stroke-2 d-flex justify-content-between align-items-center">
                                    <h4 class="fs-18 fw-700 lh-24">{{ __('Thumbnail Image Section') }}</h4>
                                </div>
                                <div class="primary-form-group mt-20">
                                    <div class="primary-form-group-wrap zImage-upload-details mw-100">
                                        <div class="zImage-inside text-center">
                                            <div class="d-flex justify-content-center pb-12">
                                                <img src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt="" />
                                            </div>
                                            <p class="fs-15 fw-500 lh-16 text-1b1c17">
                                                {{ __('Drag & drop files here') }}
                                            </p>
                                        </div>
                                        <label for="thumbnailImage" class="zForm-label-alt">
                                            {{ __('Thumbnail Image') }}
                                            <span class="text-mime-type">{{ __('(jpeg,png,jpg,svg,webp)') }}</span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="upload-img-box text-center">
                                            <img src="{{getFileUrl($universityData->thumbnail_image)}}" id="thumbnailImage" />
                                            <input type="file" name="thumbnail_image" id="thumbnailImage" accept="image/*"
                                                   onchange="previewFile(this)" />
                                        </div>
                                        <span
                                            class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 880 px / 420 px")}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bd-c-stroke-2 d-flex justify-content-between align-items-center">
                                    <h4 class="fs-18 fw-700 lh-24">{{ __('University Logo Section') }}</h4>
                                </div>
                                <div class="primary-form-group mt-20">
                                    <div class="primary-form-group-wrap zImage-upload-details mw-100">
                                        <div class="zImage-inside text-center">
                                            <div class="d-flex justify-content-center pb-12">
                                                <img src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt="" />
                                            </div>
                                            <p class="fs-15 fw-500 lh-16 text-1b1c17">
                                                {{ __('Drag & drop files here') }}
                                            </p>
                                        </div>
                                        <label for="universityLogo" class="zForm-label-alt">
                                            {{ __('University Logo') }}
                                            <span class="text-mime-type">{{ __('(jpeg,png,jpg,svg,webp)') }}</span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="upload-img-box text-center">
                                            <img src="{{getFileUrl($universityData->logo)}}" id="universityLogo" />
                                            <input type="file" name="logo" id="universityLogo" accept="image/*"
                                                   onchange="previewFile(this)" />
                                        </div>
                                        <span
                                            class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 162 px / 50 px")}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-sm-25 p-15 bd-one bd-c-stroke mb-20 bd-ra-10 bg-white">
                        <div class="core-benefits-block">
                            <div
                                class="bd-c-stroke-2 justify-content-between align-items-center d-flex justify-content-between rg-20 flex-wrap">
                                <h4 class="fs-18 fw-700 lh-24">{{__('Core Benefits Section')}}</h4>
                                <button type="button" class="flipBtn sf-flipBtn-primary flex-shrink-0 addUniversityCoreBenefitsBtn">
                                    {{ __('+ Add More') }}
                                </button>
                            </div>
                            @foreach ($universityData->core_benefits_title as $index => $title)
                                <div class="universityCoreBenefitsItems row rg-20 bd-b-one bd-c-one bd-c-stroke-2 pb-15">
                                    <div class="col-md-6 pt-14">
                                        <label for="core_benefits_title-{{$index}}"
                                               class="zForm-label-alt">{{ __('Core Benefits Title') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="core_benefits_title[]"
                                               id="core_benefits_title-{{$index}}"
                                               placeholder="{{ __('Type Core Benefits Title') }}" value="{{ $title }}"
                                               class="core_benefits_title form-control zForm-control-alt">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="primary-form-group d-flex justify-content-between">
                                            <div
                                                class="primary-form-group-wrap zImage-upload-details mw-100 mt-12 position-relative">
                                                <label for="coreBenefitsIcon{{ $index }}" class="zForm-label-alt">
                                                    {{ __('Core Benefits Icon') }}
                                                    <span
                                                        class="text-mime-type">{{ __('(jpeg,png,jpg,svg,webp)') }}</span>
                                                    <span class="text-danger">*</span>
                                                    <small class="preview-image-div">
                                                        <a href="{{getFileUrl($universityData->core_benefits_icon[$index])}}"
                                                           target="_blank"><i class="fa-solid fa-eye"></i></a>
                                                    </small>
                                                </label>
                                                <div class="d-flex align-items-center g-10">
                                                    <div class="file-upload-one d-flex flex-column g-10 w-100">
                                                        <label for="coreBenefitsIcon{{ $index }}">
                                                            <p class="fileName fs-12 fw-500 lh-16 text-para-text">{{__("Choose Image to upload")}}</p>
                                                            <p class="fs-12 fw-500 lh-16 text-white">{{__("Browse File")}}</p>
                                                        </label>
                                                        <span
                                                            class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 56 px / 56 px")}}</span>
                                                        <div class="max-w-150 flex-shrink-0">
                                                            <input type="hidden" class="old_core_benefits_icon_id"
                                                                   name="core_benefits_icon_id[]"
                                                                   value="{{$universityData->core_benefits_icon[$index]}}">
                                                            <input type="file" name="core_benefits_icon[]"
                                                                   id="coreBenefitsIcon{{ $index }}"
                                                                   accept="image/*"
                                                                   class="fileUploadInput position-absolute invisible core_benefits_icon"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(!$loop->first)
                                                <button type="button"
                                                        class="universityCoreBenefitsItems top-0 end-0  bg-transparent border-0 p-0 m-2 rounded-circle d-flex  justify-content-center align-items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                         viewBox="0 0 20 20"
                                                         fill="none">
                                                        <path
                                                            d="M16.25 4.58334L15.7336 12.9376C15.6016 15.072 15.5357 16.1393 15.0007 16.9066C14.7361 17.2859 14.3956 17.6061 14.0006 17.8467C13.2017 18.3333 12.1325 18.3333 9.99392 18.3333C7.8526 18.3333 6.78192 18.3333 5.98254 17.8458C5.58733 17.6048 5.24667 17.284 4.98223 16.904C4.4474 16.1355 4.38287 15.0668 4.25384 12.9293L3.75 4.58334"
                                                            stroke="#EF4444" stroke-width="1.5"
                                                            stroke-linecap="round"/>
                                                        <path
                                                            d="M2.5 4.58333H17.5M13.3797 4.58333L12.8109 3.40977C12.433 2.63021 12.244 2.24043 11.9181 1.99734C11.8458 1.94341 11.7693 1.89545 11.6892 1.85391C11.3283 1.66666 10.8951 1.66666 10.0287 1.66666C9.14067 1.66666 8.69667 1.66666 8.32973 1.86176C8.24842 1.90501 8.17082 1.95491 8.09774 2.01097C7.76803 2.26391 7.58386 2.66796 7.21551 3.47605L6.71077 4.58333"
                                                            stroke="#EF4444" stroke-width="1.5"
                                                            stroke-linecap="round"/>
                                                        <path d="M7.91669 13.75V8.75" stroke="#EF4444"
                                                              stroke-width="1.5"
                                                              stroke-linecap="round"/>
                                                        <path d="M12.0833 13.75V8.75" stroke="#EF4444"
                                                              stroke-width="1.5"
                                                              stroke-linecap="round"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="p-sm-25 p-15 mb-20 bd-one bd-c-stroke bd-ra-10 bg-white">
                        <div
                            class="bd-c-stroke-2 justify-content-between align-items-center d-flex justify-content-between">
                            <h4 class="fs-18 fw-700 lh-24">{{__('Image Gallery Section')}}<span
                                    class="text-mime-type zForm-label-alt">{{__(' (jpeg,png,jpg,svg,webp)')}}</span>
                            </h4>
                        </div>
                        <div class="row rg-20 pb-25">
                            @foreach($universityData->gallery_image as $key => $imageId)
                                <div class="col-lg-3 col-md-6">
                                    <div class="primary-form-group">
                                        <div class="primary-form-group-wrap zImage-upload-details mw-100 mt-12">
                                            <div class="zImage-inside">
                                                <div class="d-flex pb-12">
                                                    <img src="{{ asset('assets/images/icon/upload-img-1.svg') }}"
                                                         alt=""/>
                                                </div>
                                                <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}</p>
                                            </div>
                                            <label for="zImageUpload{{ $key }}" class="zForm-label-alt">
                                                {{ __('Gallery Image ') }} {{ $key + 1 }}
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="upload-img-box">
                                                <img src="{{ getFileUrl($imageId) }}"/>
                                                <input type="file" name="gallery_image[]"
                                                       id="zImageUpload{{ $key }}" accept="image/*"
                                                       onchange="previewFile(this)"/>
                                            </div>
                                            <span
                                                class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 645 px / 525 px")}}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if(isset($criteriaFields) && $criteriaFields->count() > 0)
                    <div class="p-sm-25 p-15 bd-one bd-c-stroke mb-20 bd-ra-10 bg-white">
                        <div class="bd-c-stroke-2 d-flex justify-content-between align-items-center mb-3">
                            <h4 class="fs-18 fw-700 lh-24">{{ __('Admission Criteria') }}</h4>
                        </div>
                        <div class="row rg-20">
                            @foreach($criteriaFields as $criteriaField)
                            <div class="col-md-6">
                                <label for="criteria_{{ $criteriaField->id }}" class="zForm-label-alt">
                                    {{ $criteriaField->name }}
                                    @if($criteriaField->description)
                                    <small class="text-muted d-block">{{ $criteriaField->description }}</small>
                                    @endif
                                </label>
                                @if($criteriaField->type === 'boolean')
                                    <div class="zCheck form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               name="criteria_values[{{ $criteriaField->id }}]" 
                                               id="criteria_{{ $criteriaField->id }}" 
                                               value="1" 
                                               {{ isset($existingCriteriaValues[$criteriaField->id]) && $existingCriteriaValues[$criteriaField->id] == '1' ? 'checked' : '' }}
                                               role="switch">
                                        <label for="criteria_{{ $criteriaField->id }}" class="zForm-label-alt ms-2">
                                            {{ $criteriaField->name }}
                                        </label>
                                    </div>
                                @elseif($criteriaField->type === 'number' || $criteriaField->type === 'decimal')
                                    <input type="number" 
                                           name="criteria_values[{{ $criteriaField->id }}]" 
                                           id="criteria_{{ $criteriaField->id }}" 
                                           class="form-control zForm-control-alt"
                                           step="{{ $criteriaField->type === 'decimal' ? '0.01' : '1' }}"
                                           value="{{ isset($existingCriteriaValues[$criteriaField->id]) ? $existingCriteriaValues[$criteriaField->id] : '' }}"
                                           placeholder="{{ __('Enter value') }}">
                                @else
                                    <input type="text" 
                                           name="criteria_values[{{ $criteriaField->id }}]" 
                                           id="criteria_{{ $criteriaField->id }}" 
                                           class="form-control zForm-control-alt"
                                           value="{{ isset($existingCriteriaValues[$criteriaField->id]) ? $existingCriteriaValues[$criteriaField->id] : '' }}"
                                           placeholder="{{ __('Enter value') }}">
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
                        <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">{{__('Update')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
@endsection
@push('script')
    <script src="{{asset('admin/custom/js/cms/universities.js')}}"></script>
@endpush
