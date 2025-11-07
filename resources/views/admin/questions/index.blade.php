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
                @if($questions->isEmpty())
                    <p class="text-center py-4">{{ __('No questions yet. Use Add Question to create questions dynamically.') }}</p>
                @else
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
                            @foreach($questions as $question)
                                <tr>
                                    <td>{{ $question->id }}</td>
                                    <td>{{ $question->question }}</td>
                                    <td>{{ $question->type }}</td>
                                    <td>{{ $question->required ? __('Yes') : __('No') }}</td>
                                    <td>{{ $question->order }}</td>
                                    <td>
                                        <div class="d-flex g-12">
                                            <a href="#" class="sf-btn-primary-xs edit-btn" data-id="{{ $question->id }}">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="#" class="sf-btn-danger-xs delete-btn" data-question="{{ $question->id }}">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
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
                        <select name="type" id="type" class="form-select zForm-control">
                            <option value="text">{{ __('Text') }}</option>
                            <option value="textarea">{{ __('Textarea') }}</option>
                            <option value="number">{{ __('Number') }}</option>
                            <option value="file">{{ __('File Upload') }}</option>
                            <option value="select">{{ __('Select') }}</option>
                            <option value="radio">{{ __('Radio') }}</option>
                            <option value="checkbox">{{ __('Checkbox') }}</option>
                        </select>
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

@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/questions.js') }}"></script>
@endpush
