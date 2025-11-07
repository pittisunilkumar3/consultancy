<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Invoice Order Payment') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route('admin.service_invoices.status_change', encodeId($orderInvoice->id)) }}" method="post"
      data-handler="commonResponseForModal">
    @csrf
    <div class="row rg-20 mb-20">
        <div class="col-md-12">
            <label for="invoiceID" class="zForm-label-alt">{{ __('Invoice ID') }} <span
                    class="text-danger">*</span></label>
            <input type="text" name="invoiceID" id="invoiceID" readonly value="{{ $orderInvoice->invoiceID }}"
                   class="form-control zForm-control-alt">
        </div>

        <input type="hidden" id="pay-amount" value="{{$orderInvoice->total}}">

        <div class="col-md-12">
            <label for="status-change-payment-status" class="zForm-label-alt">{{ __('Payment Status') }} <span
                    class="text-danger">*</span></label>
            <select class="sf-select-without-search" id="status-change-payment-status"
                    name="payment_status">
                <option
                    value="{{ PAYMENT_STATUS_PAID }}" {{ isset($orderInvoice) && $orderInvoice->payment_status == PAYMENT_STATUS_PAID ? 'selected' : '' }}>{{ __('Paid') }}</option>
                <option
                    value="{{ PAYMENT_STATUS_CANCELLED }}" {{ isset($orderInvoice) && $orderInvoice->payment_status == PAYMENT_STATUS_CANCELLED ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
            </select>
        </div>
        <div class="col-md-12" id="change-status-gateway-block">
            <label for="change-status-gateway" class="zForm-label-alt">{{ __('Gateway') }} <span
                    class="text-danger">*</span></label>
            <select class="sf-select-two" id="change-status-gateway" name="gateway">
                <option disabled @if(!isset($orderInvoice) || !$orderInvoice->payment) selected @endif class="d-none"
                        value="">{{__('Select Gateway')}}</option>
                @foreach($gateways as $gateway)
                    <option value="{{$gateway->slug}}"
                        {{ isset($orderInvoice) && $orderInvoice->payment?->gateway->slug == $gateway->slug ? 'selected' : '' }}>
                        {{ $gateway->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12" id="change-status-gateway-currency-block">
            <label for="change-status-gateway-currency" class="zForm-label-alt">{{ __('Payment Currency') }} <span
                    class="text-danger">*</span></label>
            <select class="sf-select-two" id="change-status-gateway-currency" name="gateway_currency">
                <option value="{{ isset($orderInvoice) ? $orderInvoice->payment?->payment_currency : '' }}">
                    {{ isset($orderInvoice) ? $orderInvoice->payment?->payment_currency : '-- Select Currency --' }}
                </option>
            </select>
        </div>
    </div>
    <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
        <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">
            <div class="spinner-border w-25 h-25 ml-10 d-none" role="status">
                <span class="visually-hidden"></span>
            </div>
            <div class="innerWrap">
                <span>{{__('Update')}}</span>
                <span>{{__('Update')}}</span>
                <span>{{__('Update')}}</span>
            </div>
        </button>
    </div>
</form>
