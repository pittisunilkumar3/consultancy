@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{$pageTitle}}</h4>
        <button data-bs-toggle="modal" data-bs-target="#add-modal" class="flipBtn sf-flipBtn-primary flex-shrink-0">
            + {{__('Add Staff')}}</button>
    </div>
    <div class="p-sm-30 p-15">
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
            <div class="">
                <table class="table zTable zTable-last-item-right pt-15" id="staffDataTable">
                    <thead>
                    <tr>
                        <th>
                            <div>{{__('Sl')}}</div>
                        </th>
                        <th>
                            <div>{{__('Name')}}</div>
                        </th>
                        <th>
                            <div>{{__('Email')}}</div>
                        </th>
                        <th>
                            <div>{{__('Mobile')}}</div>
                        </th>
                        <th>
                            <div>{{__('Roles')}}</div>
                        </th>
                        <th>
                            <div>{{__('Status')}}</div>
                        </th>
                        <th>
                            <div>{{__('Action')}}</div>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
    <!-- Add Modal section start -->
    <div class="modal fade" id="add-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
                <div
                    class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Add Staff') }}</h2>
                    <div class="mClose">
                        <button type="button"
                                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                                data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                </div>
                <form class="ajax reset" action="{{ route('admin.staffs.store') }}" method="post"
                      data-handler="commonResponseForModal">
                    @csrf
                    <div class="row rg-20 mb-20">
                        <div class="col-md-6">
                            <label for="first_name" class="zForm-label-alt">{{ __('First Name') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="first_name" id="first_name"
                                   placeholder="{{ __('Type First Name') }}" class="form-control zForm-control-alt">
                        </div>

                        <div class="col-md-6">
                            <label for="last_name" class="zForm-label-alt">{{ __('Last Name') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="last_name" id="last_name" placeholder="{{ __('Type Last Name') }}"
                                   class="form-control zForm-control-alt">
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="zForm-label-alt">{{ __('Email') }} <span
                                    class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" placeholder="{{ __('Email') }}"
                                   class="form-control zForm-control-alt">
                        </div>

                        <div class="col-md-6">
                            <label for="mobile" class="zForm-label-alt">{{ __('Mobile') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="mobile" id="mobile" placeholder="{{ __('Mobile') }}"
                                   class="form-control zForm-control-alt">
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="zForm-label-alt">{{ __('Password') }} <span
                                    class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" placeholder="{{ __('Password') }}"
                                   class="form-control zForm-control-alt">
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="zForm-label-alt">{{ __('Status') }} <span
                                    class="text-danger">*</span></label>
                            <select class="sf-select-without-search" id="eventType" name="status">
                                <option value="{{STATUS_ACTIVE}}">{{ __('Active') }}</option>
                                <option value="{{STATUS_DEACTIVATE}}">{{ __('Deactivate') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex flex-column g-15">
                        <div class="bd-b-one bd-c-stroke-2">
                            <h5 class="fs-16 fw-700 lh-24 text-title-text">{{__('Roles')}}</h5>
                        </div>
                        <div class="d-flex flex-wrap g-10">
                            @foreach($roles as $role)
                                <div class="">
                                    <div class="zCheck form-switch">
                                        <input class="form-check-input" type="checkbox" name="roles[]" id="{{$role->id}}" value="{{$role->name}}" role="switch">
                                        <label class="zForm-label-alt" for="{{$role->id}}">{{$role->name}}</label>
                                    </div>
                                </div>
                            @endforeach
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
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">

            </div>
        </div>
    </div>
    <input type="hidden" id="staff-route" value="{{ route('admin.staffs.index') }}">

    <!-- Edit Modal section end -->
@endsection
@push('script')
    <script src="{{asset('admin/custom/js/staffs.js')}}"></script>
@endpush
