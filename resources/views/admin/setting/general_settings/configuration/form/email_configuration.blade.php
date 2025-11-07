<div class="email-inbox__area">
    <div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
        <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Mail Configuration') }}</h2>
        <a href="javascript:void(0);" id="sendTestMailBtn"
            class="fs-15 fw-500 lh-25 bg-main-color py-5 px-10 text-white bd-ra-12 d-inline-flex align-items-center g-5">
            <i class="fa fa-envelope"></i> {{ __('Send Test Mail') }}
        </a>
        <button type="button" class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>

    <form class="ajax" action="{{ route('admin.setting.settings_env.update') }}" method="POST"
        enctype="multipart/form-data" data-handler="commonResponseForModal">
        @csrf
        <div class="row rg-20">
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-3">
                <label class="zForm-label-alt">{{ __('MAIL MAILER') }} <span class="text-danger">*</span></label>
                <input type="text" name="MAIL_MAILER" value="{{ env('MAIL_MAILER') }}"
                    class="form-control zForm-control-alt">
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-3">
                <label class="zForm-label-alt">{{ __('MAIL HOST') }} <span class="text-danger">*</span></label>
                <input type="text" name="MAIL_HOST" value="{{ env('MAIL_HOST') }}"
                    class="form-control zForm-control-alt">
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-3">
                <label class="zForm-label-alt">{{ __('MAIL PORT') }} <span class="text-danger">*</span></label>
                <input type="text" name="MAIL_PORT" value="{{ env('MAIL_PORT') }}"
                    class="form-control zForm-control-alt">
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-3">
                <label class="zForm-label-alt">{{ __('MAIL USERNAME') }} <span class="text-danger">*</span></label>
                <input type="text" name="MAIL_USERNAME" value="{{ env('MAIL_USERNAME') }}"
                    class="form-control zForm-control-alt">
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-3">
                <label class="zForm-label-alt">{{ __('MAIL PASSWORD') }} <span class="text-danger">*</span></label>
                <input type="password" name="MAIL_PASSWORD" value="{{ env('MAIL_PASSWORD') }}"
                    class="form-control zForm-control-alt">
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-3">
                <label for="MAIL_ENCRYPTION" class="zForm-label-alt">{{ __('MAIL ENCRYPTION') }}<span
                        class="text-danger">*</span></label>
                <select name="MAIL_ENCRYPTION" class="form-control zForm-control-alt sf-select-edit-modal">
                    <option value="tls" {{ env('MAIL_ENCRYPTION') == 'tls' ? 'selected' : '' }}>
                        {{ __('tls') }}
                    </option>
                    <option value="ssl" {{ env('MAIL_ENCRYPTION') == 'ssl' ? 'selected' : '' }}>
                        {{ __('ssl') }}
                    </option>
                </select>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-3">
                <label class="zForm-label-alt">{{ __('MAIL FROM ADDRESS') }} <span class="text-danger">*</span></label>
                <input type="text" name="MAIL_FROM_ADDRESS" value="{{ env('MAIL_FROM_ADDRESS') }}"
                    class="form-control zForm-control-alt">
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-3">
                <label class="zForm-label-alt">{{ __('MAIL FROM NAME') }} <span class="text-danger">*</span></label>
                <input type="text" name="MAIL_FROM_NAME" value="{{ env('MAIL_FROM_NAME') }}"
                    class="form-control zForm-control-alt">
            </div>
        </div>
        <div class="d-flex g-12 flex-wrap mt-25">
            <button class="flipBtn sf-flipBtn-primary" type="submit">
                <span>{{ __('Save') }}</span>
                <span>{{ __('Save') }}</span>
                <span>{{ __('Save') }}</span>
            </button>
        </div>
    </form>
</div>
