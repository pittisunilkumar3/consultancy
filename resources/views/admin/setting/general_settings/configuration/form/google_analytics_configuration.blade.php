<div class="customers__area">
    <div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
        <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Google analytics configuration') }}</h2>
        <div class="mClose">
            <button type="button" class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    </div>
    <form class="ajax" action="{{ route('admin.setting.settings_env.update') }}" method="post"
        class="form-horizontal" data-handler="commonResponseForModal">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <label class="zForm-label-alt">{{ __('Google Analytics Tracking Id') }} </label>
                <input type="text" min="0" max="100" step="any" name="google_analytics_tracking_id"
                    value="{{ getOption('google_analytics_tracking_id') }}" class="form-control zForm-control-alt">
            </div>
        </div>
        <div class="d-flex g-12 flex-wrap mt-25">
            <button class="flipBtn sf-flipBtn-primary" type="submit">
                <span>{{ __('Update') }}</span>
                <span>{{ __('Update') }}</span>
                <span>{{ __('Update') }}</span>
            </button>
        </div>
    </form>
</div>
