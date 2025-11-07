<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Update Testimonial') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route('admin.cms-settings.testimonials.store', $testimonialData->id) }}" method="post"
      data-handler="commonResponseForModal">
    @csrf
    <input type="hidden" value="{{$testimonialData->id}}" name="id">
    <div class="row rg-20">
        <div class="">
            <label for="name" class="zForm-label-alt">{{ __('Name') }} <span
                    class="text-danger">*</span></label>
            <input type="text" name="name" id="name" placeholder="{{ __('Type name') }}" value="{{$testimonialData->name}}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="">
            <label for="description" class="zForm-label-alt">{{ __('Description') }} <span
                    class="text-danger">*</span></label>
            <input type="text" name="description" id="description" placeholder="{{ __('Type description') }}" value="{{$testimonialData->description}}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="">
            <label for="name" class="zForm-label-alt">{{ __('Review Date') }} <span
                    class="text-danger">*</span></label>
            <input type="date" name="review_date" id="review_date" value="{{$testimonialData->review_date}}" placeholder="{{ __('Review Date') }}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="">
            <label for="status" class="zForm-label-alt">{{ __('Status') }}
                <span class="text-danger">*</span></label>
            <select class="sf-select-without-search" id="status" name="status">
                <option {{ $testimonialData->status == STATUS_ACTIVE ? 'selected' : '' }} value="{{STATUS_ACTIVE}}">{{ __('Active') }}</option>
                <option {{ $testimonialData->status == STATUS_DEACTIVATE ? 'selected' : '' }} value="{{STATUS_DEACTIVATE}}">{{ __('Deactivate') }}</option>
            </select>
        </div>
        <div class="col-6">
            <div class="primary-form-group">
                <div class="primary-form-group-wrap zImage-upload-details mw-100 mt-12">
                    <div class="zImage-inside">
                        <div class="d-flex pb-12"><img
                                src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt="" />
                        </div>
                        <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}
                        </p>
                    </div>
                    <label for="zImageUpload" class="zForm-label-alt">{{ __('Image') }} <span
                            class="text-mime-type">{{__('(jpeg,png,jpg,svg,webp)')}}</span> <span
                            class="text-danger">*</span></label>
                    <div class="upload-img-box">
                        <img src="{{getFileUrl($testimonialData->image)}}" />
                        <input type="file" name="image" id="image" accept="image/*"
                               onchange="previewFile(this)" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
        <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">
            <span>{{__('Submit')}}</span>
            <span>{{__('Submit')}}</span>
            <span>{{__('Submit')}}</span>
        </button>
    </div>
</form>
