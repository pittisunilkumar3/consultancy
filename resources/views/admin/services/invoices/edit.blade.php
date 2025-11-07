<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Update Service Order Invoice') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route('admin.service_invoices.store', encodeId($serviceOrderInvoice->id)) }}" method="post"
      data-handler="commonResponseForModal">
    @csrf
    @method('POST')

    @include('admin.services.invoices.form')

    <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
        <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">
            <span>{{__('Update')}}</span>
            <span>{{__('Update')}}</span>
            <span>{{__('Update')}}</span>
        </button>
    </div>
</form>
