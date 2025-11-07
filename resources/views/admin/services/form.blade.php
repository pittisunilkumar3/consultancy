<div class="p-sm-25 p-15 bd-one bd-c-stroke mb-20 bd-ra-10 bg-white">
    <div class="row rg-20">
        <div class="col-md-4">
            <label for="title" class="zForm-label-alt">{{__('Title')}} <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title" placeholder="{{__('Type Service Title')}}" value="{{ $service->title ?? '' }}" class="form-control title zForm-control-alt">
        </div>
        <div class="col-md-4">
            <label for="price" class="zForm-label-alt">{{__('Price')}}  <span class="text-danger">*</span></label>
            <input type="number" name="price" id="price" placeholder="{{__('Type price')}}" value="{{ $service->price ?? '' }}" class="form-control price zForm-control-alt">
        </div>
        <div class="col-md-4">
            <label for="status" class="zForm-label-alt">{{ __('Status') }} <span class="text-danger">*</span></label>
            <select class="sf-select-without-search" id="status" name="status">
                <option
                    value="{{ STATUS_ACTIVE }}" {{ (isset($service) && $service->status == STATUS_ACTIVE) ? 'selected' : '' }}>{{ __('Active') }}</option>
                <option
                    value="{{ STATUS_DEACTIVATE }}" {{ (isset($service) && $service->status == STATUS_DEACTIVATE) ? 'selected' : '' }}>{{ __('Inactive') }}</option>
            </select>
        </div>
        <div class="pt-15">
            <label class="zForm-label-alt">{{ __('Description') }} <span class="text-danger">*</span></label>
            <textarea class="summernoteOne" name="description" placeholder="{{__('Type Services Description')}}">{{ $service->description ?? '' }}</textarea>
        </div>
    </div>
</div>
<h4 class="mb-15 fs-18 fw-700 lh-24 text-title-text">{{__('Image')}} {{__(' (jpeg,png,jpg,svg,webp)')}}</h4>
<div class="mb-20 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
    <div class="row">
        <div class="col-6">
            <div class="primary-form-group">
                <div class="primary-form-group-wrap zImage-upload-details mw-100 mt-12">
                    <div class="zImage-inside">
                        <div class="d-flex pb-12"><img
                                src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt=""/>
                        </div>
                        <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}
                        </p>
                    </div>
                    <label for="icon" class="zForm-label-alt">{{ __('Icon') }} <span
                            class="text-danger">*</span></label>
                    <div class="upload-img-box">
                        <img src="{{ (isset($service) && !is_null($service->icon)) ? getFileUrl($service->icon) : '' }}"/>
                        <input type="file" name="icon" id="icon" accept="image/*" onchange="previewFile(this)"/>
                    </div>
                    <span class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 30 px / 30 px")}}</span>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="primary-form-group">
                <div class="primary-form-group-wrap zImage-upload-details mw-100 mt-12">
                    <div class="zImage-inside">
                        <div class="d-flex pb-12"><img
                                src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt=""/>
                        </div>
                        <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}
                        </p>
                    </div>
                    <label for="image" class="zForm-label-alt">{{ __('Image') }} <span
                            class="text-danger">*</span></label>
                    <div class="upload-img-box">
                        <img src="{{(isset($service) && !is_null($service->image)) ? getFileUrl($service->image) : '' }}"/>
                        <input type="file" name="image" id="image" accept="image/*" onchange="previewFile(this)"/>
                    </div>
                    <span class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 880 px / 420 px")}}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<h4 class="mb-15 fs-18 fw-700 lh-24 text-title-text">{{__('Extra Feature Fields')}}</h4>
<div class="p-sm-25 p-15 bd-one bd-c-stroke mb-20 bd-ra-10 bg-white">
    <div id="faq-block">
        <div class="bd-c-stroke-2 d-flex justify-content-end">
            <button type="button" class="flex-shrink-0 flipBtn sf-flipBtn-primary px-11 py-1" id="addFeature">
                + {{__('Add More')}}
            </button>
        </div>
        @foreach($service->feature ?? [[]] as $index => $features)
            <div class="feature-item row rg-20 bd-b-one bd-c-one bd-c-stroke-2 pb-15 mb-15">
                <div class="col-md-6">
                    <label for="feature_name_{{$index}}" class="zForm-label-alt">{{__('Name')}} <span
                            class="text-danger">*</span></label>
                    <input type="text" name="feature_name[]" id="feature_name_{{$index}}"
                           placeholder="{{__('Type Service Feature')}}"
                           value="{{ $features['name'] ?? '' }}"
                           class="form-control feature_name zForm-control-alt">
                </div>
                <div class="align-items-end answer-block col-md-6 d-flex g-10 justify-content-between position-relative">
                    <div class="w-100">
                        <label for="feature_value_{{$index}}" class="zForm-label-alt">{{__('Value')}} <span
                                class="text-danger">*</span></label>
                        <input type="text" name="feature_value[]" id="feature_value_{{$index}}"
                               placeholder="{{__('Type Service Value')}}"
                               value="{{ $features['value'] ?? '' }}"
                               class="form-control feature_value zForm-control-alt">
                    </div>
                    @if($index > 0)
                        <button type="button"
                                class="removeFeature top-0 end-0 bg-transparent border-0 p-0 m-2 rounded-circle d-flex justify-content-center align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                 fill="none">
                                <path
                                    d="M16.25 4.58334L15.7336 12.9376C15.6016 15.072 15.5357 16.1393 15.0007 16.9066C14.7361 17.2859 14.3956 17.6061 14.0006 17.8467C13.2017 18.3333 12.1325 18.3333 9.99392 18.3333C7.8526 18.3333 6.78192 18.3333 5.98254 17.8458C5.58733 17.6048 5.24667 17.284 4.98223 16.904C4.4474 16.1355 4.38287 15.0668 4.25384 12.9293L3.75 4.58334"
                                    stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
                                <path
                                    d="M2.5 4.58333H17.5M13.3797 4.58333L12.8109 3.40977C12.433 2.63021 12.244 2.24043 11.9181 1.99734C11.8458 1.94341 11.7693 1.89545 11.6892 1.85391C11.3283 1.66666 10.8951 1.66666 10.0287 1.66666C9.14067 1.66666 8.69667 1.66666 8.32973 1.86176C8.24842 1.90501 8.17082 1.95491 8.09774 2.01097C7.76803 2.26391 7.58386 2.66796 7.21551 3.47605L6.71077 4.58333"
                                    stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M7.91669 13.75V8.75" stroke="#EF4444" stroke-width="1.5"
                                      stroke-linecap="round"/>
                                <path d="M12.0833 13.75V8.75" stroke="#EF4444" stroke-width="1.5"
                                      stroke-linecap="round"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
