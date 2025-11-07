<div class="modal-header">
    <h5 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Update Gateway') }}</h5>
    <button type="button" class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
</div>
<form class="ajax" action="{{ route('admin.setting.gateway.store') }}" method="POST" autocomplete="off" data-handler="commonResponseWithPageLoad" id="gatewayForm">
    @csrf
    <input type="hidden" name="id" id="id" value="{{ encodeId($gateway->id) }}">
    <div class="modal-body">
        <div class="row rg-20">
            <div class="col-12">
                <div><img src="{{ asset($gateway->image) }}" /></div>
            </div>
            <div class="col-6">
                <label class="zForm-label-alt">{{ __('Title') }}</label>
                <input type="text" name="title" value="{{ $gateway->title }}"
                       class="form-control zForm-control-alt" id="addPaymentTitle" readonly />
            </div>
            <div class="col-6">
                <label class="zForm-label-alt">{{ __('Slug') }}</label>
                <input type="text" name="slug" value="{{ $gateway->slug }}"
                       class="form-control zForm-control-alt" readonly />
            </div>
            <div class="col-md-12">
                <div class="row rg-20">
                    <div class="col-md-6">
                        <label for="addPaymentStatus" class="zForm-label-alt">{{ __('Status') }}</label>
                        <select class="sf-select-two" name="status">
                            <option value="1" {{ $gateway->status == ACTIVE ? 'selected' : '' }}>
                                {{ __('Active') }}</option>
                            <option value="0" {{ $gateway->status != ACTIVE ? 'selected' : '' }}>
                                {{ __('Deactivate') }}</option>
                        </select>
                    </div>
                    @if ($gateway->slug != 'bank')
                        <div class="col-md-6">
                            <label for="addPaymentMode" class="zForm-label-alt">{{ __('Mode') }}</label>
                            <select class="sf-select-two" name="mode">
                                <option value="1"
                                    {{ $gateway->mode == GATEWAY_MODE_LIVE ? 'selected' : '' }}>
                                    {{ __('Live') }}</option>
                                <option value="2"
                                    {{ $gateway->mode != GATEWAY_MODE_LIVE ? 'selected' : '' }}>
                                    {{ __('Sandbox') }}</option>
                            </select>
                        </div>
                    @endif
                </div>
            </div>

            @if ($gatewaySettings)
                <div class="col-md-12">
                    <div class="row rg-20">
                        @foreach ($gatewaySettings as $gatewaySetting)
                            @if ($gatewaySetting['name'] == 'url' && $gatewaySetting['is_show'] == 1)
                                <div class="col-md-6">
                                    <label for="addPaymentClientID"
                                           class="zForm-label-alt">{{ __($gatewaySetting['label']) }}</label>
                                    <input type="text" name="url" value="{{ $gateway->url }}"
                                           class="form-control zForm-control-alt" id="addPaymentClientID"
                                           placeholder="{{ __($gatewaySetting['label']) }}" />
                                </div>
                            @endif
                            @if ($gatewaySetting['name'] == 'key' && $gatewaySetting['is_show'] == 1)
                                <div class="col-md-6">
                                    <label for="addPaymentClientID"
                                           class="zForm-label-alt">{{ __($gatewaySetting['label']) }}</label>
                                    <input type="text" name="key" value="{{ $gateway->key }}"
                                           class="form-control zForm-control-alt" id="addPaymentClientID"
                                           placeholder="{{ __($gatewaySetting['label']) }}" />
                                    <small
                                        class="d-none small">{{ __('Client id, Public Key, Key, Store id, Api Key') }}</small>
                                </div>
                            @endif
                            @if ($gatewaySetting['name'] == 'secret' && $gatewaySetting['is_show'] == 1)
                                <div class="col-md-6">
                                    <label for="addPaymentSecret"
                                           class="zForm-label-alt">{{ __($gatewaySetting['label']) }}</label>
                                    <input type="text" name="secret" value="{{ $gateway->secret }}"
                                           class="form-control zForm-control-alt" id="addPaymentSecret"
                                           placeholder="{{ __($gatewaySetting['label']) }}" />
                                    <small
                                        class="d-none small">{{ __('Client Secret, Secret, Store Password, Auth Token') }}</small>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @if ($gateway->slug == 'bank')
            <div class="row rg-20">
                <div class="col-md-12 mt-20">
                    <div class="d-flex justify-content-between align-items-center g-10 pb-8">
                        <h4 class="fs-14 fw-500 lh-20 text-title-text">{{ __('Bank Details') }}</h4>
                        <button type="button"
                                class="bd-one bd-c-stroke rounded-circle w-25 h-25 d-flex justify-content-center align-items-center bg-transparent fs-15 lh-25 text-para-text addBankBtn">+</button>
                    </div>
                    <ul class="zList-pb-16 bankItemLists">
                        @foreach ($gatewayBanks as $bank)
                            <li class="d-flex justify-content-between align-items-center g-10">
                                <input type="hidden" name="bank[id][]" value="{{ $bank->id }}">
                                <div class="flex-grow-1 d-flex flex-wrap flex-sm-nowrap g-10 left">
                                    <div class="flex-grow-1">
                                        <input type="text" class="form-control zForm-control-alt"
                                               placeholder="Name" name="bank[name][]"
                                               value="{{ $bank->name }}">
                                    </div>
                                    <div class="flex-grow-1">
                                        <textarea name="bank[details][]" class="form-control zForm-control-alt" placeholder="Details">{{ $bank->details }}</textarea>
                                    </div>
                                </div>
                                <button type="button"
                                        class="flex-shrink-0 bd-one bd-c-stroke rounded-circle w-25 h-25 d-flex justify-content-center align-items-center bg-transparent text-danger removedBankBtn">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="row rg-20">
            <div class="col-md-12 mt-20">
                <div class="d-flex justify-content-between align-items-center g-10 pb-8">
                    <h4 class="fs-14 fw-500 lh-20 text-title-text">{{ __('Conversion Rate') }}</h4>
                    <button type="button"
                            class="bd-one bd-c-stroke rounded-circle w-25 h-25 d-flex justify-content-center align-items-center bg-transparent fs-15 lh-25 text-para-text addCurrencyBtn">+</button>
                </div>
                <ul class="zList-pb-16" id="currencyConversionRateSection">
                    @foreach ($gatewayCurrencies as $gatewayCurrency)
                        <li
                            class="d-flex justify-content-between align-items-center g-10 paymentConversionRate">
                            <input type="hidden" name="currency_id[]"
                                   value="{{ $gatewayCurrency->id }}">
                            <div class="flex-grow-1 d-flex flex-wrap flex-sm-nowrap left">
                                <select class="sf-select currency" name="currency[]">
                                    @foreach (getCurrency() as $key => $currency)
                                        <option value="{{ $key }}"
                                            {{ $key == $gatewayCurrency->currency ? 'selected' : '' }}>
                                            {{ $currency }}</option>
                                    @endforeach
                                </select>
                                <p class="p-13 fs-14 fw-400 lh-22 text-title-text bg-body-bg text-nowrap bd-l-one bd-r-one bd-c-stroke">
                                    1{{ getCurrencySymbol() }} =
                                </p>
                                <input type="text" name="conversion_rate[]"
                                       value="{{ $gatewayCurrency->conversion_rate }}"
                                       class="form-control zForm-control-alt" id=""
                                       placeholder="1.00" />
                                <p class="p-13 fs-14 fw-400 lh-22 text-title-text bg-body-bg text-nowrap bd-l-one bd-c-stroke">
                                    {{ $gatewayCurrency->currency }}
                                </p>
                            </div>
                            <button type="button"
                                    class="flex-shrink-0 bd-one bd-c-stroke rounded-circle w-25 h-25 fs-12 d-flex justify-content-center align-items-center bg-transparent text-danger removedCurrencyBtn">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="flipBtn sf-flipBtn-primary">
            <span>{{ __('Update') }}</span>
            <span>{{ __('Update') }}</span>
            <span>{{ __('Update') }}</span>
        </button>
    </div>
</form>
