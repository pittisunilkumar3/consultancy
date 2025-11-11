@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush

@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{ $pageTitle }}</h4>
    </div>

    <div class="p-sm-30 p-15">
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
            <div class="">
                <table class="table zTable zTable-last-item-right pt-15" id="submissionsDataTable">
                    <thead>
                        <tr>
                            <th>
                                <div>{{ __('#Sl') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Student Name') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Email') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Form Name') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Status') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Submitted On') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Last Updated') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Action') }}</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- DataTables will populate rows via AJAX (server-side processing) --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#submissionsDataTable').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('admin.career-corner-submissions.index') }}",
                    type: 'GET'
                },
                dom: '<l><>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
                language: {
                    paginate: {
                        previous: "<i class='fa-solid fa-angles-left'></i>",
                        next: "<i class='fa-solid fa-angles-right'></i>",
                    },
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    lengthMenu: "Show _MENU_ entries",
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'user.name', name: 'user.name', searchable: true },
                    { data: 'user.email', name: 'user.email', searchable: true },
                    { data: 'formStructure.name', name: 'formStructure.name', searchable: true },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'created_at', name: 'created_at', searchable: false },
                    { data: 'updated_at', name: 'updated_at', searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                order: [[6, 'desc']] // Order by updated_at descending (column index 6)
            });
        });
    </script>
@endpush

