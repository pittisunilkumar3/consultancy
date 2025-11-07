@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{$pageTitle}}</h4>
        <button data-bs-toggle="modal" data-bs-target="#add-modal" class="flipBtn sf-flipBtn-primary flex-shrink-0">
            + {{__('Add New')}}</button>
    </div>
    <div class="p-sm-30 p-15">
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
            <div class="">
                <table class="table zTable zTable-last-item-right" id="consulterDataTable">
                    <thead>
                    <tr>
                        <th>
                            <div>{{ __('Photo') }}</div>
                        </th>
                        <th>
                            <div>{{ __('Name') }}</div>
                        </th>
                        <th>
                            <div>{{ __('Email') }}</div>
                        </th>
                        <th>
                            <div>{{ __('Mobile') }}</div>
                        </th>
                        <th>
                            <div>{{ __('Off Days') }}</div>
                        </th>
                        <th>
                            <div>{{ __('Status') }}</div>
                        </th>
                        <th>
                            <div>{{ __('Action') }}</div>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal section start -->
    <div class="modal fade" id="add-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
                <div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Add Consulter') }}</h2>
                    <div class="mClose">
                        <button type="button"
                                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                                data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                </div>
                <form class="ajax reset" action="{{ route('admin.consultations.store') }}" method="post"
                      data-handler="commonResponseForModal">
                    @csrf
                    <div class="row rg-20 mb-20">
                        <div class="col-md-4">
                            <label for="first_name" class="zForm-label-alt">{{ __('First Name') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="first_name" id="first_name"
                                   placeholder="{{ __('Type First Name') }}" class="form-control zForm-control-alt">
                        </div>

                        <div class="col-md-4">
                            <label for="last_name" class="zForm-label-alt">{{ __('Last Name') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="last_name" id="last_name" placeholder="{{ __('Type Last Name') }}"
                                   class="form-control zForm-control-alt">
                        </div>

                        <div class="col-md-4">
                            <label for="email" class="zForm-label-alt">{{ __('Email') }} <span
                                    class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" placeholder="{{ __('Email') }}"
                                   class="form-control zForm-control-alt">
                        </div>

                        <div class="col-md-4">
                            <label for="mobile" class="zForm-label-alt">{{ __('Mobile') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="mobile" id="mobile" placeholder="{{ __('Mobile') }}"
                                   class="form-control zForm-control-alt">
                        </div>

                        <div class="col-md-4">
                            <label for="password" class="zForm-label-alt">{{ __('Password') }} <span
                                    class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" placeholder="{{ __('Password') }}"
                                   class="form-control zForm-control-alt">
                        </div>

                        <div class="col-md-4">
                            <label for="fee" class="zForm-label-alt">{{ __('Fee') }}({{config('app.currencySymbol')}}) <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="fee" min="0" id="fee" placeholder="{{ __('Fee') }}"
                                   class="form-control zForm-control-alt">
                            <small class="fs-12 fst-italic">{{__('Input it 0 if its free')}}</small>
                        </div>
                        <div class="col-md-4">
                            <label for="mAttachment" class="zForm-label-alt">{{ __('Image') }}  <span class="text-mime-type">{{ __('(jpeg,png,jpg,svg,webp)') }}</span></label>
                            <div class="file-upload-one">
                                <label for="mAttachment">
                                    <p class="fileName fs-14 fw-500 lh-24 text-para-text max-w-200">{{ __('Choose Image to upload') }}</p>
                                    <p class="fs-15 fw-700 lh-28 text-white">{{ __('Browse File') }}</p>
                                </label>
                                <input type="file" name="image" id="mAttachment"
                                       class="fileUploadInput invisible position-absolute">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="off_days" class="zForm-label-alt">{{ __('Off Days') }}</label>
                            <select class="sf-select-checkbox" multiple="multiple" id="off_days" name="off_days[]">
                               @foreach(offDays() as $index => $offDay)
                                    <option value="{{$index}}">{{$offDay}}</option>
                               @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="slot_ids" class="zForm-label-alt">{{ __('Slots') }}</label>
                            <select class="sf-select-checkbox" multiple="multiple" id="slot_ids" name="slot_ids[]">
                               @foreach($slots as $slot)
                                    <option value="{{$slot->id}}">{{$slot->start_time.'-'.$slot->end_time}}</option>
                               @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="gender" class="zForm-label-alt">{{ __('Gender') }} <span
                                    class="text-danger">*</span></label>
                            <select class="sf-select-without-search" id="gender" name="gender">
                                @foreach(gender() as $index => $gender)
                                    <option value="{{$index}}">{{$gender}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="professional_title" class="zForm-label-alt">{{ __('Professional Title') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="professional_title" id="professional_title" placeholder="{{ __('Professional Title') }}"
                                   class="form-control zForm-control-alt">
                        </div>
                        <div class="col-md-4">
                            <label for="experience" class="zForm-label-alt">{{ __('Experience') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="experience" id="experience" placeholder="{{ __('Experience') }}"
                                   class="form-control zForm-control-alt">
                        </div>
                        <div class="col-md-12">
                            <label for="about_me" class="zForm-label-alt">{{ __('About Me') }} <span class="text-danger">*</span></label>
                            <textarea class="summernoteOne" name="about_me" id="about_me" placeholder="{{ __('About Me') }}"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="zForm-label-alt">{{ __('Status') }} <span
                                    class="text-danger">*</span></label>
                            <select class="sf-select-without-search" id="status" name="status">
                                <option value="{{STATUS_ACTIVE}}">{{ __('Active') }}</option>
                                <option value="{{STATUS_DEACTIVATE}}">{{ __('Deactivate') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
                        <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">
                            <div class="d-none h-25 spinner-border w-25" role="status">
                                <span class="visually-hidden"></span>
                            </div>
                            {{__('Submit')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Modal section end -->
    <!-- Edit Modal section start -->
    <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">

            </div>
        </div>
    </div>

    <input type="hidden" id="consulterIndexRoute" value="{{ route('admin.consultations.index') }}">
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/consultations.js') }}"></script>
@endpush
