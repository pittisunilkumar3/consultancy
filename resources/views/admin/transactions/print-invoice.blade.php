<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.responsive.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/scss/style.css') }}"/>

    <style>
        @page {
            margin: 0px;
            padding: 0px;
        }

        body {
            margin: 0px;
            padding: 0px;
        }

        img {
            width: 120px;
        }

        * {
            overflow: hidden;
        }
    </style>
</head>

<body>
<div class="modal-xl mx-auto m-5">
    <div class="row">
        <div class="col-md-12">
            <div class="p-3 bg-white rounded">
                <div class="px-20" id="printableArea">

                    <!--  -->
                    <div class="d-flex justify-content-between align-items-center pb-50 pt-20">
                        <!--  -->
                        <div class="max-w-167">
                            <img src="{{ getSettingImage('app_logo_black') }}" alt="{{ getOption('app_name') }}"/></div>
                        <!--  -->
                        <div class="d-flex align-items-center cg-10">
                            <p class="bd-ra-5 py-4 px-14 zBadge-active fs-12 fw-500 lh-24 text-green">
                                @if($transaction->amount > 0)
                                    {{__('Paid')}}
                                @else
                                    {{__('Free')}}
                                @endif
                            </p>
                        </div>
                    </div>
                    <!--  -->
                    <div class="bd-ra-10 bg-secondary p-25 mb-30">
                        <div class="d-flex justify-content-between invoice-item">
                            <div class="item">
                                <h4 class="fs-27 fw-600 lh-40 text-title-text pb-10">{{__('Invoice')}}</h4>
                                <p class="fs-15 fw-500 lh-20 text-title-text"> {{$transaction->tnxId}}</p>
                            </div>
                            <div class="item">
                                <p class="fs-14 fw-600 lh-24 text-para-text">{{__('Invoice To')}}:</p>
                                <p class="fs-14 fw-400 lh-24 text-para-text">{{$transaction->user?->name}}</p>
                                <p class="fs-14 fw-400 lh-24 mailto:text-para-text">{{$transaction->user?->email}}</p>
                            </div>
                            <div class="item">
                                <p class="fs-14 fw-600 lh-24 text-para-text">{{__('Pay to')}}:</p>
                                <p class="fs-14 fw-400 lh-24 text-para-text">{{ getOption('app_name') }}</p>
                                <p class="fs-14 fw-400 lh-24 text-para-text">{{ getOption('app_location') }}</p>
                                <p class="fs-14 fw-400 lh-24 text-para-text">{{ getOption('app_contact_number') }}</p>
                            </div>
                        </div>
                    </div>


                    <div class="pb-15">
                        <h4 class="fs-18 fw-600 lh-28 text-title-text pb-15">{{__('Invoice Items')}}</h4>
                        <div class="table-responsive pb-15">
                            <table class="table zTable zTable-last-item-right zTable-last-item-border">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="text-nowrap">{{__('Transaction ID')}}</div>
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
                                    <td>{{$transaction->tnxId}}</td>
                                    <td>{{$transaction->purpose}}</td>
                                    <td>{{showPrice($transaction->amount)}}</td>
                                    <td>{{1}}</td>
                                    <td>{{showPrice($transaction->amount)}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!--  -->
                    <div class="max-w-374 w-100 ms-auto mb-30 text-end">
                        <ul class="zList-pb-15">
                            <li>
                                <div class="row align-items-center">
                                    <div class="col-6">
                                        <p class="fs-14 fw-500 lh-17 text-para-text">{{__('Total')}}:</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="fs-14 fw-600 lh-17 text-main-color">{{showPrice($transaction->amount)}}</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    @if($transaction->amount > 0)
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
                        <tr>
                            <td>{{$transaction->payment->created_at}}</td>
                            <td>{{$transaction->payment->gateway != null?$transaction->payment->gateway->title:'N/A'}}</td>
                            <td>{{$transaction->payment->paymentId}}</td>
                            <td>{{showPrice($transaction->payment->grand_total)}}</td>
                        </tr>
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('admin/custom/js/print-invoice.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
</body>

</html>
