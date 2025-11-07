<div class="zSidebar">
    <div class="zSidebar-overlay"></div>
    <!--  -->
    <div class="zSidebar-wrap h-100">
        <!-- Logo -->
        <a href="{{ route('admin.dashboard') }}" class="zSidebar-logo">
            <img class="" src="{{ getSettingImage('app_logo') }}" alt=""/>
        </a>
        <!-- Menu & Logout -->
        <div class="zSidebar-fixed">
            <ul class="zSidebar-menu" id="sidebarMenu">
                <li>
                    <a href="{{route('consultant.dashboard')}}"
                       class="d-flex align-items-center cg-10 {{$activeDashboard ?? ''}}">
                        <div class="d-flex">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.5 13.5H10.5" stroke="white" stroke-width="1.125" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M1.76354 9.91013C1.49878 8.18715 1.3664 7.32572 1.69213 6.56204C2.01785 5.79834 2.74052 5.27582 4.18586 4.23079L5.26574 3.45C7.06372 2.15 7.96267 1.5 9 1.5C10.0373 1.5 10.9363 2.15 12.7343 3.45L13.8142 4.23079C15.2595 5.27582 15.9821 5.79834 16.3078 6.56204C16.6336 7.32572 16.5012 8.18715 16.2364 9.91013L16.0107 11.3793C15.6353 13.8217 15.4477 15.0429 14.5718 15.7715C13.6958 16.5 12.4153 16.5 9.8541 16.5H8.1459C5.58475 16.5 4.30418 16.5 3.42825 15.7715C2.55232 15.0429 2.36465 13.8217 1.98932 11.3793L1.76354 9.91013Z" stroke="white" stroke-width="1.125" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span>{{__('Dashboard')}}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('consultant.consultant_profile') }}"
                       class="d-flex align-items-center cg-10 {{ @$activeConsultantProfile }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.0001 18.3334C14.6025 18.3334 18.3334 14.6024 18.3334 10C18.3334 5.39765 14.6025 1.66669 10.0001 1.66669C5.39771 1.66669 1.66675 5.39765 1.66675 10C1.66675 14.6024 5.39771 18.3334 10.0001 18.3334Z" stroke="white" stroke-width="1.25"/>
                                <path d="M6.25 14.1666C8.19308 12.1315 11.786 12.0356 13.75 14.1666M12.0792 7.91665C12.0792 9.06723 11.1452 9.99998 9.99292 9.99998C8.84075 9.99998 7.90664 9.06723 7.90664 7.91665C7.90664 6.76605 8.84075 5.83331 9.99292 5.83331C11.1452 5.83331 12.0792 6.76605 12.0792 7.91665Z" stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <span class="">{{ __('Consultant Profile') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('consultant.consultations.appointments.index') }}"
                       class="d-flex align-items-center cg-10 {{ @$activeAppointment }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.1666 1.66669V4.16669M5.83325 1.66669V4.16669" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10.8333 2.91669H9.16667C6.02397 2.91669 4.45262 2.91669 3.47631 3.893C2.5 4.86931 2.5 6.44065 2.5 9.58335V11.6667C2.5 14.8094 2.5 16.3808 3.47631 17.357C4.45262 18.3334 6.02397 18.3334 9.16667 18.3334H10.8333C13.976 18.3334 15.5474 18.3334 16.5237 17.357C17.5 16.3808 17.5 14.8094 17.5 11.6667V9.58335C17.5 6.44065 17.5 4.86931 16.5237 3.893C15.5474 2.91669 13.976 2.91669 10.8333 2.91669Z" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2.91675 7.08331H17.0834" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7.5 12.9167C7.5 12.9167 8.75 13.3334 9.16667 14.5834C9.16667 14.5834 10.9804 11.25 13.3333 10.4167" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="">{{ __('Appointments') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('consultant.student.list') }}"
                       class="d-flex align-items-center cg-10 {{ @$activeConsulterStudent }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.8334 4.16669L10.0001 1.66669L4.16675 4.16669L7.08341 5.41669V7.08335C7.08341 7.08335 8.05564 6.66669 10.0001 6.66669C11.9445 6.66669 12.9167 7.08335 12.9167 7.08335V5.41669L15.8334 4.16669ZM15.8334 4.16669V7.50002" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12.9166 7.08331V7.91665C12.9166 9.52748 11.6108 10.8333 9.99992 10.8333C8.38909 10.8333 7.08325 9.52748 7.08325 7.91665V7.08331" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6.48555 13.9194C5.56857 14.4899 3.1643 15.6545 4.62866 17.1119C5.34399 17.8238 6.14068 18.3329 7.14232 18.3329H12.8578C13.8595 18.3329 14.6562 17.8238 15.3715 17.1119C16.8358 15.6545 14.4316 14.4899 13.5146 13.9194C11.3643 12.5819 8.63583 12.5819 6.48555 13.9194Z" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="">{{ __('Students') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('consultant.consultations.review.list') }}"
                       class="d-flex align-items-center cg-10 {{ @$activeConsultationReview }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.4397 2.87017L12.9062 5.82742C13.1062 6.23908 13.6395 6.63393 14.0895 6.70954L16.7475 7.15481C18.4473 7.44046 18.8473 8.68385 17.6224 9.91043L15.556 11.9939C15.206 12.3468 15.0144 13.0273 15.1227 13.5146L15.7143 16.0938C16.1809 18.1353 15.106 18.925 13.3146 17.858L10.8232 16.371C10.3732 16.1022 9.63166 16.1022 9.17333 16.371L6.68197 17.858C4.89883 18.925 3.81562 18.1269 4.28224 16.0938L4.87384 13.5146C4.98216 13.0273 4.79051 12.3468 4.44055 11.9939L2.37411 9.91043C1.15758 8.68385 1.54921 7.44046 3.24901 7.15481L5.90706 6.70954C6.34867 6.63393 6.88195 6.23908 7.08192 5.82742L8.54841 2.87017C9.34833 1.26553 10.6482 1.26553 11.4397 2.87017Z" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="">{{ __('Reviews') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('consultant.notification.all') }}"
                       class="d-flex align-items-center cg-10 {{ @$activeNotification }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M4.29864 9.57579C4.23741 10.7391 4.3078 11.9775 3.26844 12.757C2.7847 13.1198 2.5 13.6891 2.5 14.2939C2.5 15.1256 3.1515 15.8333 4 15.8333H16C16.8485 15.8333 17.5 15.1256 17.5 14.2939C17.5 13.6891 17.2153 13.1198 16.7316 12.757C15.6922 11.9775 15.7626 10.7391 15.7013 9.57579C15.5418 6.54348 13.0365 4.16663 10 4.16663C6.96347 4.16663 4.45823 6.54348 4.29864 9.57579Z"
                                    stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                <path
                                    d="M8.75 2.60413C8.75 3.29448 9.30967 4.16663 10 4.16663C10.6903 4.16663 11.25 3.29448 11.25 2.60413C11.25 1.91377 10.6903 1.66663 10 1.66663C9.30967 1.66663 8.75 1.91377 8.75 2.60413Z"
                                    stroke="white" stroke-width="1.25"/>
                                <path
                                    d="M12.5 15.8334C12.5 17.2141 11.3807 18.3334 10 18.3334C8.61925 18.3334 7.5 17.2141 7.5 15.8334"
                                    stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="">{{ __('Notifications') }}</span>
                    </a>
                </li>
            </ul>
            <a href="{{ route('logout') }}" class="d-inline-flex align-items-center cg-15 pt-17 pb-30 px-25">
                <img src="{{asset('assets/images/icon/logout.svg')}}" alt=""/>
                <p class="fs-15 fw-600 lh-20 text-white">{{__('Logout')}}</p>
            </a>
        </div>
    </div>
</div>
