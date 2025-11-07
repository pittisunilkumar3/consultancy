@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="p-sm-30 p-15">
        <!--  -->
        <div class="row rg-20">
            <div class="col-xll-2 col-lg-5 col-md-6">
                <div class="bg-white p-sm-25 p-15 bd-ra-5">
                    <ul class="zList-pb-5">
                        <div class="pb-20">
                            <div class="upload-img-box profileImage-upload">
                                <img src="{{ getFileUrl($serviceData->icon) }}">
                            </div>
                        </div>
                        <li class="d-flex align-items-center g-8">
                            <div class="d-flex">
                                <svg width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.1875 4.73926H15.3333C16.1272 4.73926 16.7708 5.38285 16.7708 6.17676V7.61426" stroke="#636370" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M14.375 12.406H8.625" stroke="#636370" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M11.5 16.2393H8.625" stroke="#636370" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M17.7263 1.91675L6.04481 1.91677C5.56856 1.91677 5.08144 1.98665 4.69646 2.26924C3.47582 3.16526 2.53513 5.16359 4.43478 6.96774C4.96816 7.47431 5.71371 7.65795 6.44659 7.65795H17.5216C18.2821 7.65795 19.6458 7.76679 19.6458 10.0887V17.2321C19.6458 19.3591 17.9307 21.0834 15.815 21.0834H7.16107C5.04928 21.0834 3.51339 19.5909 3.39207 17.3184L3.35997 4.9512" stroke="#636370" stroke-width="1.5" stroke-linecap="round"></path>
                                </svg>
                            </div>
                            <p class="fs-14 fw-600 lh-28 text-para-text">
                                <span class="text-title-text fw-600">{{__('Service Title')}} :</span>
                            </p>
                            <p class="fs-15 fw-500 lh-28 text-para-text">{{ $serviceData->title }}</p>
                        </li>
                        <li class="d-flex align-items-center g-8">
                            <div class="d-flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M18.3337 10.0001C18.3337 14.6024 14.6027 18.3334 10.0003 18.3334C5.39795 18.3334 1.66699 14.6024 1.66699 10.0001C1.66699 5.39771 5.39795 1.66675 10.0003 1.66675C14.6027 1.66675 18.3337 5.39771 18.3337 10.0001Z" stroke="#636370" stroke-width="1.5"></path>
                                    <path d="M12.2588 8.38433C12.1762 7.74878 11.4465 6.72193 10.1343 6.7219C8.60966 6.72188 7.96812 7.5663 7.83795 7.9885C7.63486 8.55325 7.67548 9.71433 9.46257 9.84091C11.6965 9.99925 12.5914 10.2629 12.4776 11.6301C12.3637 12.9972 11.1184 13.2926 10.1343 13.2608C9.15016 13.2292 7.54002 12.7772 7.47754 11.5612M9.97816 5.83179V6.72493M9.97816 13.2527V14.1651" stroke="#636370" stroke-width="1.5" stroke-linecap="round"></path>
                                </svg>
                            </div>
                            <p class="fs-14 fw-600 lh-28 text-para-text">
                                <span class="text-title-text fw-600">{{__("Price")}} :</span>
                            </p>
                            <p class="fs-15 fw-500 lh-28 text-para-text">{{ showPrice($serviceData->price) }}</p>
                        </li>
                        <li class="d-flex align-items-center g-8">
                            <div class="d-flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                    <path d="M11.1648 6.69063C10.8151 6.69063 10.5059 6.91602 10.398 7.24882C9.81486 9.05022 8.12401 10.3572 6.13078 10.3572C3.65624 10.3572 1.64295 8.34373 1.64295 5.86899C1.64295 3.88395 2.93915 2.19853 4.7295 1.60876C5.07519 1.49486 5.3094 1.17164 5.3094 0.807259C5.3094 0.549157 5.1859 0.306521 4.97734 0.154745C4.76878 0.00273658 4.50005 -0.0401609 4.25463 0.0388607C1.78934 0.832812 0.000183105 3.14349 0.000183105 5.86907C0.000183105 9.24977 2.75026 12 6.13076 12C8.84544 12 11.1483 10.2249 11.9509 7.77528C12.0334 7.52338 11.9902 7.24726 11.8345 7.03254C11.6793 6.81795 11.4303 6.69063 11.1648 6.69063Z" fill="#636370"></path>
                                    <path d="M7.50942 1.60183C8.87218 2.043 9.95057 3.119 10.395 4.48035C10.5057 4.81924 10.8213 5.04808 11.1779 5.04808H11.1948C11.4522 5.04808 11.6941 4.92498 11.8457 4.71701C11.9969 4.50907 12.0402 4.24094 11.9612 3.99592C11.3601 2.1266 9.88458 0.648084 8.01682 0.0425191C7.76934 -0.037663 7.49784 0.00542433 7.28731 0.158403C7.07675 0.311594 6.95203 0.556276 6.95203 0.816699V0.834571C6.95194 1.18391 7.1773 1.49413 7.50942 1.60183Z" fill="#636370"></path>
                                </svg>
                            </div>
                            @if($serviceData->status == STATUS_ACTIVE)
                                <p class="fs-14 fw-600 lh-28 text-para-text">{{__('Status')}} : {{__('Active')}}</p>
                            @elseif($serviceData->status == STATUS_DEACTIVATE)
                                <p class="fs-14 fw-600 lh-28 text-para-text">{{__('Status')}} : {{__('Inactive')}}</p>
                            @endif
                        </li>
                        <li class="d-flex align-items-center g-8">
                            <div class="d-flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M15.8333 3.33325H4.16667C3.24619 3.33325 2.5 4.07944 2.5 4.99992V16.6666C2.5 17.5871 3.24619 18.3333 4.16667 18.3333H15.8333C16.7538 18.3333 17.5 17.5871 17.5 16.6666V4.99992C17.5 4.07944 16.7538 3.33325 15.8333 3.33325Z" stroke="#636370" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M13.3335 1.66675V5.00008" stroke="#636370" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M6.6665 1.66675V5.00008" stroke="#636370" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M2.5 8.33325H17.5" stroke="#636370" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <p class="fs-14 fw-600 lh-28 text-para-text">
                                <span class="text-title-text fw-600">{{__('Created At')}} :</span>
                            </p>
                            <p class="fs-15 fw-500 lh-28 text-para-text">{{ \Carbon\Carbon::parse($serviceData->created_at)->format('Y-m-d , h:i:s') }}</p>
                        </li>
                        @if(count($serviceData->feature))
                            @foreach($serviceData->feature as $feature)
                                <li class="d-flex justify-content-between align-items-center g-10">
                                    <div class="d-flex align-items-start g-10">
                                        <div class="d-flex pt-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                 viewBox="0 0 20 20" fill="none">
                                                <path
                                                    d="M1.66663 1.66667H13.3333C14.9046 1.66667 15.6903 1.66667 16.1785 2.15483C16.6666 2.64298 16.6666 3.42866 16.6666 5.00001V10C16.6666 11.5713 16.6666 12.357 16.1785 12.8452C15.6903 13.3333 14.9046 13.3333 13.3333 13.3333H7.49996"
                                                    stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"/>
                                                <path d="M8.33337 5.41667H13.3334" stroke="#636370" stroke-width="1.5"
                                                      stroke-linecap="round" stroke-linejoin="round"/>
                                                <path
                                                    d="M1.66663 14.1667V10.8333C1.66663 10.0477 1.66663 9.65484 1.9107 9.41076C2.15478 9.16667 2.54762 9.16667 3.33329 9.16667H4.99996M1.66663 14.1667H4.99996M1.66663 14.1667V18.3333M4.99996 9.16667V14.1667M4.99996 9.16667H7.49996H9.99996M4.99996 14.1667V18.3333"
                                                    stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"/>
                                                <path
                                                    d="M4.99996 5.41667C4.99996 6.33714 4.25377 7.08333 3.33329 7.08333C2.41282 7.08333 1.66663 6.33714 1.66663 5.41667C1.66663 4.49619 2.41282 3.75 3.33329 3.75C4.25377 3.75 4.99996 4.49619 4.99996 5.41667Z"
                                                    stroke="#636370" stroke-width="1.5"/>
                                            </svg>
                                        </div>
                                        <div class="flex-grow-1 d-flex align-items-start g-10 flex-wrap">
                                            <p class="fs-15 fw-500 lh-28 text-para-text">
                                                <span class="text-title-text fw-600">{{$feature['name']}} :
                                                </span>
                                            </p>
                                            <p class="fs-15 fw-500 lh-28 text-para-text">{{$feature['value']}}</p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-xll-10 col-lg-7 col-md-6">
                <div class="event-details-content">
                    <div class="img"><img class="w-100" src="{{ getFileUrl($serviceData->image) }}" alt="" /></div>
                    <div>
                        {!! $serviceData->description !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
