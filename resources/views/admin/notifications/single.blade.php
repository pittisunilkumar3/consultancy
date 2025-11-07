@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush

@section('content')
    <div class="p-sm-30 p-15">
        <div class="p-md-30 p-15 bg-white bd-ra-5">
            <div class="notifyList-head">
                <p class="fs-14 fw-500 lh-19 text-title-text">{{__('Notifications')}}</p>
                <p class="fs-14 fw-500 lh-19 text-title-text">{{__('Date & Time')}}</p>
            </div>
            <ul class="notifyList-body">
                <li class="item">
                    <div class="content">
                        <div class="d-flex align-items-start cg-6">
                            <div
                                class="flex-shrink-0 w-32 h-32 rounded-circle d-flex justify-content-center align-items-center bg-warning-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                     viewBox="0 0 22 22"
                                     fill="none">
                                    <path
                                        d="M5.96077 6.39393C6.21967 3.80494 8.39825 1.83334 11.0002 1.83334C13.6021 1.83334 15.7807 3.80494 16.0396 6.39393L16.3023 9.02123C16.4322 10.3206 16.8789 11.5682 17.6033 12.6547L18.3926 13.8387C19.0339 14.8007 19.3546 15.2816 19.2821 15.6793C19.2446 15.885 19.1436 16.0738 18.9932 16.2191C18.7025 16.5 18.1245 16.5 16.9684 16.5H5.03196C3.87585 16.5 3.29779 16.5 3.00712 16.2191C2.85675 16.0738 2.75571 15.885 2.71822 15.6793C2.64574 15.2816 2.96639 14.8007 3.60768 13.8387L4.39706 12.6547C5.12139 11.5682 5.56811 10.3206 5.69804 9.02123L5.96077 6.39393Z"
                                        fill="#F5C000"
                                    />
                                    <path
                                        d="M9.2293 18.9103C9.33375 19.0077 9.56392 19.0939 9.8841 19.1553C10.2043 19.2167 10.5966 19.25 11.0002 19.25C11.4037 19.25 11.796 19.2167 12.1162 19.1553C12.4364 19.0939 12.6666 19.0077 12.771 18.9103"
                                        stroke="#F5C000" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>

                            <div class="flex-grow-1">
                                <h4 class="fs-14 fw-500 fw-700 lh-17 pb-15 text-para-text">{{$notification->title}}</h4>
                                <p class="fs-12 fw-500 lh-17 text-para-text pb-15">
                                    {{$notification->body}}
                                    @if($notification->link)
                                        <a href="{{$notification->link}}">{{__('View Link')}}</a>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="info">{{$notification->created_at->format('d/m/Y h:i A')}}</p>
                </li>
            </ul>
        </div>
    </div>
@endsection
