@extends('layouts.app')

@push('title')
    {{ $pageTitle }}
@endpush

@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{ $pageTitle }}</h4>
        <button data-bs-toggle="modal" data-bs-target="#add-modal" class="flipBtn sf-flipBtn-primary flex-shrink-0">
            + {{ __('Add Question') }}</button>
    </div>

    <div class="p-sm-30 p-15">
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
            <div class="">
                <table class="table zTable zTable-last-item-right pt-15" id="questionsDataTable">
                    <thead>
                        <tr>
                            <th>
                                <div>{{ __('#Sl') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Question') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Type') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Required') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Order') }}</div>
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
                <h5 class="fs-18 fw-600 lh-24 text-title-black">{{ __('Add New Question') }}</h5>
                <button class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent" data-bs-dismiss="modal" type="button">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <form class="ajax" data-handler="settingCommonHandler" action="{{ route('admin.questions.store') }}" method="post">
                @csrf
                <div class="row rg-20">
                    <div class="col-12">
                        <label for="question" class="zForm-label">{{ __('Question') }} <span class="text-danger">*</span></label>
                        <input type="text" name="question" id="question" class="form-control zForm-control" placeholder="{{ __('Enter Question') }}" required>
                    </div>
                    <div class="col-12">
                        <label for="type" class="zForm-label">{{ __('Type') }} <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-select zForm-control sf-select-without-search">
                            <option value="text">{{ __('Text') }}</option>
                            <option value="textarea">{{ __('Textarea') }}</option>
                            <option value="number">{{ __('Number') }}</option>
                            <option value="email">{{ __('Email') }}</option>
                            <option value="file">{{ __('File Upload') }}</option>
                            <option value="select">{{ __('Select') }}</option>
                            <option value="radio">{{ __('Radio') }}</option>
                            <option value="checkbox">{{ __('Checkbox') }}</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="placeholder" class="zForm-label">{{ __('Placeholder') }}</label>
                        <input type="text" name="placeholder" id="placeholder" class="form-control zForm-control" placeholder="{{ __('Enter placeholder text') }}">
                        <small class="form-text text-muted">{{ __('This text will appear as a placeholder in the input field') }}</small>
                    </div>
                    <div class="col-12 step-wrapper d-none">
                        <label for="step" class="zForm-label">{{ __('Step Value') }}</label>
                        <input type="text" name="step" id="step" class="form-control zForm-control" placeholder="{{ __('e.g., 0.1, 0.01, any') }}">
                        <small class="form-text text-muted">{{ __('For number inputs, specify step value (e.g., 0.1 for decimals, "any" for any decimal). Leave empty for integers only.') }}</small>
                    </div>
                    <div class="col-12 options-wrapper d-none">
                        <div class="mb-3">
                            <div class="zForm-wrap-checkbox-2 mb-2">
                                <input type="checkbox" name="use_countries" id="use_countries" class="form-check-input" value="1">
                                <label for="use_countries" class="form-check-label">{{ __('Use Countries from Database') }}</label>
                            </div>
                            <small class="form-text text-muted d-block">{{ __('Check this to automatically populate options with countries from the database.') }}</small>
                        </div>
                        <label class="zForm-label">{{ __('Options') }} <span class="text-danger">*</span></label>
                        <div class="option-list border rounded p-2 mb-2" id="optionList" data-draggable="true">
                            <!-- Option rows inserted here -->
                        </div>
                        <div class="d-flex gap-2 mb-2">
                            <button type="button" id="addOptionBtn" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-plus"></i> {{ __('Add Option') }}
                            </button>
                            <small class="form-text text-muted align-self-center">{{ __('Add options and drag to reorder. For select type you can set value and label.') }}</small>
                        </div>
                        <input type="hidden" name="options" id="options" value="">
                    </div>
                    <div class="col-12">
                        <label for="order" class="zForm-label">{{ __('Order') }}</label>
                        <input type="number" name="order" id="order" class="form-control zForm-control" value="0">
                    </div>
                    <div class="col-12">
                        <div class="zForm-wrap-checkbox-2">
                            <input type="checkbox" name="required" id="required" class="form-check-input" value="1">
                            <label for="required" class="form-check-label">{{ __('Required Field') }}</label>
                        </div>
                    </div>
                    @if(isset($criteriaFields) && $criteriaFields->count() > 0)
                    <div class="col-12">
                        <label class="zForm-label">{{ __('Map to University Criteria') }} <small class="text-muted">({{ __('Optional') }})</small></label>
                        <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                            @foreach($criteriaFields as $criteriaField)
                            <div class="zForm-wrap-checkbox-2 mb-2">
                                <input type="checkbox" 
                                       name="criteria_fields[]" 
                                       id="criteria_field_{{ $criteriaField->id }}" 
                                       class="form-check-input criteria-field-checkbox" 
                                       value="{{ $criteriaField->id }}">
                                <label for="criteria_field_{{ $criteriaField->id }}" class="form-check-label">
                                    {{ $criteriaField->name }}
                                    @if($criteriaField->description)
                                    <small class="text-muted d-block">{{ $criteriaField->description }}</small>
                                    @endif
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <small class="form-text text-muted">{{ __('Select which university criteria this question maps to. This will be used for filtering universities based on student answers.') }}</small>
                    </div>
                    @endif
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

    <input type="hidden" id="questionsIndexRoute" value="{{ route('admin.questions.index') }}">
    <input type="hidden" id="questionsStoreRoute" value="{{ route('admin.questions.store') }}">
    <input type="hidden" id="questionsShowBase" value="{{ url('admin/questions/show') }}">
    <input type="hidden" id="questionsUpdateBase" value="{{ url('admin/questions/update') }}">
    <input type="hidden" id="questionsDeleteBase" value="{{ url('admin/questions/delete') }}">
    <input type="hidden" id="questionsCountriesRoute" value="{{ route('admin.questions.countries') }}">

@endsection

@push('script')
    <!-- SortableJS for drag-handle ordering of options -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="{{ asset('admin/custom/js/questions.js') }}"></script>
@endpush
