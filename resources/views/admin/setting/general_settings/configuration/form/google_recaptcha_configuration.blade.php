<div class="email-inbox__area">
    <div class="item-top mb-30 d-flex flex-wrap justify-content-between">
        <h2>{{ __('Google Recaptcha Credentials') }}</h2>
        <div class="mClose">
            <button type="button" class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                    data-bs-dismiss="modal" aria-label="Close">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    </div>
    <form class="ajax" action="{{ route('admin.setting.settings_env.update') }}" method="post" class="form-horizontal"
        data-handler="commonResponseForModal">
        @csrf
        <div class="form-group text-black row mb-3">
            <div class="row rg-15">
                <div class="col-lg-12">
                    <label class="zForm-label-alt">{{ __('Google Recaptcha Site Key') }}</label>
                    <input type="text" name="google_recaptcha_site_key" id="google_recaptcha_site_key"
                           value="{{ getOption('google_recaptcha_site_key') }}" class="form-control zForm-control-alt">
                </div>
                <div class="col-lg-12">
                    <label class="zForm-label-alt">{{ __('Google Recaptcha Secret Key') }}</label>
                    <input type="text" name="google_recaptcha_secret_key" id="google_recaptcha_secret_key"
                           value="{{ getOption('google_recaptcha_secret_key') }}" class="form-control zForm-control-alt">
                </div>
                <div class="col-md-12">
                    <button type="submit" class="flipBtn sf-flipBtn-primary">
                        <span>{{ __('Update') }}</span>
                        <span>{{ __('Update') }}</span>
                        <span>{{ __('Update') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
