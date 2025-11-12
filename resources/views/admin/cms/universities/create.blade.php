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
                    <a href="{{route('admin.cms-settings.universities.index')}}" class="flipBtn sf-flipBtn-secondary flex-shrink-0">{{__('Back')}}</a>
                </div>
                    <form class="ajax reset" action="{{ route('admin.cms-settings.universities.store') }}" method="post"
                          data-handler="commonResponseRedirect" data-redirect-url="{{route('admin.cms-settings.universities.index')}}">
                        @csrf
                        <div class="p-sm-25 p-15 mb-20 bd-one bd-c-stroke bd-ra-10 bg-white">
                            <div class="row rg-20">
                                <div class="col-md-6">
                                    <label for="name" class="zForm-label-alt">{{ __('Name') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" placeholder="{{ __('Type Name') }}"
                                           class="form-control zForm-control-alt">
                                </div>
                                <div class="col-md-6">
                                    <label for="avgCost" class="zForm-label-alt">{{ __('Average Cost') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="avg_cost" id="avgCost" placeholder="{{ __('Type Average Cost') }}"
                                           class="form-control zForm-control-alt">
                                </div>
                                <div class="col-md-6">
                                    <label for="worldRanking" class="zForm-label-alt">{{ __('World Ranking') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="world_ranking" id="worldRanking" placeholder="{{ __('Type World Ranking') }}"
                                           class="form-control zForm-control-alt">
                                </div>
                                <div class="col-md-6">
                                    <label for="internationalStudent" class="zForm-label-alt">{{ __('International Student') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="international_student" id="internationalStudent" placeholder="{{ __('Type International Student') }}"
                                           class="form-control zForm-control-alt">
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="zForm-label-alt">{{ __('Status') }}
                                        <span class="text-danger">*</span></label>
                                    <select class="sf-select-without-search" id="status" name="status">
                                        <option value="{{STATUS_ACTIVE}}">{{ __('Active') }}</option>
                                        <option value="{{STATUS_DEACTIVATE}}">{{ __('Deactivate') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex gap-5 pt-sm-45 flex-wrap rg-20">
                                        <div class="zCheck form-switch">
                                            <input class="form-check-input" type="checkbox" name="feature" id="feature" value="1" role="switch">
                                            <label for="feature" class="zForm-label-alt ms-2">{{ __('Featured') }}</label>
                                        </div>

                                        <div class="zCheck form-switch">
                                            <input class="form-check-input" type="checkbox" name="top_university" id="topUniversity" value="1" role="switch">
                                            <label for="topUniversity" class="zForm-label-alt ms-2">{{ __('Top University') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="country" class="zForm-label-alt">{{ __('Country') }}
                                        <span class="text-danger">*</span></label>
                                    <select class="sf-select-checkbox-search country_id" id="country" name="country_id">
                                        @foreach($countryData as $data)
                                        <option value="{{$data->id}}">{{ $data->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="">
                                    <label for="details" class="zForm-label-alt">{{ __('Details') }} <span
                                            class="text-danger">*</span></label>
                                    <textarea class="summernoteOne" name="details" id="details"
                                              placeholder="{{__('Details')}}"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="p-sm-25 p-15 bd-one bd-c-stroke mb-20 bd-ra-10 bg-white">
                            <div class="row rg-20">
                                <div class="col-md-6">
                                    <div class="bd-c-stroke-2 d-flex justify-content-between align-items-center">
                                        <h4 class="fs-18 fw-700 lh-24">{{ __('Thumbnail Image Section') }}</h4>
                                    </div>
                                    <div class="primary-form-group mt-3">
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
                                                <img src="" id="thumbnailImage" />
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
                                    <div class="primary-form-group mt-3">
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
                                                <img src="" id="universityLogo" />
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
                                <div class="universityCoreBenefitsItems row rg-20 bd-b-one bd-c-one bd-c-stroke-2 pb-15">
                                    <div class="col-md-6 pt-14">
                                        <label for="core_benefits_title" class="zForm-label-alt">{{ __('Core Benefits Title') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="core_benefits_title[]" id="core_benefits_title-0"
                                               placeholder="{{ __('Type Core Benefits Title') }}"
                                               class="form-control zForm-control-alt core_benefits_title">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="primary-form-group  d-flex justify-content-between">
                                            <div class="primary-form-group-wrap zImage-upload-details mw-100 mt-12">
                                                <label for="coreBenefitsIcon-0"
                                                       class="zForm-label-alt">{{ __('Core Benefits Icon') }} <span
                                                        class="text-mime-type">{{__('(jpeg,png,jpg,svg,webp)')}}</span> <span
                                                        class="text-danger">*</span></label>
                                                <div class="d-flex align-items-center g-10">
                                                    <div class="file-upload-one d-flex flex-column g-10 w-100">
                                                        <label for="coreBenefitsIcon-0">
                                                            <p class="fileName fs-12 fw-500 lh-16 text-para-text">{{__("Choose Image to upload")}}</p>
                                                            <p class="fs-12 fw-500 lh-16 text-white">{{__("Browse File")}}</p>
                                                        </label>
                                                        <span
                                                            class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 56 px / 56 px")}}</span>
                                                        <div class="max-w-150 flex-shrink-0">
                                                            <input type="file" name="core_benefits_icon[]"
                                                                   id="coreBenefitsIcon-0" accept="image/*"
                                                                   class="fileUploadInput position-absolute invisible core_benefits_icon"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-sm-25 p-15 bd-one bd-c-stroke mb-20 bd-ra-10 bg-white">
                            <div
                                class="bd-c-stroke-2 justify-content-between align-items-center d-flex justify-content-between rg-20 flex-wrap">
                                <h4 class="fs-18 fw-700 lh-24">{{__('Image Gallery Section')}}<span
                                        class="text-mime-type zForm-label-alt">{{__(' (jpeg,png,jpg,svg,webp)')}}</span></h4>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <div class="primary-form-group">
                                        <div class="primary-form-group-wrap zImage-upload-details mw-100 mt-12">
                                            <div class="zImage-inside">
                                                <div class="d-flex pb-12"><img
                                                        src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt=""/>
                                                </div>
                                                <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}
                                                </p>
                                            </div>
                                            <label for="galleryImage1" class="zForm-label-alt">{{ __('Gallery Image 1') }} <span
                                                    class="text-danger">*</span></label>
                                            <div class="upload-img-box">
                                                <img src=""/>
                                                <input type="file" name="gallery_image[]" class="gallery_image"
                                                       id="galleryImage1" accept="image/*"
                                                       onchange="previewFile(this)"/>
                                            </div>
                                            <span
                                                class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 645 px / 525 px")}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="primary-form-group">
                                        <div class="primary-form-group-wrap zImage-upload-details mw-100 mt-12">
                                            <div class="zImage-inside">
                                                <div class="d-flex pb-12"><img
                                                        src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt=""/>
                                                </div>
                                                <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}
                                                </p>
                                            </div>
                                            <label for="galleryImage2" class="zForm-label-alt">{{ __('Gallery Image 2') }} <span
                                                    class="text-danger">*</span></label>
                                            <div class="upload-img-box">
                                                <img src=""/>
                                                <input type="file" name="gallery_image[]" class="gallery_image"
                                                       id="galleryImage2" accept="image/*"
                                                       onchange="previewFile(this)"/>
                                            </div>
                                            <span
                                                class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 645 px / 525 px")}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="primary-form-group">
                                        <div class="primary-form-group-wrap zImage-upload-details mw-100 mt-12">
                                            <div class="zImage-inside">
                                                <div class="d-flex pb-12"><img
                                                        src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt=""/>
                                                </div>
                                                <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}
                                                </p>
                                            </div>
                                            <label for="galleryImage3" class="zForm-label-alt">{{ __('Gallery Image 3') }} <span
                                                    class="text-danger">*</span></label>
                                            <div class="upload-img-box">
                                                <img src=""/>
                                                <input type="file" name="gallery_image[]" class="gallery_image"
                                                       id="galleryImage3" accept="image/*"
                                                       onchange="previewFile(this)"/>
                                            </div>
                                            <span
                                                class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 645 px / 525 px")}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="primary-form-group">
                                        <div class="primary-form-group-wrap zImage-upload-details mw-100 mt-12">
                                            <div class="zImage-inside">
                                                <div class="d-flex pb-12"><img
                                                        src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt=""/>
                                                </div>
                                                <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}
                                                </p>
                                            </div>
                                            <label for="galleryImage4" class="zForm-label-alt">{{ __('Gallery Image 4') }} <span
                                                    class="text-danger">*</span></label>
                                            <div class="upload-img-box">
                                                <img src=""/>
                                                <input type="file" name="gallery_image[]" class="gallery_image"
                                                       id="galleryImage4" accept="image/*"
                                                       onchange="previewFile(this)"/>
                                            </div>
                                            <span
                                                class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 645 px / 525 px")}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(isset($criteriaFields) && $criteriaFields->count() > 0)
                        <div class="p-sm-25 p-15 bd-one bd-c-stroke mb-20 bd-ra-10 bg-white">
                            <div class="bd-c-stroke-2 d-flex justify-content-between align-items-center mb-3">
                                <h4 class="fs-18 fw-700 lh-24">{{ __('Admission Criteria') }}</h4>
                            </div>
                            <div class="row rg-20">
                                @foreach($criteriaFields as $criteriaField)
                                @php
                                    $isConditional = $criteriaField->depends_on_criteria_field_id !== null;
                                    $parentFieldId = $criteriaField->depends_on_criteria_field_id;
                                    $parentValue = $criteriaField->depends_on_value;
                                    $conditionalClass = $isConditional ? 'conditional-criteria-field' : '';
                                    $conditionalAttr = $isConditional ? 'data-depends-on="' . $parentFieldId . '" data-depends-value="' . $parentValue . '" style="display: none;"' : '';
                                @endphp
                                <div class="col-md-6 {{ $conditionalClass }}" {!! $conditionalAttr !!}>
                                    <label for="criteria_{{ $criteriaField->id }}" class="zForm-label-alt">
                                        {{ $criteriaField->name }}
                                        @if($criteriaField->description)
                                        <small class="text-muted d-block">{{ $criteriaField->description }}</small>
                                        @endif
                                        @if($isConditional)
                                        <small class="text-info d-block"><i class="fa fa-info-circle"></i> {{ __('This field depends on another criteria') }}</small>
                                        @endif
                                    </label>
                                    @if($criteriaField->type === 'boolean')
                                        <div class="zCheck form-switch">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="criteria_values[{{ $criteriaField->id }}]" 
                                                   id="criteria_{{ $criteriaField->id }}" 
                                                   value="1" 
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
                                               placeholder="{{ __('Enter value') }}">
                                    @elseif($criteriaField->type === 'json' && $criteriaField->is_structured && !empty($criteriaField->options) && is_array($criteriaField->options))
                                        {{-- Structured JSON type (e.g., English tests with scores) --}}
                                        <div class="border rounded p-3" id="structured_json_{{ $criteriaField->id }}">
                                            @foreach($criteriaField->options as $option)
                                            <div class="row mb-3 align-items-center structured-option-row" data-option="{{ $option }}">
                                                <div class="col-md-6">
                                                    <div class="zForm-wrap-checkbox-2">
                                                        <input type="checkbox" 
                                                               name="criteria_structured[{{ $criteriaField->id }}][{{ $option }}][enabled]" 
                                                               id="criteria_{{ $criteriaField->id }}_{{ $option }}_enabled" 
                                                               class="form-check-input structured-checkbox" 
                                                               value="1"
                                                               data-field-id="{{ $criteriaField->id }}"
                                                               data-option="{{ $option }}">
                                                        <label for="criteria_{{ $criteriaField->id }}_{{ $option }}_enabled" class="form-check-label">
                                                            {{ $option }}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="number" 
                                                           name="criteria_structured[{{ $criteriaField->id }}][{{ $option }}][value]" 
                                                           id="criteria_{{ $criteriaField->id }}_{{ $option }}_value" 
                                                           class="form-control zForm-control-alt structured-value-input" 
                                                           step="0.01"
                                                           placeholder="{{ __('Min. Score') }}"
                                                           disabled
                                                           data-field-id="{{ $criteriaField->id }}"
                                                           data-option="{{ $option }}">
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <small class="form-text text-muted">{{ __('Check accepted tests and enter minimum scores') }}</small>
                                    @elseif($criteriaField->type === 'json' && !empty($criteriaField->options) && is_array($criteriaField->options))
                                        {{-- JSON type with predefined options - show checkboxes --}}
                                        <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                            @foreach($criteriaField->options as $option)
                                            <div class="zForm-wrap-checkbox-2 mb-2">
                                                <input type="checkbox" 
                                                       name="criteria_values[{{ $criteriaField->id }}][]" 
                                                       id="criteria_{{ $criteriaField->id }}_{{ $loop->index }}" 
                                                       class="form-check-input" 
                                                       value="{{ $option }}">
                                                <label for="criteria_{{ $criteriaField->id }}_{{ $loop->index }}" class="form-check-label">
                                                    {{ $option }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                        <small class="form-text text-muted">{{ __('Select one or more options') }}</small>
                                    @elseif($criteriaField->type === 'json')
                                        {{-- JSON type without predefined options - show text input with instructions --}}
                                        <input type="text" 
                                               name="criteria_values[{{ $criteriaField->id }}]" 
                                               id="criteria_{{ $criteriaField->id }}" 
                                               class="form-control zForm-control-alt"
                                               placeholder='{{ __('Enter JSON array, e.g., ["UG", "PG"]') }}'>
                                        <small class="form-text text-muted">{{ __('Enter as JSON array format: ["option1", "option2"]') }}</small>
                                    @else
                                        <input type="text" 
                                               name="criteria_values[{{ $criteriaField->id }}]" 
                                               id="criteria_{{ $criteriaField->id }}" 
                                               class="form-control zForm-control-alt"
                                               placeholder="{{ __('Enter value') }}">
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
                            <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">{{__('Submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
@endsection
@push('script')
    <script src="{{asset('admin/custom/js/cms/universities.js')}}"></script>
@endpush
