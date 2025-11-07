<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Update Staff') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route('admin.staffs.update', $user->id) }}" method="post"
      data-handler="commonResponseForModal">
    @csrf
    @method('patch')
    <div class="row rg-20 mb-20">
        <div class="col-md-6">
            <label for="first_name" class="zForm-label-alt">{{ __('First Name') }}
                <span class="text-danger">*</span>
            </label>
            <input type="text" name="first_name" id="first_name"
                   placeholder="{{ __('Type First Name') }}" class="form-control zForm-control-alt"
                   value="{{ old('first_name', $user->first_name) }}">
        </div>

        <div class="col-md-6">
            <label for="last_name" class="zForm-label-alt">{{ __('Last Name') }}
                <span class="text-danger">*</span>
            </label>
            <input type="text" name="last_name" id="last_name" placeholder="{{ __('Type Last Name') }}"
                   class="form-control zForm-control-alt"
                   value="{{ old('last_name', $user->last_name) }}">
        </div>

        <div class="col-md-6">
            <label for="email" class="zForm-label-alt">{{ __('Email') }} <span
                    class="text-danger">*</span></label>
            <input type="email" name="email" id="email" placeholder="{{ __('Email') }}"
                   class="form-control zForm-control-alt"
                   value="{{ old('email', $user->email) }}">
        </div>

        <div class="col-md-6">
            <label for="mobile" class="zForm-label-alt">{{ __('Mobile') }} <span
                    class="text-danger">*</span></label>
            <input type="text" name="mobile" value="{{ old('mobile', $user->mobile) }}" id="mobile" placeholder="{{ __('Mobile') }}"
                   class="form-control zForm-control-alt">
        </div>

        <div class="col-md-6">
            <label for="password" class="zForm-label-alt">{{ __('Password') }}</label>
            <input type="password" name="password" id="password" placeholder="{{ __('Password') }}"
                   class="form-control zForm-control-alt">
            <small class="fs-12 fst-italic">{{__('Leave it blank if do not want to update password')}}</small>
        </div>

        <div class="col-md-6">
            <label for="status" class="zForm-label-alt">{{ __('Status') }} <span
                    class="text-danger">*</span></label>
            <select class="sf-select-without-search" id="status" name="status">
                <option value="{{ STATUS_ACTIVE }}" {{ $user->status == STATUS_ACTIVE ? 'selected' : '' }}>{{ __('Active') }}</option>
                <option value="{{ STATUS_DEACTIVATE }}" {{ $user->status == STATUS_DEACTIVATE ? 'selected' : '' }}>{{ __('Deactivate') }}</option>
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
                        <input class="form-check-input" type="checkbox" name="roles[]" id="role-{{$role->id}}" value="{{ $role->name }}"
                            {{ in_array($role->id, $user->roles->pluck('id')->toArray()) ? 'checked' : '' }}>
                        <label class="zForm-label-alt" for="role-{{$role->id}}">{{ $role->name }}</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
        <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">
            <span>{{__('Update')}}</span>
            <span>{{__('Update')}}</span>
            <span>{{__('Update')}}</span>
        </button>
    </div>
</form>
