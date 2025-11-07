@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="p-sm-30 p-15">
        <!--  -->
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white">
            <form class="ajax" action="{{ route('consultant.consultant_profile.update') }}" method="post"
                  data-handler="commonResponseForModal">
                @csrf
                <input type="hidden" name="id" value="{{$consulter->id}}">
                <div class="row rg-20 mb-20">
                    <div class="col-md-4">
                        <label for="edit_first_name" class="zForm-label-alt">{{ __('First Name') }}
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="first_name" id="edit_first_name"
                               value="{{ old('first_name', $consulter->first_name) }}"
                               placeholder="{{ __('Type First Name') }}" class="form-control zForm-control-alt">
                    </div>

                    <div class="col-md-4">
                        <label for="edit_last_name" class="zForm-label-alt">{{ __('Last Name') }}
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="last_name" id="edit_last_name"
                               value="{{ old('last_name', $consulter->last_name) }}"
                               placeholder="{{ __('Type Last Name') }}" class="form-control zForm-control-alt">
                    </div>

                    <div class="col-md-4">
                        <label for="edit_fee" class="zForm-label-alt">{{ __('Fee') }}({{config('app.currencySymbol')}})
                            <span
                                class="text-danger">*</span></label>
                        <input type="number" name="fee" min="0" id="edit_fee"
                               value="{{ old('fee', $consulter->fee) }}"
                               placeholder="{{ __('Fee') }}" class="form-control zForm-control-alt">
                        <small class="fs-12 fst-italic">{{__('Input it 0 if its free')}}</small>
                    </div>

                    <div class="col-md-4">
                        <label for="edit_off_days" class="zForm-label-alt">{{ __('Off Days') }}</label>
                        <select class="sf-select-checkbox" multiple="multiple" id="edit_off_days" name="off_days[]">
                            @foreach(offDays() as $index => $offDay)
                                <option value="{{ $index }}"
                                    {{ in_array($index, old('off_days', $consulter->day_off ?? [])) ? 'selected' : '' }}>
                                    {{ $offDay }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-4">
                        <label for="edit_slot_ids" class="zForm-label-alt">{{ __('Slots') }}</label>
                        <select class="sf-select-checkbox" multiple="multiple" id="edit_slot_ids" name="slot_ids[]">
                            @foreach($slots as $slot)
                                <option value="{{ $slot->id }}"
                                    {{ $consulter->slots->contains($slot->id) ? 'selected' : '' }}>
                                    {{ $slot->start_time . ' - ' . $slot->end_time }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="professional_title" class="zForm-label-alt">{{ __('Professional Title') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" name="professional_title"
                               value="{{ old('professional_title', $consulter->professional_title) }}"
                               id="professional_title" placeholder="{{ __('Professional Title') }}"
                               class="form-control zForm-control-alt">
                    </div>
                    <div class="col-md-4">
                        <label for="experience" class="zForm-label-alt">{{ __('Experience') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" name="experience" id="experience"
                               value="{{ old('experience', $consulter->experience) }}"
                               placeholder="{{ __('Experience') }}"
                               class="form-control zForm-control-alt">
                    </div>

                    <div class="col-md-12">
                        <label for="about_me" class="zForm-label-alt">{{ __('About Me') }} <span
                                class="text-danger">*</span></label>
                        <textarea class="summernoteOne" name="about_me" id="about_me"
                                  placeholder="{{ __('About Me') }}">{!! $consulter->about_me !!}</textarea>
                    </div>
                </div>
                <div class="d-flex g-12 flex-wrap mt-25">
                    <button type="submit" class="sf-btn-primary flex-shrink-0">{{ __('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
