@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="p-sm-30 p-15">
        <div class="row rg-20">
            <div class="col-xl-3">
                <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                    @include('admin.cms.partials.cms-sidebar')
                </div>
            </div>
            <div class="col-xl-9">
                <div class="align-items-center bd-b-one bd-c-one bd-c-stroke bd-c-stroke-2 d-flex justify-content-between mb-30 pb-15 pb-sm-30">
                    <h4 class="fs-18 fw-700 lh-24 text-title-text">{{$pageTitle}}</h4>
                    <button data-bs-toggle="modal" data-bs-target="#add-modal" class="flipBtn sf-flipBtn-primary flex-shrink-0">+ {{__('Add Testimonial')}}</button>
                </div>
                <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                    <div class="">
                        <table class="table zTable zTable-last-item-right pt-15" id="testimonialDataTable">
                            <thead>
                            <tr>
                                <th><div>{{__('Sl')}}</div></th>
                                <th><div>{{__('Image')}}</div></th>
                                <th><div>{{__('Name')}}</div></th>
                                <th><div class="text-nowrap">{{__('Review Date')}}</div></th>
                                <th><div class="text-end">{{__('Status')}}</div></th>
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
                    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Add Testimonials') }}</h2>
                    <div class="mClose">
                        <button type="button"
                                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                                data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                </div>
                <form class="ajax reset" action="{{ route('admin.cms-settings.testimonials.store') }}" method="post"
                      data-handler="commonResponseForModal">
                    @csrf
                    <div class="row rg-20">
                        <div class="">
                            <label for="name" class="zForm-label-alt">{{ __('Name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" placeholder="{{ __('Type Name') }}"
                                   class="form-control zForm-control-alt">
                        </div>
                        <div class="">
                            <label for="description" class="zForm-label-alt">{{ __('Description') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="description" id="description" placeholder="{{ __('Type Description') }}"
                                   class="form-control zForm-control-alt">
                        </div>
                        <div class="">
                            <label for="name" class="zForm-label-alt">{{ __('Review Date') }} <span
                                    class="text-danger">*</span></label>
                            <input type="date" name="review_date" id="review_date" placeholder="{{ __('Review Date') }}"
                                   class="form-control zForm-control-alt">
                        </div>
                        <div class="">
                            <label for="status" class="zForm-label-alt">{{ __('Status') }}
                                <span class="text-danger">*</span></label>
                            <select class="sf-select-without-search" id="status" name="status">
                                <option value="{{STATUS_ACTIVE}}">{{ __('Active') }}</option>
                                <option value="{{STATUS_DEACTIVATE}}">{{ __('Deactivate') }}</option>
                            </select>
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
                                <label for="zImageUpload" class="zForm-label-alt">{{ __('Image') }} <span
                                        class="text-mime-type">{{__('(jpeg,png,jpg,svg,webp)')}}</span> <span
                                        class="text-danger">*</span></label>
                                <div class="upload-img-box">
                                    <img src="" />
                                    <input type="file" name="image" id="image" accept="image/*"
                                           onchange="previewFile(this)" />
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
    <input type="hidden" id="testimonial-route" value="{{ route('admin.cms-settings.testimonials.index') }}">

    <!-- Edit Modal section end -->
@endsection
@push('script')
    <script src="{{asset('admin/custom/js/cms/testimonials.js')}}"></script>
@endpush
