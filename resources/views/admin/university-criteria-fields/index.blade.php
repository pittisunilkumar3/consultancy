@extends('layouts.app')

@push('title')
    {{ $pageTitle }}
@endpush

@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{ $pageTitle }}</h4>
        <button data-bs-toggle="modal" data-bs-target="#add-modal" class="flipBtn sf-flipBtn-primary flex-shrink-0">
            + {{ __('Add Criteria Field') }}</button>
    </div>

    <div class="p-sm-30 p-15">
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
            <div class="">
                <table class="table zTable zTable-last-item-right pt-15" id="criteriaFieldsDataTable">
                    <thead>
                        <tr>
                            <th>
                                <div>{{ __('#Sl') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Name') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Slug') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Type') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Order') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Status') }}</div>
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

    <!-- Add Modal section start -->
    <div class="modal fade" id="add-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
                <div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                    <h5 class="fs-18 fw-600 lh-24 text-title-black">{{ __('Add New Criteria Field') }}</h5>
                    <button class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent" data-bs-dismiss="modal" type="button">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <form class="ajax" data-handler="settingCommonHandler" action="{{ route('admin.university-criteria-fields.store') }}" method="post" id="addForm">
                    @csrf
                    <div class="row rg-20">
                        <div class="col-12">
                            <label for="name" class="zForm-label">{{ __('Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control zForm-control" placeholder="{{ __('e.g., Accepts Backlogs') }}" required>
                        </div>
                        <div class="col-12">
                            <label for="slug" class="zForm-label">{{ __('Slug') }} <span class="text-danger">*</span></label>
                            <input type="text" name="slug" id="slug" class="form-control zForm-control" placeholder="{{ __('e.g., accepts_backlogs') }}" required>
                            <small class="form-text text-muted">{{ __('Unique identifier (lowercase, underscores only)') }}</small>
                        </div>
                        <div class="col-12">
                            <label for="type" class="zForm-label">{{ __('Type') }} <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select zForm-control sf-select-without-search" required>
                                <option value="boolean">{{ __('Boolean') }} (Toggle/Checkbox)</option>
                                <option value="number">{{ __('Number') }} (Integer)</option>
                                <option value="decimal">{{ __('Decimal') }} (Float)</option>
                                <option value="text">{{ __('Text') }} (String)</option>
                                <option value="json">{{ __('JSON') }} (Array/Object)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="description" class="zForm-label">{{ __('Description') }}</label>
                            <textarea name="description" id="description" class="form-control zForm-control" rows="3" placeholder="{{ __('Optional description for this criteria field') }}"></textarea>
                        </div>
                        <div class="col-12">
                            <label for="order" class="zForm-label">{{ __('Order') }}</label>
                            <input type="number" name="order" id="order" class="form-control zForm-control" value="0" min="0">
                        </div>
                        <div class="col-12">
                            <label for="status" class="zForm-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select zForm-control sf-select-without-search" required>
                                <option value="{{ STATUS_ACTIVE }}">{{ __('Active') }}</option>
                                <option value="{{ STATUS_DEACTIVATE }}">{{ __('Inactive') }}</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="mt-20 sf-btn-primary d-flex align-items-center">
                        <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                        <span>{{ __('Create') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Modal section end -->

    <!-- Edit Modal section start -->
    <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
                <div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                    <h5 class="fs-18 fw-600 lh-24 text-title-black">{{ __('Edit Criteria Field') }}</h5>
                    <button class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent" data-bs-dismiss="modal" type="button">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <form class="ajax" data-handler="settingCommonHandler" action="" method="post" id="editForm">
                    @csrf
                    <input type="hidden" name="id" id="edit_id">
                    <div class="row rg-20">
                        <div class="col-12">
                            <label for="edit_name" class="zForm-label">{{ __('Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control zForm-control" required>
                        </div>
                        <div class="col-12">
                            <label for="edit_slug" class="zForm-label">{{ __('Slug') }} <span class="text-danger">*</span></label>
                            <input type="text" name="slug" id="edit_slug" class="form-control zForm-control" required>
                            <small class="form-text text-muted">{{ __('Unique identifier (lowercase, underscores only)') }}</small>
                        </div>
                        <div class="col-12">
                            <label for="edit_type" class="zForm-label">{{ __('Type') }} <span class="text-danger">*</span></label>
                            <select name="type" id="edit_type" class="form-select zForm-control sf-select-without-search" required>
                                <option value="boolean">{{ __('Boolean') }} (Toggle/Checkbox)</option>
                                <option value="number">{{ __('Number') }} (Integer)</option>
                                <option value="decimal">{{ __('Decimal') }} (Float)</option>
                                <option value="text">{{ __('Text') }} (String)</option>
                                <option value="json">{{ __('JSON') }} (Array/Object)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="edit_description" class="zForm-label">{{ __('Description') }}</label>
                            <textarea name="description" id="edit_description" class="form-control zForm-control" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label for="edit_order" class="zForm-label">{{ __('Order') }}</label>
                            <input type="number" name="order" id="edit_order" class="form-control zForm-control" min="0">
                        </div>
                        <div class="col-12">
                            <label for="edit_status" class="zForm-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select name="status" id="edit_status" class="form-select zForm-control sf-select-without-search" required>
                                <option value="{{ STATUS_ACTIVE }}">{{ __('Active') }}</option>
                                <option value="{{ STATUS_DEACTIVATE }}">{{ __('Inactive') }}</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="mt-20 sf-btn-primary d-flex align-items-center">
                        <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                        <span>{{ __('Update') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Modal section end -->

    <input type="hidden" id="criteriaFieldsIndexRoute" value="{{ route('admin.university-criteria-fields.index') }}">
    <input type="hidden" id="criteriaFieldsStoreRoute" value="{{ route('admin.university-criteria-fields.store') }}">
    <input type="hidden" id="criteriaFieldsShowBase" value="{{ url('admin/university-criteria-fields/show') }}">
    <input type="hidden" id="criteriaFieldsUpdateBase" value="{{ url('admin/university-criteria-fields/update') }}">
    <input type="hidden" id="criteriaFieldsDeleteBase" value="{{ url('admin/university-criteria-fields/delete') }}">

@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#criteriaFieldsDataTable').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('admin.university-criteria-fields.index') }}",
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
                    { data: 'name', name: 'name', searchable: true },
                    { data: 'slug', name: 'slug', searchable: true },
                    { data: 'type', name: 'type', searchable: false },
                    { data: 'order', name: 'order', searchable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                order: [[4, 'asc']] // Order by order column
            });

            // Edit button click handler
            $(document).on('click', '.edit-btn', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                
                $.ajax({
                    url: $('#criteriaFieldsShowBase').val() + '/' + id,
                    type: 'GET',
                    success: function(response) {
                        if (response.status) {
                            var data = response.data;
                            $('#edit_id').val(data.id);
                            $('#edit_name').val(data.name);
                            $('#edit_slug').val(data.slug);
                            $('#edit_type').val(data.type);
                            $('#edit_description').val(data.description);
                            $('#edit_order').val(data.order);
                            $('#edit_status').val(data.status);
                            $('#editForm').attr('action', $('#criteriaFieldsUpdateBase').val() + '/' + id);
                            $('#edit-modal').modal('show');
                        }
                    }
                });
            });

            // Delete button click handler
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                
                Swal.fire({
                    title: '{{ __("Are you sure?") }}',
                    text: '{{ __("You won't be able to revert this!") }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __("Yes, delete it!") }}',
                    cancelButtonText: '{{ __("Cancel") }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: $('#criteriaFieldsDeleteBase').val() + '/' + id,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.status) {
                                    Swal.fire('{{ __("Deleted!") }}', response.message, 'success');
                                    $('#criteriaFieldsDataTable').DataTable().ajax.reload();
                                } else {
                                    Swal.fire('{{ __("Error!") }}', response.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('{{ __("Error!") }}', xhr.responseJSON?.message || '{{ __("Something went wrong") }}', 'error');
                            }
                        });
                    }
                });
            });

            // Reset form on modal close
            $('#add-modal').on('hidden.bs.modal', function() {
                $('#addForm')[0].reset();
            });
        });
    </script>
@endpush

