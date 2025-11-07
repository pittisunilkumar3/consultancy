@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="p-sm-30 p-15">
        <div>
            <h4 class="fs-24 fw-500 lh-34 text-title-text pb-16">{{ __($pageTitle) }}</h4>
            <div class="row rg-20">
                <div class="col-xl-3">
                    <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                        @include('admin.setting.sidebar')
                    </div>
                </div>
                <div class="col-xl-9">
                    <h4 class="mb-15 fs-18 fw-700 lh-24 text-title-text">{{ $pageTitle }}</h4>
                    <div class="mb-30">
                        @foreach($sections as $section)
                            <div class="bd-c-stroke bd-one bd-ra-5 bg-white p-15 p-md-30 p-sm-25 mb-30">
                                <div
                                    class="d-flex justify-content-between align-items-center mb-2 bd-b-one bd-c-stroke pb-10">
                                    <h4 class="fs-18 fw-600 lh-22 text-title-text">{{ __($section['title']) }}</h4>
                                    @if(!$section['is_show_section_disabled'])
                                        <div class="zCheck form-switch">
                                            <input class="form-check-input mt-0"
                                                   onchange="changeFormSetting(this, '{{ $section['show_section_key'] }}', 'field_show')"
                                                   type="checkbox"
                                                   id="{{ $section['show_section_key'] }}"
                                                {{ getOnboardingField($formSetting, $section['show_section_key'], 'show') ? 'checked' : '' }}
                                                {{ $section['is_show_section_disabled'] ? 'disabled' : '' }}>
                                            <label
                                                for="{{ $section['show_section_key'] }}">{{ __('Show Section') }}</label>
                                        </div>
                                    @endif
                                </div>

                                @foreach($section['fields'] as $field)
                                    <div
                                        class="bd-b-one bd-c-stroke m-0 py-10 rg-20 row {{$field['is_disabled'] ? 'd-none' : ''}}">
                                        <div class="col-md-6">
                                            <p>{{ __($field['label']) }}</p>
                                        </div>

                                        <!-- Show Checkbox for Field -->
                                        <div class="col-md-3 col-6">
                                            <div class="zCheck form-switch">
                                                <input class="form-check-input mt-0"
                                                       onchange="changeFormSetting(this, '{{ $field['slug'] }}', '{{ $field['show_key'] }}')"
                                                       value="1"
                                                       type="checkbox" role="switch"
                                                       id="{{ $field['slug'] }}_show"
                                                    {{ getOnboardingField($formSetting, $field['slug'], 'show') ? 'checked' : '' }}
                                                    {{ $field['is_disabled'] ? 'disabled' : '' }}>
                                                <label for="{{ $field['slug'] }}_show">{{ __('Show') }}</label>
                                            </div>
                                        </div>

                                        <!-- Required Checkbox for Field -->
                                        <div class="col-md-3 col-6">
                                            <div class="zCheck form-switch">
                                                <input class="form-check-input mt-0"
                                                       onchange="changeFormSetting(this, '{{ $field['slug'] }}', '{{ $field['required_key'] }}')"
                                                       value="1"
                                                       type="checkbox" role="switch"
                                                       id="{{ $field['slug'] }}_required"
                                                    {{ getOnboardingField($formSetting, $field['slug'], 'required') ? 'checked' : '' }}
                                                    {{ $field['is_disabled'] ? 'disabled' : '' }}>
                                                <label for="{{ $field['slug'] }}_required">{{ __('Required') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                        <div class="bd-c-stroke bd-one bd-ra-5 bg-white p-15 p-md-30 p-sm-25 mb-30">
                            <div
                                class="align-items-center bd-b-one bd-c-stroke d-flex justify-content-between mb-2 mb-20 pb-10">
                                <h4 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Custom Field Section') }}</h4>
                                <div class="zCheck form-switch">
                                    <input class="form-check-input mt-0"
                                           onchange="changeFormSetting(this, 'section_show_custom_filed', 'field_show')"
                                           type="checkbox" id="section_show_custom_filed"
                                        {{ getOnboardingField($formSetting, 'section_show_custom_filed', 'show') ? 'checked' : '' }}>
                                    <label for="show_custom_filed">{{ __('Show Section') }}</label>
                                </div>
                            </div>
                            <form action="{{route('admin.setting.onboarding_form_setting_custom_field')}}" class="ajax"
                                  method="POST"
                                  enctype="multipart/form-data" data-handler="commonResponse">
                                @csrf
                                <div class="row rg-25">
                                    <div class="col-md-12">
                                        <div id="customField" class="sf-sortable-form"></div>
                                        <input type="hidden" name="custom_field">
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" id="saveCustomField"
                                                class="float-end flipBtn sf-flipBtn-primary">{{__('Save')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="statusChangeRoute" value="{{ route('admin.setting.onboarding_form_setting_update') }}">
    @php
        $customFormFiled = $formSetting->where('field_slug', 'custom_field_form')->first();
    @endphp
@endsection
@push('script')
    <script>
        var editFields = [];
        @if ($customFormFiled)
            editFields = {!! $customFormFiled->field_show !!};
        @endif
    </script>
    <script src="{{ asset('admin/custom/js/onboarding-form-settings.js') }}"></script>
@endpush
