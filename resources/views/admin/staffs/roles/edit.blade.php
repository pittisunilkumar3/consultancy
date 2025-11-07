<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Update Service') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route('admin.staffs.roles.update', $role->id) }}" method="post"
      data-handler="settingCommonHandler">
    @method('PATCH')
    @csrf
    <div class="row rg-20">
        <div class="">
            <label for="symbol" class="zForm-label-alt">{{ __('Name') }} <span
                    class="text-danger">*</span></label>
            <input type="text" name="name" id="name" value="{{$role->name}}" placeholder="{{ __('Type Name') }}"
                   class="form-control zForm-control-alt">
        </div>

        <div class="">
            <label for="status" class="zForm-label-alt">{{ __('Status') }} <span class="text-danger">*</span></label>
            <select class="sf-select-without-search" id="eventType" name="status">
                <option value="{{ STATUS_ACTIVE }}" {{ $role->status == STATUS_ACTIVE ? 'selected' : '' }}>
                    {{ __('Active') }}
                </option>
                <option value="{{ STATUS_DEACTIVATE }}" {{ $role->status == STATUS_DEACTIVATE ? 'selected' : '' }}>
                    {{ __('Deactivate') }}
                </option>
            </select>
        </div>

        <div class="bd-b-one bd-c-stroke-2">
            <h5 class="fs-16 fw-700 lh-24 text-title-text">{{__('Permissions')}}</h5>
        </div>
        <div class="">
            <div class="d-flex flex-wrap g-10">
                @foreach($permissions as $permission)
                    <div class="">
                        <div class="zCheck form-switch">
                            <input class="form-check-input" {{in_array($permission->name, $oldPermissions) ? 'checked' : ''}} type="checkbox" name="permissions[]" id="edit-{{$permission->id}}" value="{{$permission->name}}" role="switch">
                            <label class="zForm-label-alt" for="edit-{{$permission->id}}">{{$permission->name}}</label>
                        </div>
                    </div>
                @endforeach
            </div>
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
