<div class="email-inbox__area">
    <div class="item-top mb-30 d-flex flex-wrap justify-content-between">
        <h2>{{ __('Social Login (Facebook) Configuration') }}</h2>
        <div class="mClose">
            <button type="button" class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                    data-bs-dismiss="modal" aria-label="Close">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    </div>
    <form class="ajax" action="{{ route('admin.setting.settings_env.update') }}" method="POST"
          enctype="multipart/form-data" data-handler="commonResponseForModal">
        @csrf
        <div class="row rg-15">
            <div class="col-lg-12">
                <label class="zForm-label-alt">{{ __('Facebook Client ID') }}</label>
                <input type="text" name="facebook_client_id" id="facebook_client_id"
                       value="{{ getOption('facebook_client_id') }}" class="form-control zForm-control-alt">
            </div>
            <div class="col-lg-12">
                <label class="zForm-label-alt">{{ __('Facebook Client Secret') }} </label>
                <input type="text" name="facebook_client_secret" id="facebook_client_secret" value="{{ getOption('facebook_client_secret') }}" class="form-control zForm-control-alt">
            </div>
            <div class="col-lg-12">
                <div class="form-group text-black row mb-3">
                    <label>{{ __('Set callback URL') }} : <strong>{{ url('/auth/facebook/callback') }}</strong></label>
                </div>
            </div>
            <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
                <button type="submit" class="flipBtn sf-flipBtn-primary">
                    <span>{{__('Save')}}</span>
                    <span>{{__('Save')}}</span>
                    <span>{{__('Save')}}</span>
                </button>
            </div>
        </div>
    </form>
</div>
