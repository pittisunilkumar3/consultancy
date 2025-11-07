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
                <table class="table zTable zTable-last-item-right" id="consultationSlotDataTable">
                    <thead>
                    <tr>
                        <th>
                            <div>{{ __('#SL') }}</div>
                        </th>
                        <th>
                            <div>{{ __('Start Time') }}</div>
                        </th>
                        <th>
                            <div>{{ __('End Time') }}</div>
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
                <div
                        class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Add Consultation SLot') }}</h2>
                    <div class="mClose">
                        <button type="button"
                                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                                data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                </div>
                <form class="ajax reset" action="{{ route('admin.consultations.slots.store') }}" method="post"
                      data-handler="commonResponseForModal">
                    @csrf
                    <div class="row rg-20 mb-20">
                        <div class="col-12">
                            <label for="start_time" class="zForm-label-alt">{{ __('Start Time') }}</label>
                            <input type="time" name="start_time" class="form-control zForm-control-alt" id="start_time"
                                   placeholder="{{ __('Start Time') }}"/>
                        </div>
                        <div class="col-12">
                            <label for="end_time" class="zForm-label-alt">{{ __('End Time') }}</label>
                            <input type="time" name="end_time" class="form-control zForm-control-alt" id="end_time"
                                   placeholder="{{ __('End Time') }}"/>
                        </div>
                        <div class="col-md-12">
                            <label for="addStatus" class="zForm-label-alt">{{ __('Status') }}</label>
                            <select class="sf-select-two" id="addStatus" name="status">
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Deactivate') }}</option>
                            </select>
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">

            </div>
        </div>
    </div>

    <input type="hidden" id="consultationSlotIndexRoute" value="{{ route('admin.consultations.slots.index') }}">
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/consultation-slots.js') }}"></script>
@endpush
