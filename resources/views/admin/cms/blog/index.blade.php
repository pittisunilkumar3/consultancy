@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="p-sm-30 p-15">
        <div class="row rg-20">
            <div class="col-xl-12">
                <div class="align-items-center bd-b-one bd-c-one bd-c-stroke bd-c-stroke-2 d-flex justify-content-between mb-30 pb-15 pb-sm-30">
                    <h4 class="fs-18 fw-700 lh-24 text-title-text">{{$pageTitle}}</h4>
                    <button data-bs-toggle="modal" data-bs-target="#add-modal" class="flipBtn sf-flipBtn-primary flex-shrink-0">+ {{__('Add Blog')}}</button>
                </div>
                <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                    <div class="">
                        <table class="table zTable zTable-last-item-right pt-15" id="blogDataTable">
                            <thead>
                            <tr>
                                <th><div>{{__('Sl')}}</div></th>
                                <th><div class="text-nowrap">{{__('Banner Image')}}</div></th>
                                <th><div>{{__('Title')}}</div></th>
                                <th><div class="text-nowrap">{{__('Category Name')}}</div></th>
                                <th><div>{{__('Status')}}</div></th>
                                <th><div>{{__('Action')}}</div></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
    <!-- Add Modal section start -->
    <div class="modal fade" id="add-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
                <div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                    <h2 class="fs-18 fw-600 lh-22 text-title-black">{{ __('Add Blogs') }}</h2>
                    <div class="mClose">
                        <button type="button"
                                class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                                data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                </div>
                <form class="ajax reset" action="{{ route('admin.cms-settings.blogs.store') }}" method="post"
                      data-handler="settingCommonHandler">
                    @csrf
                    <div class="row rg-20">
                        <div class="col-6">
                            <label for="title" class="zForm-label-alt">{{ __('Title') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" placeholder="{{ __('Type Blog Title') }}"
                                   class="form-control zForm-control-alt">
                        </div>
                        <div class="col-6">
                            <label for="publishDate" class="zForm-label-alt">{{ __('Publish Date') }} <span
                                    class="text-danger">*</span></label>
                            <input type="datetime-local" name="publish_date" id="publishDate"
                                   class="form-control zForm-control-alt">
                        </div>
                        <div class="col-6">
                            <label for="blogCategoryId" class="zForm-label-alt">{{ __('Blog Category') }}
                                <span class="text-danger">*</span></label>
                            <select class="sf-select-without-search" id="blogCategoryId" name="blog_category_id">
                                @foreach($blogCategory as $data)
                                <option value="{{$data->id}}">{{ $data->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="blogTag" class="zForm-label-alt">{{ __('Blog Tag') }}
                                <span class="text-danger">*</span></label>
                            <select class="sf-select-checkbox-search" id="blogTag" name="tag_id[]" multiple>
                                @foreach($blogTag as $data)
                                    <option value="{{$data->id}}">{{ $data->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="metaTitle" class="zForm-label-alt">{{ __('Meta Title') }} </label>
                            <input type="text" name="meta_title" id="metaTitle" placeholder="{{__('Type Meta Title')}}"
                                   class="form-control zForm-control-alt">
                        </div>
                        <div class="col-6">
                            <label for="metaDescription" class="zForm-label-alt">{{ __('Meta Description') }} </label>
                            <input type="text" name="meta_description" id="metaDescription" placeholder="{{__('Type Meta Description')}}"
                                   class="form-control zForm-control-alt">
                        </div>
                        <div class="col-6">
                            <label for="metaKeyword" class="zForm-label-alt">{{ __('Meta Keyword') }} </label>
                            <input type="text" name="meta_keyword" id="metaKeyword" placeholder="{{__('Type Meta Keyword')}}"
                                   class="form-control zForm-control-alt">
                        </div>
                        <div class="col-6">
                            <label for="status" class="zForm-label-alt">{{ __('Status') }}
                                <span class="text-danger">*</span></label>
                            <select class="sf-select-without-search" id="status" name="status">
                                <option value="{{STATUS_ACTIVE}}">{{ __('Active') }}</option>
                                <option value="{{STATUS_DEACTIVATE}}">{{ __('Deactivate') }}</option>
                            </select>
                        </div>
                        <div class="">
                            <label for="details" class="zForm-label-alt">{{ __('Details') }} <span
                                    class="text-danger">*</span></label>
                            <textarea class="summernoteOne" name="details" id="details"
                                      placeholder="{{__('Details')}}"></textarea>
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
                                        <img src="" />
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
                                        <img src="" />
                                        <input type="file" name="og_image" id="ogImage" accept="image/*"
                                               onchange="previewFile(this)" />
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
    <!-- Add Modal section end -->
    <!-- Edit Modal section start -->
    <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">

            </div>
        </div>
    </div>
    <input type="hidden" id="blog-route" value="{{ route('admin.cms-settings.blogs.index') }}">

    <!-- Edit Modal section end -->
@endsection
@push('script')
    <script src="{{asset('admin/custom/js/cms/blog.js')}}"></script>
@endpush
