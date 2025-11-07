<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-black">{{ __('Update Blog') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route('admin.cms-settings.blogs.store', $blogData->id) }}" method="post"
      data-handler="settingCommonHandler">
    @csrf
    <input type="hidden" value="{{$blogData->id}}" name="id">
    <div class="row rg-20">
        <div class="col-6">
            <label for="title" class="zForm-label-alt">{{ __('Title') }} <span
                    class="text-danger">*</span></label>
            <input type="text" name="title" id="title" placeholder="{{ __('Type Blog Title') }}" value="{{$blogData->title}}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="col-6">
            <label for="publishDate" class="zForm-label-alt">{{ __('Publish Date') }} <span
                    class="text-danger">*</span></label>
            <input type="datetime-local" name="publish_date" id="publishDate" value="{{$blogData->publish_date}}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="col-6">
            <label for="blogCategoryId" class="zForm-label-alt">{{ __('Blog Category') }}
                <span class="text-danger">*</span></label>
            <select class="sf-select-without-search" id="blogCategoryId" name="blog_category_id">
                @foreach($blogCategory as $data)
                    <option {{ $blogData->blog_category_id == $data->id ? 'selected' : '' }} value="{{$data->id}}">{{$data->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6">
            <label for="blogTag" class="zForm-label-alt">{{ __('Blog Tag') }}
                <span class="text-danger">*</span></label>
            <select class="sf-select-checkbox-search" id="blogTag" name="tag_id[]" multiple>
                @foreach($blogTag as $data)
                    <option {{ in_array($data->id, $oldTags) ? 'selected' : '' }} value="{{ $data->id }}">{{ $data->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6">
            <label for="metaTitle" class="zForm-label-alt">{{ __('Meta Title') }} </label>
            <input type="text" name="meta_title" id="metaTitle" placeholder="{{__('Type Meta Title')}}" value="{{$blogData->meta_title}}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="col-6">
            <label for="metaDescription" class="zForm-label-alt">{{ __('Meta Description') }} </label>
            <input type="text" name="meta_description" id="metaDescription" placeholder="{{__('Type Meta Description')}}" value="{{$blogData->meta_description}}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="col-6">
            <label for="metaKeyword" class="zForm-label-alt">{{ __('Meta Keyword') }} </label>
            <input type="text" name="meta_keyword" id="metaKeyword" placeholder="{{__('Type Meta Keyword')}}" value="{{$blogData->meta_keyword}}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="col-6">
            <label for="status" class="zForm-label-alt">{{ __('Status') }}
                <span class="text-danger">*</span></label>
            <select class="sf-select-without-search" id="status" name="status">
                <option {{ $blogData->status == STATUS_ACTIVE ? 'selected' : ''}} value="{{STATUS_ACTIVE}}">{{ __('Active') }}</option>
                <option {{ $blogData->status == STATUS_DEACTIVATE ? 'selected' : '' }} value="{{STATUS_DEACTIVATE}}">{{ __('Deactivate') }}</option>
            </select>
        </div>
        <div class="">
            <label for="details" class="zForm-label-alt">{{ __('Details') }} <span
                    class="text-danger">*</span></label>
            <textarea class="summernoteOne" name="details" id="details"
                      placeholder="{{__('Details')}}">{!! $blogData->details !!}</textarea>
        </div>
    </div>
    <div class="row">
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
                    <label for="bannerImage" class="zForm-label-alt">{{ __('Banner Image') }} <span
                            class="text-mime-type">{{__('(jpeg,png,jpg,svg,webp)')}}</span> <span
                            class="text-danger">*</span></label>
                    <div class="upload-img-box">
                        <img src="{{ getFileUrl($blogData->banner_image) }}" />
                        <input type="file" name="banner_image" id="bannerImage" accept="image/*"
                               onchange="previewFile(this)" />
                    </div>
                    <span
                        class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 880 px / 420 px")}}</span>
                </div>
            </div>
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
                    <label for="ogImage" class="zForm-label-alt">{{ __('OG Image') }} <span
                            class="text-mime-type">{{__('(jpeg,png,jpg,svg,webp)')}}</span></label>
                    <div class="upload-img-box">
                        <img src="{{ getFileUrl($blogData->og_image )}}" />
                        <input type="file" name="og_image" id="ogImage" accept="image/*"
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
