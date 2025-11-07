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
                @forelse($notifications as $notification)
                    <li class="item">
                        <div class="content">
                            <div class="d-flex align-items-start cg-6">
                                @if($notification->view_status == 1)
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
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30"
                                         fill="none">
                                        <rect width="30" height="30" rx="15" fill="#F2EDFF"/>
                                        <path
                                            d="M10.4191 10.8127C10.6544 8.4591 12.635 6.66675 15.0003 6.66675C17.3657 6.66675 19.3462 8.4591 19.5816 10.8127L19.8204 13.2012C19.9386 14.3824 20.3447 15.5166 21.0031 16.5043L21.7208 17.5807C22.2195 18.3288 22.4689 18.7029 22.4651 19.0124C22.4611 19.338 22.2987 19.6413 22.03 19.8252C21.7746 20.0001 21.3251 20.0001 20.426 20.0001H9.57468C8.67556 20.0001 8.22601 20.0001 7.97061 19.8252C7.7019 19.6413 7.53959 19.338 7.53559 19.0124C7.53178 18.7029 7.78115 18.3288 8.27989 17.5807L8.9975 16.5043C9.65599 15.5166 10.0621 14.3824 10.1802 13.2012L10.4191 10.8127Z"
                                            fill="#5525C9"/>
                                        <path
                                            d="M13.3904 22.1912C13.4854 22.2798 13.6946 22.3581 13.9857 22.4139C14.2768 22.4697 14.6334 22.5 15.0003 22.5C15.3672 22.5 15.7239 22.4697 16.0149 22.4139C16.306 22.3581 16.5152 22.2798 16.6102 22.1912"
                                            stroke="#5525C9" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                @endif

                                <div class="flex-grow-1">
                                    <h4 class="fs-14 fw-500 fw-700 lh-17 pb-15 text-para-text">{{$notification->title}}</h4>
                                    <p class="fs-12 fw-500 lh-17 text-para-text pb-15">
                                        {{$notification->body}}
                                        @if($notification->link)
                                            <a href="{{$notification->link}}">{{__('View Link')}}</a>
                                        @endif
                                    </p>
                                    @if($notification->view_status != 1)
                                        <a href="{{route(getPrefix().'.notification.notification-mark-as-read', $notification->id)}}">{{__('Mark as Read')}}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <p class="info">{{$notification->created_at->format('d/m/Y h:i A')}}</p>
                    </li>
                @empty
                    <div class="content d-flex justify-content-center pt-20">
                        <div class="d-flex align-items-start cg-6">
                            <p class="fs-12 fw-500 lh-17 text-para-text pb-15">
                                {{__('No notification found')}}
                            </p>
                        </div>
                    </div>
                @endforelse
            </ul>
        </div>
        <!--  -->
        {{ $notifications->links('layouts.partial.common_pagination_with_count') }}
    </div>
@endsection
