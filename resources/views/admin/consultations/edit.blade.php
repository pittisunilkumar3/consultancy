<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-400 lh-22 text-title-text">{{ __('Update Consulter') }}</h2>
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
            <label for="edit_email" class="zForm-label-alt">{{ __('Email') }} <span
                    class="text-danger">*</span></label>
            <input type="email" name="email" id="edit_email"
                   value="{{ old('email', $consulter->email) }}"
                   placeholder="{{ __('Email') }}" class="form-control zForm-control-alt">
        </div>

        <div class="col-md-4">
            <label for="edit_mobile" class="zForm-label-alt">{{ __('Mobile') }} <span
                    class="text-danger">*</span></label>
            <input type="text" name="mobile" id="edit_mobile"
                   value="{{ old('mobile', $consulter->mobile) }}"
                   placeholder="{{ __('Mobile') }}" class="form-control zForm-control-alt">
        </div>

        <div class="col-md-4">
            <label for="edit_password" class="zForm-label-alt">{{ __('Password') }}</label>
            <input type="password" name="password" id="edit_password"
                   placeholder="{{ __('Password') }}"
                   class="form-control zForm-control-alt">
            <small class="fs-12 fst-italic">{{__('Leave it blank if do not want to update password')}}</small>
        </div>

        <div class="col-md-4">
            <label for="edit_fee" class="zForm-label-alt">{{ __('Fee') }}({{config('app.currencySymbol')}}) <span
                    class="text-danger">*</span></label>
            <input type="number" name="fee" min="0" id="edit_fee"
                   value="{{ old('fee', $consulter->fee) }}"
                   placeholder="{{ __('Fee') }}" class="form-control zForm-control-alt">
            <small class="fs-12 fst-italic">{{__('Input it 0 if its free')}}</small>
        </div>

        <div class="col-md-4">
            <label for="edit_mAttachment" class="zForm-label-alt">{{ __('Image') }}  <span class="text-mime-type">{{ __('(jpeg,png,jpg,svg,webp)') }}</span></label>
            @if($consulter->image)
                <small>
                    <a href="{{getFileUrl($consulter->image)}}" target="_blank"><i class="fa-solid fa-eye"></i></a>
                </small>
            @endif
            <div class="file-upload-one">
                <label for="edit_mAttachment">
                    <p class="fileName fs-14 fw-500 lh-24 text-para-text max-w-200">{{ __('Choose Image to upload') }}</p>
                    <p class="fs-15 fw-700 lh-28 text-white">{{ __('Browse File') }}</p>
                </label>
                <input type="file" name="image" id="edit_mAttachment"
                       class="fileUploadInput invisible position-absolute">
            </div>
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
            <label for="edit_gender" class="zForm-label-alt">{{ __('Gender') }} <span
                    class="text-danger">*</span></label>
            <select class="sf-select-without-search" id="edit_gender" name="gender">
                @foreach(gender() as $index => $gender)
                    <option value="{{ $index }}"
                        {{ old('gender', $consulter->gender) == $index ? 'selected' : '' }}>
                        {{ $gender }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label for="professional_title" class="zForm-label-alt">{{ __('Professional Title') }} <span
                    class="text-danger">*</span></label>
            <input type="text" name="professional_title" value="{{ old('professional_title', $consulter->professional_title) }}" id="professional_title" placeholder="{{ __('Professional Title') }}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="col-md-4">
            <label for="experience" class="zForm-label-alt">{{ __('Experience') }} <span
                    class="text-danger">*</span></label>
            <input type="text" name="experience" id="experience" value="{{ old('experience', $consulter->experience) }}" placeholder="{{ __('Experience') }}"
                   class="form-control zForm-control-alt">
        </div>

        <div class="col-md-12">
            <label for="about_me" class="zForm-label-alt">{{ __('About Me') }} <span class="text-danger">*</span></label>
            <textarea class="summernoteOne" name="about_me" id="about_me" placeholder="{{ __('About Me') }}">{!! $consulter->about_me !!}</textarea>
        </div>

        <div class="col-md-4">
            <label for="edit_status" class="zForm-label-alt">{{ __('Status') }} <span
                    class="text-danger">*</span></label>
            <select class="sf-select-without-search" id="edit_status" name="status">
                <option value="{{ STATUS_ACTIVE }}"
                    {{ old('status', $consulter->status) == STATUS_ACTIVE ? 'selected' : '' }}>
                    {{ __('Active') }}
                </option>
                <option value="{{ STATUS_DEACTIVATE }}"
                    {{ old('status', $consulter->status) == STATUS_DEACTIVATE ? 'selected' : '' }}>
                    {{ __('Deactivate') }}
                </option>
            </select>
        </div>
    </div>
    <div class="d-flex g-12 flex-wrap mt-25">
        <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">
            <span>{{__('Update Now')}}</span>
            <span>{{__('Update Now')}}</span>
            <span>{{__('Update Now')}}</span>
        </button>
    </div>
</form>
