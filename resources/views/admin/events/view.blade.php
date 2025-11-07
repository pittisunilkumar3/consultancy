@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="p-sm-30 p-15">
        <!--  -->
        <div class="row rg-20">
            <div class="col-xll-3 col-lg-5">
                <div class="course-details-info bg-white p-16 bd-ra-5">
                    <div class="img">
                        <img src="{{getFileUrl($event->image)}}" alt="{{$event->title}}"/>
                    </div>
                    <div class="content">
                        <ul class="zList-pb-13">
                            <li class="d-flex justify-content-between align-items-start g-10">
                                <div class="d-flex align-items-center g-10 flex-shrink-0">
                                    <div class="d-flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                             viewBox="0 0 20 20" fill="none">
                                            <path
                                                d="M2.08337 5L6.66671 3.33334L11.25 5L9.16671 6.25V7.5C9.16671 7.5 8.61112 7.08334 6.66671 7.08334C4.72227 7.08334 4.16671 7.5 4.16671 7.5V6.25L2.08337 5ZM2.08337 5V8.33334"
                                                stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                            <path
                                                d="M9.16663 7.08334V7.82408C9.16663 9.25592 8.04733 10.4167 6.66663 10.4167C5.28592 10.4167 4.16663 9.25592 4.16663 7.82408V7.08334"
                                                stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                            <path
                                                d="M12.7652 9.19117C12.7652 9.19117 13.1692 8.89708 14.5833 8.89708C15.9975 8.89708 16.4015 9.19117 16.4015 9.19117M12.7652 9.19117V8.33333L11.25 7.5L14.5833 6.25L17.9167 7.5L16.4015 8.33333V9.19117M12.7652 9.19117V9.43183C12.7652 10.436 13.5792 11.25 14.5833 11.25C15.5875 11.25 16.4015 10.436 16.4015 9.43183V9.19117"
                                                stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                            <path
                                                d="M3.65417 13.2717C2.86818 13.7104 0.807383 14.6064 2.06255 15.7274C2.67568 16.275 3.35857 16.6667 4.21711 16.6667H9.11613C9.97471 16.6667 10.6575 16.275 11.2707 15.7274C12.5259 14.6064 10.465 13.7104 9.67904 13.2717C7.83597 12.2428 5.49728 12.2428 3.65417 13.2717Z"
                                                stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                            <path
                                                d="M13.3334 16.6667H16.4207C17.0646 16.6667 17.5768 16.3533 18.0366 15.9152C18.978 15.0184 17.4325 14.3017 16.843 13.9507C15.7802 13.3178 14.4977 13.1716 13.3334 13.5117"
                                                stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <p class="fs-15 fw-500 lh-28 text-para-text">{{__('Percipient')}} :</p>
                                </div>
                                <p class="fs-15 fw-500 lh-28 text-para-text text-end">{{count($event->percipients)}}</p>
                            </li>
                            <li class="d-flex justify-content-between align-items-start g-10">
                                <div class="d-flex align-items-center g-10 flex-shrink-0">
                                    <div class="d-flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                             viewBox="0 0 20 20" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                  d="M17.7083 10C17.7083 14.2575 14.2575 17.7083 9.99996 17.7083C5.74246 17.7083 2.29163 14.2575 2.29163 10C2.29163 5.7425 5.74246 2.29167 9.99996 2.29167C14.2575 2.29167 17.7083 5.7425 17.7083 10Z"
                                                  stroke="#636370" stroke-width="1.5" stroke-linecap="square"/>
                                            <path d="M12.8601 12.4518L9.71838 10.5777V6.53849" stroke="#636370"
                                                  stroke-width="1.5" stroke-linecap="square"/>
                                        </svg>
                                    </div>
                                    <p class="fs-15 fw-500 lh-28 text-para-text">{{__('Event Type')}} :</p>
                                </div>
                                <p class="fs-15 fw-500 lh-28 text-para-text text-end">{{  $event->type == EVENT_TYPE_PHYSICAL ? __('Physical'): __('Virtual')}}</p>
                            </li>
                            <li class="d-flex justify-content-between align-items-start g-10">
                                <div class="d-flex align-items-center g-10 flex-shrink-0">
                                    <div class="d-flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="24"
                                             viewBox="0 0 23 24" fill="none">
                                            <path
                                                d="M7.1875 5.23926H15.3333C16.1272 5.23926 16.7708 5.88285 16.7708 6.67676V8.11426"
                                                stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                            <path d="M14.375 12.9059H8.625" stroke="#636370" stroke-width="1.5"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M11.5 16.7393H8.625" stroke="#636370" stroke-width="1.5"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                            <path
                                                d="M17.7263 2.41667L6.04481 2.41669C5.56856 2.41669 5.08144 2.48657 4.69646 2.76916C3.47582 3.66517 2.53513 5.66351 4.43478 7.46766C4.96816 7.97423 5.71371 8.15787 6.44659 8.15787H17.5216C18.2821 8.15787 19.6458 8.26671 19.6458 10.5887V17.732C19.6458 19.859 17.9307 21.5833 15.815 21.5833H7.16107C5.04928 21.5833 3.51339 20.0908 3.39207 17.8183L3.35997 5.45111"
                                                stroke="#636370" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                    <p class="fs-15 fw-500 lh-28 text-para-text">{{__('Location')}} :</p>
                                </div>
                                <p class="fs-15 fw-500 lh-28 text-para-text text-end">{{$event->location}}</p>
                            </li>
                            <li class="d-flex justify-content-between align-items-start g-10">
                                <div class="d-flex align-items-center g-10 flex-shrink-0">
                                    <div class="d-flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="24"
                                             viewBox="0 0 23 24" fill="none">
                                            <path
                                                d="M7.1875 5.23926H15.3333C16.1272 5.23926 16.7708 5.88285 16.7708 6.67676V8.11426"
                                                stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                            <path d="M14.375 12.9059H8.625" stroke="#636370" stroke-width="1.5"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M11.5 16.7393H8.625" stroke="#636370" stroke-width="1.5"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                            <path
                                                d="M17.7263 2.41667L6.04481 2.41669C5.56856 2.41669 5.08144 2.48657 4.69646 2.76916C3.47582 3.66517 2.53513 5.66351 4.43478 7.46766C4.96816 7.97423 5.71371 8.15787 6.44659 8.15787H17.5216C18.2821 8.15787 19.6458 8.26671 19.6458 10.5887V17.732C19.6458 19.859 17.9307 21.5833 15.815 21.5833H7.16107C5.04928 21.5833 3.51339 20.0908 3.39207 17.8183L3.35997 5.45111"
                                                stroke="#636370" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                    <p class="fs-15 fw-500 lh-28 text-para-text">{{__('Date & Time')}} :</p>
                                </div>
                                <p class="fs-15 fw-500 lh-28 text-para-text text-end">{{$event->date_time}}</p>
                            </li>
                            <li class="d-flex justify-content-between align-items-start g-10">
                                <div class="d-flex align-items-center g-10 flex-shrink-0">
                                    <div class="d-flex">
                                        <!-- SVG Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="24"
                                             viewBox="0 0 23 24" fill="none">
                                            <path
                                                d="M7.1875 5.23926H15.3333C16.1272 5.23926 16.7708 5.88285 16.7708 6.67676V8.11426"
                                                stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                            <path d="M14.375 12.9059H8.625" stroke="#636370" stroke-width="1.5"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M11.5 16.7393H8.625" stroke="#636370" stroke-width="1.5"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                            <path
                                                d="M17.7263 2.41667L6.04481 2.41669C5.56856 2.41669 5.08144 2.48657 4.69646 2.76916C3.47582 3.66517 2.53513 5.66351 4.43478 7.46766C4.96816 7.97423 5.71371 8.15787 6.44659 8.15787H17.5216C18.2821 8.15787 19.6458 8.26671 19.6458 10.5887V17.732C19.6458 19.859 17.9307 21.5833 15.815 21.5833H7.16107C5.04928 21.5833 3.51339 20.0908 3.39207 17.8183L3.35997 5.45111"
                                                stroke="#636370" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                    <p class="fs-15 fw-500 lh-28 text-para-text">{{ __('Country') }} :</p>
                                </div>
                                <p class="fs-15 fw-500 lh-28 text-para-text text-end">{{ $countries }}</p>
                            </li>

                            <li class="d-flex justify-content-between align-items-start g-10">
                                <div class="d-flex align-items-center g-10 flex-shrink-0">
                                    <div class="d-flex">
                                        <!-- SVG Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="24"
                                             viewBox="0 0 23 24" fill="none">
                                            <path
                                                d="M7.1875 5.23926H15.3333C16.1272 5.23926 16.7708 5.88285 16.7708 6.67676V8.11426"
                                                stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                            <path d="M14.375 12.9059H8.625" stroke="#636370" stroke-width="1.5"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M11.5 16.7393H8.625" stroke="#636370" stroke-width="1.5"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                            <path
                                                d="M17.7263 2.41667L6.04481 2.41669C5.56856 2.41669 5.08144 2.48657 4.69646 2.76916C3.47582 3.66517 2.53513 5.66351 4.43478 7.46766C4.96816 7.97423 5.71371 8.15787 6.44659 8.15787H17.5216C18.2821 8.15787 19.6458 8.26671 19.6458 10.5887V17.732C19.6458 19.859 17.9307 21.5833 15.815 21.5833H7.16107C5.04928 21.5833 3.51339 20.0908 3.39207 17.8183L3.35997 5.45111"
                                                stroke="#636370" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                    <p class="fs-15 fw-500 lh-28 text-para-text">{{ __('Study Levels') }} :</p>
                                </div>
                                <p class="fs-15 fw-500 lh-28 text-para-text text-end">{{ $studyLevels }}</p>
                            </li>

                            <li class="d-flex justify-content-between align-items-start g-10">
                                <div class="d-flex align-items-center g-10 flex-shrink-0">
                                    <div class="d-flex">
                                        <!-- SVG Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="24"
                                             viewBox="0 0 23 24" fill="none">
                                            <path
                                                d="M7.1875 5.23926H15.3333C16.1272 5.23926 16.7708 5.88285 16.7708 6.67676V8.11426"
                                                stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                            <path d="M14.375 12.9059H8.625" stroke="#636370" stroke-width="1.5"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M11.5 16.7393H8.625" stroke="#636370" stroke-width="1.5"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                            <path
                                                d="M17.7263 2.41667L6.04481 2.41669C5.56856 2.41669 5.08144 2.48657 4.69646 2.76916C3.47582 3.66517 2.53513 5.66351 4.43478 7.46766C4.96816 7.97423 5.71371 8.15787 6.44659 8.15787H17.5216C18.2821 8.15787 19.6458 8.26671 19.6458 10.5887V17.732C19.6458 19.859 17.9307 21.5833 15.815 21.5833H7.16107C5.04928 21.5833 3.51339 20.0908 3.39207 17.8183L3.35997 5.45111"
                                                stroke="#636370" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                    <p class="fs-15 fw-500 lh-28 text-para-text">{{ __('Universities') }} :</p>
                                </div>
                                <p class="fs-15 fw-500 lh-28 text-para-text text-end">{{ $universities }}</p>
                            </li>

                            <li class="d-flex justify-content-between align-items-start g-10">
                                <div class="d-flex align-items-center g-10 flex-shrink-0">
                                    <div class="d-flex">
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
                                    <p class="fs-15 fw-500 lh-28 text-para-text">{{__('Price')}} :</p>
                                </div>
                                <p class="fs-15 fw-500 lh-28 text-para-text text-end">{{showPrice($event->price)}}</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xll-9 col-lg-7">
                <div class="event-details-content">
                    <h4 class="title">{{__('Participants')}}</h4>
                    <table class="table zTable zTable-last-item-right common-datatable">
                        <thead>
                        <tr>
                            <th>
                                <div>{{__('#SL')}}</div>
                            </th>
                            <th>
                                <div>{{__('Image')}}</div>
                            </th>
                            <th>
                                <div>{{__('Name')}}</div>
                            </th>
                            <th>
                                <div>{{__('Email')}}</div>
                            </th>
                            <th>
                                <div>{{__('Phone')}}</div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($event->percipients as $percipient)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>
                                    <div class="min-w-160 d-flex align-items-center cg-10">
                                        <div
                                            class="flex-shrink-0 w-41 h-41 bd-one bd-c-stroke rounded-circle overflow-hidden bg-eaeaea d-flex justify-content-center align-items-center">
                                            <img src="{{getFileUrl($percipient->image)}}" alt="Image"
                                                 class="rounded avatar-xs w-100 h-100 object-fit-cover">
                                        </div>
                                    </div>
                                </td>
                                <td>{{$percipient->name}}</td>
                                <td>{{$percipient->email}}</td>
                                <td>{{$percipient->mobile}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
