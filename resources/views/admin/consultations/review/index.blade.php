@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-18 fw-700 lh-24">{{ $pageTitle }}</h4>
    </div>
    <div class="p-sm-30 p-15">
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
            <div class="">
                <table class="table zTable zTable-last-item-right" id="consulterReviewDataTable">
                    <thead>
                    <tr>
                        <th>
                            <div>{{ __('#SL') }}</div>
                        </th>
                        <th>
                            <div class="text-nowrap">{{ __('Consulter Name') }}</div>
                        </th>
                        <th>
                            <div class="text-nowrap">{{ __('Student Name') }}</div>
                        </th>
                        <th>
                            <div>{{ __('Comment') }}</div>
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
    <!-- Edit Modal section start -->
    <div class="modal fade" id="review-status-change-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-15">

            </div>
        </div>
    </div>

    <input type="hidden" id="consulterReviewRoute" value="{{ route(getPrefix().'.consultations.review.list') }}">
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/consulter-review.js') }}"></script>
@endpush
