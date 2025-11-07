<div class="invoice-content">
    <div class="bd-b-one bd-c-stroke pb-25 mb-25 d-flex justify-content-between align-items-center flex-wrap">
        <!--  -->
        <a href="" data-bs-dismiss="modal" aria-label="Close"
           class="d-inline-flex align-items-center cg-13 fs-18 fw-500 lh-22 text-brand-primary">
            <i class="fa-solid fa-long-arrow-left"></i>
            {{__('Back')}}
        </a>

        <!--  -->
        <a target="blank" href="{{route(getPrefix().'.service_invoices.print', encodeId($orderInvoice->id))}}"
           class="flipBtn sf-flipBtn-primary">
            <span>{{__('Download')}}</span>
            <span>{{__('Download')}}</span>
            <span>{{__('Download')}}</span>
        </a>
    </div>
    <!--  -->
    <div class="d-flex justify-content-between align-items-center pb-50">
        <!--  -->
        <div class="max-w-167">
            <img src="{{ getSettingImage('app_logo_black') }}" alt="{{ getOption('app_name') }}"/>
        </div>
        <!--  -->

        <div class="d-flex align-items-center cg-10">
            @if ($orderInvoice->payment_status == PAYMENT_STATUS_PAID)
                <p class="bd-ra-5 py-4 px-14 zBadge-active fs-12 fw-500 lh-24 text-green">{{__('Paid')}}</p>
            @elseif ($orderInvoice->payment_status == PAYMENT_STATUS_PENDING)
                <p class="bd-ra-5 py-4 px-14 zBadge-pending fs-12 fw-500 lh-24">{{__('Pending')}}</p>
            @elseif ($orderInvoice->payment_status == PAYMENT_STATUS_CANCELLED)
                <p class="bd-ra-5 py-4 px-14 zBadge-cancel fs-12 fw-500 lh-24">{{__('Cancelled')}}</p>
            @endif
        </div>

    </div>
    <!--  -->
    <div class="bd-ra-10 bg-secondary p-25 mb-30">
        <div class="d-flex justify-content-between invoice-item">
            <div class="item">
                <h4 class="fs-27 fw-600 lh-40 text-title-text pb-10">{{__('Invoice')}}</h4>
                <p class="fs-15 fw-500 lh-20 text-title-text"> {{$orderInvoice->invoiceID}}</p>
            </div>
            <div class="item">
                <p class="fs-14 fw-600 lh-24 text-para-text">{{__('Invoice To')}}:</p>
                <p class="fs-14 fw-400 lh-24 text-para-text">{{$orderInvoice->student?->name}}</p>
                <p class="fs-14 fw-400 lh-24 mailto:text-para-text">{{$orderInvoice->student?->email}}</p>
            </div>
            <div class="item">
                <p class="fs-14 fw-600 lh-24 text-para-text">{{__('Pay to')}}:</p>
                <p class="fs-14 fw-400 lh-24 text-para-text">{{ getOption('app_name') }}</p>
                <p class="fs-14 fw-400 lh-24 text-para-text">{{ getOption('app_location') }}</p>
                <p class="fs-14 fw-400 lh-24 text-para-text">{{ getOption('app_contact_number') }}</p>
            </div>
        </div>
    </div>
    <!--  -->

    <div class="pb-15">
        <h4 class="fs-18 fw-600 lh-28 text-title-text pb-15">{{__('Invoice Items')}}</h4>
        <div class="table-responsive pb-15">
            <table class="table zTable zTable-last-item-right zTable-last-item-border">
                <thead>
                <tr>
                    <th>
                        <div class="text-nowrap">{{__('OrderID')}}</div>
                    </th>
                    <th>
                        <div class="text-nowrap">{{__('Details/Service')}}</div>
                    </th>
                    <th>
                        <div>{{__('Price')}}</div>
                    </th>
                    <th>
                        <div>{{__('Quantity')}}</div>
                    </th>
                    <th>
                        <div>{{__('Total')}}</div>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{$orderInvoice->service_order->orderID}}</td>
                    <td>{{$orderInvoice->details ?? $orderInvoice->service->title}}</td>
                    <td>{{showPrice($orderInvoice->total)}}</td>
                    <td>{{1}}</td>
                    <td>{{showPrice($orderInvoice->total)}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!--  -->
    <div class="max-w-374 w-100 ms-auto mb-30 text-end invoiceTotal">
        <ul class="zList-pb-15">
            <li>
                <div class="row align-items-center">
                    <div class="col-6">
                        <p class="fs-14 fw-500 lh-17 text-para-text">{{__('Total')}}:</p>
                    </div>
                    <div class="col-6">
                        <p class="fs-14 fw-600 lh-17 text-title-text">{{showPrice($orderInvoice->total)}}</p>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <!--  -->
    <h4 class="fs-18 fw-600 lh-28 text-title-text pb-15">{{__('Transaction Details')}}</h4>
    <table class="table zTable zTable-last-item-right zTable-last-item-border">
        <thead>
        <tr>
            <th>
                <div>{{__("Date")}}</div>
            </th>
            <th>
                <div>{{__("Payment Gateway")}}</div>
            </th>
            <th>
                <div>{{__("Transaction ID")}}</div>
            </th>
            <th>
                <div>{{__("Amount")}}</div>
            </th>
        </tr>
        </thead>
        <tbody>
        @if($orderInvoice->payment_status == PAYMENT_STATUS_PAID && $orderInvoice->payment)
            <tr>
                <td>{{$orderInvoice->payment->created_at}}</td>
                <td>{{$orderInvoice->payment->gateway != null?$orderInvoice->payment->gateway->title:'N/A'}}</td>
                <td>{{$orderInvoice->payment->paymentId}}</td>
                <td>{{showPrice($orderInvoice->payment->grand_total)}}</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
