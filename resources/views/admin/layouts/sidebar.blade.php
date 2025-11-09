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
                    <a href="{{route('admin.dashboard')}}"
                       class="d-flex align-items-center cg-10 {{$activeDashboard ?? ''}}">
                        <div class="d-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                 fill="none">
                                <path
                                    d="M12 3.75C12 3.05109 12 2.70164 12.1142 2.42597C12.2664 2.05844 12.5585 1.76642 12.926 1.61418C13.2017 1.5 13.5511 1.5 14.25 1.5C14.9489 1.5 15.2983 1.5 15.574 1.61418C15.9415 1.76642 16.2336 2.05844 16.3858 2.42597C16.5 2.70164 16.5 3.05109 16.5 3.75V6.75C16.5 7.44891 16.5 7.79835 16.3858 8.07405C16.2336 8.44155 15.9415 8.7336 15.574 8.88585C15.2983 9 14.9489 9 14.25 9C13.5511 9 13.2017 9 12.926 8.88585C12.5585 8.7336 12.2664 8.44155 12.1142 8.07405C12 7.79835 12 7.44891 12 6.75V3.75Z"
                                    stroke="#FFFFFF" stroke-width="1.5"/>
                                <path
                                    d="M12 14.25C12 13.5511 12 13.2017 12.1142 12.926C12.2664 12.5585 12.5585 12.2664 12.926 12.1142C13.2017 12 13.5511 12 14.25 12C14.9489 12 15.2983 12 15.574 12.1142C15.9415 12.2664 16.2336 12.5585 16.3858 12.926C16.5 13.2017 16.5 13.5511 16.5 14.25C16.5 14.9489 16.5 15.2983 16.3858 15.574C16.2336 15.9415 15.9415 16.2336 15.574 16.3858C15.2983 16.5 14.9489 16.5 14.25 16.5C13.5511 16.5 13.2017 16.5 12.926 16.3858C12.5585 16.2336 12.2664 15.9415 12.1142 15.574C12 15.2983 12 14.9489 12 14.25Z"
                                    stroke="#FFFFFF" stroke-width="1.5"/>
                                <path
                                    d="M1.5 12C1.5 10.5858 1.5 9.8787 1.93934 9.43935C2.37868 9 3.08579 9 4.5 9H6C7.41421 9 8.1213 9 8.56065 9.43935C9 9.8787 9 10.5858 9 12V13.5C9 14.9142 9 15.6213 8.56065 16.0606C8.1213 16.5 7.41421 16.5 6 16.5H4.5C3.08579 16.5 2.37868 16.5 1.93934 16.0606C1.5 15.6213 1.5 14.9142 1.5 13.5V12Z"
                                    stroke="#FFFFFF" stroke-width="1.5"/>
                                <path
                                    d="M1.5 3.75C1.5 3.05109 1.5 2.70164 1.61418 2.42597C1.76642 2.05844 2.05844 1.76642 2.42597 1.61418C2.70164 1.5 3.05109 1.5 3.75 1.5H6.75C7.44891 1.5 7.79835 1.5 8.07405 1.61418C8.44155 1.76642 8.7336 2.05844 8.88585 2.42597C9 2.70164 9 3.05109 9 3.75C9 4.44891 9 4.79836 8.88585 5.07403C8.7336 5.44156 8.44155 5.73358 8.07405 5.88582C7.79835 6 7.44891 6 6.75 6H3.75C3.05109 6 2.70164 6 2.42597 5.88582C2.05844 5.73358 1.76642 5.44156 1.61418 5.07403C1.5 4.79836 1.5 4.44891 1.5 3.75Z"
                                    stroke="#FFFFFF" stroke-width="1.5"/>
                            </svg>
                        </div>
                        <span>{{__('Dashboard')}}</span>
                    </a>
                </li>
                @can('Manage Service')
                    <li>
                        <a href="{{route('admin.services.index')}}"
                           class="d-flex align-items-center cg-10 {{ @$activeService }}">
                            <div class="d-flex">
                                <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.74984 5.58325L7.6665 8.91659H10.9998L8.9165 12.2499" stroke="white"
                                          stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path
                                        d="M16.8425 10.6992C17.2774 10.5796 17.4949 10.5197 17.5807 10.4053C17.6667 10.2909 17.6667 10.1068 17.6667 9.73875V8.09467C17.6667 7.72658 17.6667 7.5425 17.5807 7.42808C17.4948 7.31367 17.2774 7.25383 16.8425 7.13416C15.2172 6.68692 14.1999 4.95318 14.6194 3.30567C14.7347 2.85259 14.7924 2.62605 14.7373 2.49318C14.6822 2.36032 14.5243 2.26875 14.2081 2.08562L12.7708 1.25302C12.4607 1.07333 12.3056 0.983482 12.1664 1.00262C12.0272 1.02175 11.8702 1.1816 11.556 1.50131C10.34 2.73907 8.328 2.73902 7.11195 1.50122C6.79786 1.18152 6.64082 1.02167 6.50161 1.00253C6.36241 0.983399 6.20731 1.07325 5.89711 1.25294L4.45987 2.08555C4.14378 2.26866 3.98572 2.36022 3.93065 2.49307C3.87557 2.62592 3.93322 2.85248 4.04854 3.30562C4.46781 4.95317 3.44977 6.68696 1.82418 7.13417C1.38927 7.25383 1.17181 7.31367 1.0859 7.42808C1 7.5425 1 7.7265 1 8.09467V9.73875C1 10.1068 1 10.2909 1.0859 10.4053C1.17179 10.5197 1.38926 10.5796 1.82418 10.6992C3.4495 11.1465 4.46673 12.8802 4.04727 14.5277C3.93191 14.9808 3.87422 15.2073 3.9293 15.3402C3.98438 15.4731 4.14243 15.5647 4.45854 15.7477L5.89579 16.5804C6.20601 16.7601 6.36112 16.8499 6.50033 16.8308C6.63955 16.8117 6.79657 16.6517 7.11059 16.3321C8.32725 15.0932 10.3407 15.0932 11.5574 16.332C11.8714 16.6517 12.0284 16.8116 12.1677 16.8307C12.3068 16.8498 12.462 16.76 12.7722 16.5803L14.2094 15.7477C14.5256 15.5646 14.6837 15.473 14.7387 15.3401C14.7938 15.2072 14.7361 14.9807 14.6207 14.5277C14.201 12.8802 15.2174 11.1465 16.8425 10.6992Z"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <span class="">{{ __('Manage Services') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('admin.service_orders')}}"
                           class="d-flex align-items-center cg-10 {{ @$showServiceOrder }}">
                            <div class="d-flex">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13.7499 16.5476V17.5M13.7499 16.5476C12.9064 16.5476 12.1633 16.1268 11.7274 15.4877M13.7499 16.5476C14.5934 16.5476 15.3365 16.1268 15.7724 15.4877M11.7274 15.4877L10.8336 16.0715M11.7274 15.4877C11.4697 15.1097 11.3193 14.6554 11.3193 14.1667C11.3193 13.678 11.4696 13.2238 11.7273 12.8459M15.7724 15.4877L16.6663 16.0715M15.7724 15.4877C16.0302 15.1097 16.1805 14.6554 16.1805 14.1667C16.1805 13.678 16.0303 13.2238 15.7725 12.8459M13.7499 11.7858C14.5935 11.7858 15.3367 12.2067 15.7725 12.8459M13.7499 11.7858C12.9063 11.7858 12.1632 12.2067 11.7273 12.8459M13.7499 11.7858V10.8334M15.7725 12.8459L16.6666 12.262M11.7273 12.8459L10.8333 12.262"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                                    <path d="M3.33325 2.5H16.6666" stroke="white" stroke-width="1.25"
                                          stroke-linecap="round"/>
                                    <path d="M3.33325 7.5H16.6666" stroke="white" stroke-width="1.25"
                                          stroke-linecap="round"/>
                                    <path d="M3.33325 12.5H7.49992" stroke="white" stroke-width="1.25"
                                          stroke-linecap="round"/>
                                </svg>
                            </div>
                            <span class="">{{ __('Service Order') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('admin.service_invoices.index')}}"
                           class="d-flex align-items-center cg-10 {{ @$showServiceOrderInvoice }}">
                            <div class="d-flex">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M3.33325 15.5381V6.71184C3.33325 4.3335 3.33325 3.14433 4.06549 2.40548C4.79772 1.66663 5.97623 1.66663 8.33325 1.66663H11.6666C14.0236 1.66663 15.2021 1.66663 15.9343 2.40548C16.6666 3.14433 16.6666 4.3335 16.6666 6.71184V15.5381C16.6666 16.7979 16.6666 17.4277 16.2816 17.6756C15.6525 18.0809 14.68 17.2311 14.1908 16.9227C13.7867 16.6678 13.5847 16.5404 13.3603 16.533C13.118 16.525 12.9123 16.6473 12.4757 16.9227L10.8833 17.927C10.4537 18.1978 10.2389 18.3333 9.99992 18.3333C9.76092 18.3333 9.54617 18.1978 9.11658 17.927L7.52419 16.9227C7.12004 16.6678 6.91797 16.5404 6.69369 16.533C6.45135 16.525 6.24569 16.6473 5.80898 16.9227C5.31988 17.2311 4.34731 18.0809 3.71821 17.6756C3.33325 17.4277 3.33325 16.7979 3.33325 15.5381Z"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                    <path d="M13.3334 5H6.66675" stroke="white" stroke-width="1.25"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8.33341 8.33337H6.66675" stroke="white" stroke-width="1.25"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                    <path
                                        d="M12.0833 8.22917C11.3929 8.22917 10.8333 8.71883 10.8333 9.32292C10.8333 9.927 11.3929 10.4167 12.0833 10.4167C12.7736 10.4167 13.3333 10.9063 13.3333 11.5104C13.3333 12.1145 12.7736 12.6042 12.0833 12.6042M12.0833 8.22917C12.6275 8.22917 13.0905 8.5335 13.2621 8.95833M12.0833 8.22917V7.5M12.0833 12.6042C11.539 12.6042 11.076 12.2998 10.9044 11.875M12.0833 12.6042V13.3333"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <span class="">{{ __('Service Invoice') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-divider"></li>
                @endcan

                @canany(['Manage Consultations', 'Manage Free Consultations'])
                    @can('Manage Consultations')
                        <li>
                            <a href="#"
                               class="d-flex align-items-center cg-10 {{isset($showManageConsultation) ? 'collapsed active' : ''}}"
                               data-bs-toggle="collapse" data-bs-target="#collapseConsulter"
                               aria-expanded="{{isset($showManageConsultation) ? 'true' : 'false'}}"
                               aria-controls="collapseConsulter">
                                <div class="d-flex">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M3.75 8.33335C3.75 5.19065 3.75 3.61931 4.72631 2.643C5.70262 1.66669 7.27397 1.66669 10.4167 1.66669H11.6667C14.8093 1.66669 16.3807 1.66669 17.357 2.643C18.3333 3.61931 18.3333 5.19065 18.3333 8.33335V11.6667C18.3333 14.8094 18.3333 16.3808 17.357 17.357C16.3807 18.3334 14.8093 18.3334 11.6667 18.3334H10.4167C7.27397 18.3334 5.70262 18.3334 4.72631 17.357C3.75 16.3808 3.75 14.8094 3.75 11.6667V8.33335Z"
                                            stroke="white" stroke-width="1.25"/>
                                        <path d="M3.75008 5H1.66675M3.75008 10H1.66675M3.75008 15H1.66675"
                                              stroke="white"
                                              stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path
                                            d="M12.7289 7.07719C12.7289 7.99764 11.9828 8.74385 11.0623 8.74385C10.1419 8.74385 9.39575 7.99764 9.39575 7.07719C9.39575 6.15675 10.1419 5.41058 11.0623 5.41058C11.9828 5.41058 12.7289 6.15675 12.7289 7.07719Z"
                                            stroke="white" stroke-width="1.25" stroke-linecap="round"
                                            stroke-linejoin="round"/>
                                        <path
                                            d="M7.7666 13.0968C8.64858 11.739 10.0492 11.2302 11.0625 11.2312C12.0759 11.2323 13.4354 11.739 14.3173 13.0968C14.3744 13.1846 14.3901 13.2927 14.3387 13.384C14.1324 13.7497 13.492 14.4755 13.0294 14.5247C12.498 14.5812 11.1077 14.5891 11.0636 14.5894C11.0194 14.5891 9.58625 14.5812 9.0545 14.5247C8.592 14.4755 7.95158 13.7497 7.74532 13.384C7.69386 13.2927 7.70957 13.1846 7.7666 13.0968Z"
                                            stroke="white" stroke-width="1.25" stroke-linecap="round"
                                            stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <span class="">{{__('Manage Consultations')}}</span>
                            </a>
                            <div class="collapse {{@$showManageConsultation}}" id="collapseConsulter"
                                 data-bs-parent="#sidebarMenu">
                                <ul class="zSidebar-submenu">
                                    <li><a class="{{ @$activeConsultationSlot }}"
                                           href="{{route('admin.consultations.slots.index')}}">{{__('Slots')}}</a></li>
                                    <li><a class="{{ @$activeMeetingPlatform }}"
                                           href="{{route('admin.consultations.meeting_platforms.index')}}">{{__('Meeting Platform')}}</a>
                                    </li>
                                    <li><a class="{{ @$activeConsulter }}"
                                           href="{{route('admin.consultations.index')}}">{{__('Consultant')}}</a></li>
                                    <li><a class="{{ @$activeAppointment }}"
                                           href="{{route('admin.consultations.appointments.index')}}">{{__('Appointments')}}</a>
                                    </li>
                                    <li><a class="{{ @$activeAppointmentOrder }}"
                                           href="{{route('admin.orders.index', ['orderType' => 'consultation'])}}">{{__('Orders')}}</a>
                                    </li>
                                    <li><a class="{{ @$activeConsultationReview }}"
                                           href="{{route('admin.consultations.review.list')}}">{{__('Reviews')}}</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endcan
                    @can('Manage Free Consultations')
                        <li>
                            <a href="{{route('admin.free_consultations.index')}}"
                               class="d-flex align-items-center cg-10 {{ @$activeFreeConsultation }}">
                                <div class="d-flex">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10.0001 18.3333C6.66096 18.3333 4.99141 18.3333 3.95408 17.235C2.91675 16.1366 2.91675 14.3688 2.91675 10.8333C2.91675 7.29778 2.91675 5.53001 3.95408 4.43166C4.99141 3.33331 6.66096 3.33331 10.0001 3.33331C13.3392 3.33331 15.0087 3.33331 16.0461 4.43166C17.0834 5.53001 17.0834 7.29778 17.0834 10.8333C17.0834 14.3688 17.0834 16.1366 16.0461 17.235C15.0087 18.3333 13.3392 18.3333 10.0001 18.3333Z"
                                            stroke="white" stroke-width="1.25" stroke-linecap="round"
                                            stroke-linejoin="round"/>
                                        <path d="M6.66675 3.33335V1.66669" stroke="white" stroke-width="1.25"
                                              stroke-linecap="round"/>
                                        <path d="M13.3333 3.33335V1.66669" stroke="white" stroke-width="1.25"
                                              stroke-linecap="round"/>
                                        <path
                                            d="M11.6816 7.9105C11.6816 8.831 10.9355 9.57716 10.0151 9.57716C9.09456 9.57716 8.34839 8.831 8.34839 7.9105C8.34839 6.99006 9.09456 6.2439 10.0151 6.2439C10.9355 6.2439 11.6816 6.99006 11.6816 7.9105Z"
                                            stroke="white" stroke-width="1.25" stroke-linecap="round"
                                            stroke-linejoin="round"/>
                                        <path
                                            d="M6.71923 13.9301C7.60119 12.5723 9.00175 12.0635 10.0152 12.0645C11.0285 12.0656 12.3881 12.5723 13.27 13.9301C13.327 14.0179 13.3427 14.126 13.2912 14.2173C13.085 14.583 12.4446 15.3088 11.9821 15.358C11.4506 15.4145 10.0603 15.4224 10.0162 15.4227C9.97208 15.4224 8.53891 15.4145 8.00717 15.358C7.54462 15.3088 6.90421 14.583 6.69796 14.2173C6.6465 14.126 6.66221 14.0179 6.71923 13.9301Z"
                                            stroke="white" stroke-width="1.25" stroke-linecap="round"
                                            stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <span class="">{{ __('Leads') }}</span>
                            </a>
                        </li>
                    @endcan
                    <li class="sidebar-divider"></li>
                @endcanany

                @can('Manage Questions')
                    <li>
                        <a href="#"
                           class="d-flex align-items-center cg-10 {{isset($showQuestions) ? 'collapsed active' : ''}}"
                           data-bs-toggle="collapse" data-bs-target="#collapseQuestions"
                           aria-expanded="{{isset($showQuestions) ? 'true' : 'false'}}"
                           aria-controls="collapseQuestions">
                            <div class="d-flex">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 1.66663C13.6819 1.66663 15 3.0743 15 6.66663C15 9.66663 10 10.8333 10 13.3333" stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                                    <path d="M10 17.5H10.0083" stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                                    <path d="M6.66667 6.66663C6.66667 4.33329 8.33333 3.33331 10 3.33331C11.6667 3.33331 13.3333 4.33329 13.3333 6.66663C13.3333 8.99996 10 9.99996 10 12.5" stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <span class="">{{__('Questions')}}</span>
                        </a>
                        <div class="collapse {{@$showQuestions}}" id="collapseQuestions" data-bs-parent="#sidebarMenu">
                            <ul class="zSidebar-submenu">
                                <li><a class="{{ @$activeQuestion }}" href="{{route('admin.questions.index')}}">{{__('Questions')}}</a></li>
                                <li><a class="{{ @$activeQuestionStructure }}" href="{{route('admin.form-structure.index')}}">{{__('Questions Structure')}}</a></li>
                            </ul>
                        </div>
                    </li>
                @endcan

                @can('Manage Events')
                    <li>
                        <a href="#"
                           class="d-flex align-items-center cg-10 {{isset($showManageEvent) ? 'collapsed active' : ''}}"
                           data-bs-toggle="collapse" data-bs-target="#collapseEvent"
                           aria-expanded="{{isset($showManageEvent) ? 'true' : 'false'}}" aria-controls="collapseEvent">
                            <div class="d-flex">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15 1.66669V3.33335M5 1.66669V3.33335" stroke="white" stroke-width="1.25"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                    <path
                                        d="M2.08325 10.2027C2.08325 6.57162 2.08325 4.75607 3.12669 3.62803C4.17012 2.5 5.84949 2.5 9.20825 2.5H10.7916C14.1503 2.5 15.8298 2.5 16.8732 3.62803C17.9166 4.75607 17.9166 6.57162 17.9166 10.2027V10.6307C17.9166 14.2617 17.9166 16.0773 16.8732 17.2053C15.8298 18.3333 14.1503 18.3333 10.7916 18.3333H9.20825C5.84949 18.3333 4.17012 18.3333 3.12669 17.2053C2.08325 16.0773 2.08325 14.2617 2.08325 10.6307V10.2027Z"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                    <path d="M2.5 6.66669H17.5" stroke="white" stroke-width="1.25"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="">{{__('Manage Events')}}</span>
                        </a>
                        <div class="collapse {{@$showManageEvent}}" id="collapseEvent" data-bs-parent="#sidebarMenu">
                            <ul class="zSidebar-submenu">
                                <li><a class="{{ @$activeEvent }}"
                                       href="{{route('admin.events.index')}}">{{__('Events')}}</a></li>
                                <li><a class="{{ @$activeEventOrder }}"
                                       href="{{ route('admin.orders.index', ['orderType' => 'event']) }}">{{ __('Orders') }}</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('Manage Courses')
                    <li>
                        <a href="#"
                           class="d-flex align-items-center cg-10 {{isset($showManageCourse) ? 'collapsed active' : ''}}"
                           data-bs-toggle="collapse" data-bs-target="#collapseCourse"
                           aria-expanded="{{isset($showManageCourse) ? 'true' : 'false'}}"
                           aria-controls="collapseCourse">
                            <div class="d-flex">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16.6669 12.5C16.6669 14.0532 16.6669 14.8297 16.4131 15.4423C16.0748 16.259 15.4259 16.9079 14.6091 17.2462C13.9965 17.5 13.22 17.5 11.6669 17.5H9.16688C6.02419 17.5 4.45283 17.5 3.47652 16.5237C2.5002 15.5473 2.50022 13.976 2.50024 10.8332L2.50029 5.83319C2.50031 3.99232 3.99264 2.5 5.83352 2.5"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                    <path
                                        d="M8.33346 7.08331L8.69488 10.3907C8.72938 10.6672 8.8993 10.9082 9.1553 11.0182C9.72696 11.2637 10.7976 11.6666 11.6668 11.6666C12.536 11.6666 13.6067 11.2637 14.1784 11.0182C14.4344 10.9082 14.6043 10.6672 14.6388 10.3907L15.0001 7.08331M17.0835 6.24998V9.39098M11.6668 3.33331L5.8335 5.83331L11.6668 8.33331L17.5001 5.83331L11.6668 3.33331Z"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="">{{__('Manage Courses')}}</span>
                        </a>
                        <div class="collapse {{@$showManageCourse}}" id="collapseCourse" data-bs-parent="#sidebarMenu">
                            <ul class="zSidebar-submenu">
                                <li><a class="{{ @$activeProgram }}"
                                       href="{{route('admin.courses.programs.index')}}">{{__('Program')}}</a></li>
                                <li><a class="{{ @$activeCourse }}"
                                       href="{{route('admin.courses.index')}}">{{__('Courses')}}</a></li>
                                <li><a class="{{ @$activeCourseOrder }}"
                                       href="{{ route('admin.orders.index', ['orderType' => 'course']) }}">{{__('Orders')}}</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('Manage Students')
                    <li>
                        <a href="{{route('admin.students.index')}}"
                           class="d-flex align-items-center cg-10 {{ @$activeStudent }}">
                            <div class="d-flex">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.08325 5.00004L6.66659 3.33337L11.2499 5.00004L9.16659 6.25004V7.50004C9.16659 7.50004 8.611 7.08337 6.66659 7.08337C4.72214 7.08337 4.16659 7.50004 4.16659 7.50004V6.25004L2.08325 5.00004ZM2.08325 5.00004V8.33337"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                    <path
                                        d="M9.16675 7.08337V7.82412C9.16675 9.25596 8.04746 10.4167 6.66675 10.4167C5.28604 10.4167 4.16675 9.25596 4.16675 7.82412V7.08337"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                    <path
                                        d="M12.7652 9.19117C12.7652 9.19117 13.1692 8.89708 14.5833 8.89708C15.9975 8.89708 16.4015 9.19117 16.4015 9.19117M12.7652 9.19117V8.33333L11.25 7.5L14.5833 6.25L17.9167 7.5L16.4015 8.33333V9.19117M12.7652 9.19117V9.43183C12.7652 10.436 13.5792 11.25 14.5833 11.25C15.5875 11.25 16.4015 10.436 16.4015 9.43183V9.19117"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                    <path
                                        d="M3.65429 13.2717C2.86831 13.7104 0.807505 14.6064 2.06267 15.7274C2.67581 16.275 3.35869 16.6667 4.21723 16.6667H9.11625C9.97483 16.6667 10.6577 16.275 11.2708 15.7274C12.526 14.6064 10.4652 13.7104 9.67916 13.2717C7.83609 12.2428 5.49741 12.2428 3.65429 13.2717Z"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                    <path
                                        d="M13.3333 16.6667H16.4206C17.0645 16.6667 17.5767 16.3534 18.0365 15.9153C18.9779 15.0185 17.4323 14.3017 16.8428 13.9507C15.7801 13.3179 14.4976 13.1716 13.3333 13.5118"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="">{{ __('Manage Students') }}</span>
                        </a>
                    </li>
                @endcan
                @can('Manage Staffs')
                    <li>
                        <a href="#"
                           class="d-flex align-items-center cg-10 {{isset($showManageStaff) ? 'collapsed active' : ''}}"
                           data-bs-toggle="collapse" data-bs-target="#collapseStaff"
                           aria-expanded="{{isset($showManageStaff) ? 'true' : 'false'}}" aria-controls="collapseStaff">
                            <div class="d-flex">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M15.5135 16.6667H15.922C16.8802 16.6667 17.6423 16.2301 18.3267 15.6197C20.0651 14.0689 15.9785 12.5 14.5834 12.5M12.9167 4.224C13.106 4.18646 13.3025 4.16669 13.5041 4.16669C15.0207 4.16669 16.2501 5.28598 16.2501 6.66669C16.2501 8.0474 15.0207 9.16669 13.5041 9.16669C13.3025 9.16669 13.106 9.14694 12.9167 9.10935"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                                    <path
                                        d="M3.73443 13.426C2.75195 13.9525 0.175949 15.0276 1.7449 16.3728C2.51133 17.03 3.36493 17.5 4.4381 17.5H10.5619C11.6351 17.5 12.4887 17.03 13.2551 16.3728C14.8241 15.0276 12.2481 13.9525 11.2656 13.426C8.96167 12.1913 6.03833 12.1913 3.73443 13.426Z"
                                        stroke="white" stroke-width="1.25"/>
                                    <path
                                        d="M10.8334 6.25002C10.8334 8.09097 9.341 9.58335 7.50008 9.58335C5.65913 9.58335 4.16675 8.09097 4.16675 6.25002C4.16675 4.40907 5.65913 2.91669 7.50008 2.91669C9.341 2.91669 10.8334 4.40907 10.8334 6.25002Z"
                                        stroke="white" stroke-width="1.25"/>
                                </svg>
                            </div>
                            <span class="">{{__('Manage Staffs')}}</span>
                        </a>
                        <div class="collapse {{@$showManageStaff}}" id="collapseStaff" data-bs-parent="#sidebarMenu">
                            <ul class="zSidebar-submenu">
                                <li><a class="{{ @$activeRole }}"
                                       href="{{route('admin.staffs.roles.index')}}">{{__('Roles')}}</a></li>
                                <li><a class="{{ @$activeStaff }}"
                                       href="{{route('admin.staffs.index')}}">{{__('Staffs')}}</a></li>
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('Manage Transaction')
                    <li>
                        <a href="{{ route('admin.transactions') }}"
                           class="d-flex align-items-center cg-10 {{ @$activeTransaction }}">
                            <div class="d-flex">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14.1472 16.25C16.1681 14.9063 17.5 12.6087 17.5 10C17.5 5.85787 14.1422 2.5 10 2.5C9.42717 2.5 8.86933 2.56422 8.33333 2.68585M14.1472 16.25V13.3333M14.1472 16.25H17.0833M5.83333 3.76296C3.82336 5.10839 2.5 7.39965 2.5 10C2.5 14.1422 5.85787 17.5 10 17.5C10.5728 17.5 11.1307 17.4357 11.6667 17.3142M5.83333 3.76296V6.66667M5.83333 3.76296H2.91667"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                    <path
                                        d="M8.698 12.2222V7.77773M10.0001 7.77773V6.66663M10.0001 13.3333V12.2222M8.698 9.99996H11.3022M11.3022 9.99996C11.7337 9.99996 12.0834 10.373 12.0834 10.8333V11.3889C12.0834 11.8491 11.7337 12.2222 11.3022 12.2222H7.91675M11.3022 9.99996C11.7337 9.99996 12.0834 9.62688 12.0834 9.16663V8.61104C12.0834 8.15083 11.7337 7.77773 11.3022 7.77773H7.91675"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="">{{ __('Transactions') }}</span>
                        </a>
                    </li>
                @endcan
                @canany(['Manage Events', 'Manage Courses', 'Manage Students', 'Manage Staffs', 'Manage Transaction'])
                <li class="sidebar-divider"></li>
                @endcanany
                @can('Manage Cms Settings')
                    <li>
                        <a href="#"
                           class="d-flex align-items-center cg-10 {{isset($showCmsSettings) ? 'collapsed active' : ''}}"
                           data-bs-toggle="collapse" data-bs-target="#collapseCmsSettings"
                           aria-expanded="{{isset($showCmsSettings) ? 'true' : 'false'}}"
                           aria-controls="collapseCmsSettings">
                            <div class="d-flex">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M1.88017 13.0268C1.90847 12.7575 2.10365 12.5404 2.49401 12.1063L3.35323 11.1457C3.56326 10.8798 3.7123 10.4166 3.7123 9.99979C3.7123 9.58329 3.56321 9.11996 3.35321 8.85413L2.49401 7.89355C2.10365 7.45943 1.90847 7.24238 1.88017 6.97303C1.85187 6.70368 1.99767 6.45065 2.28927 5.94461L2.70058 5.23078C3.01167 4.69092 3.1672 4.42098 3.43185 4.31335C3.6965 4.20571 3.9958 4.29065 4.59442 4.46052L5.61127 4.74693C5.99345 4.83506 6.39441 4.78507 6.74337 4.60578L7.0241 4.4438C7.32333 4.25214 7.5535 3.96956 7.6809 3.6374L7.9592 2.80624C8.14218 2.25623 8.23367 1.98123 8.45145 1.82393C8.66928 1.66663 8.95862 1.66663 9.5372 1.66663H10.4662C11.0449 1.66663 11.3342 1.66663 11.5519 1.82393C11.7698 1.98123 11.8613 2.25623 12.0443 2.80624L12.3225 3.6374C12.4499 3.96956 12.6801 4.25214 12.9794 4.4438L13.2601 4.60578C13.609 4.78507 14.01 4.83506 14.3922 4.74693L15.409 4.46052C16.0076 4.29065 16.3069 4.20571 16.5716 4.31335C16.8363 4.42098 16.9918 4.69092 17.3029 5.23078L17.7142 5.94461C18.0058 6.45065 18.1515 6.70368 18.1233 6.97303C18.095 7.24238 17.8998 7.45943 17.5095 7.89355L16.6502 8.85413C16.4403 9.11996 16.2911 9.58329 16.2911 9.99979C16.2911 10.4166 16.4402 10.8798 16.6502 11.1457L17.5095 12.1063C17.8998 12.5404 18.095 12.7575 18.1233 13.0268C18.1515 13.2962 18.0058 13.5492 17.7142 14.0552L17.3029 14.769C16.9918 15.3089 16.8363 15.5789 16.5716 15.6865C16.3069 15.7941 16.0076 15.7092 15.409 15.5393L14.3922 15.2529C14.01 15.1647 13.609 15.2148 13.26 15.3941L12.9793 15.5561C12.68 15.7478 12.4499 16.0303 12.3226 16.3625L12.0443 17.1937C11.8613 17.7437 11.7698 18.0187 11.5519 18.176C11.3342 18.3333 11.0449 18.3333 10.4662 18.3333H9.5372C8.95862 18.3333 8.66928 18.3333 8.45145 18.176C8.23367 18.0187 8.14218 17.7437 7.9592 17.1937"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                                    <path
                                        d="M2.28072 15.6498C3.18072 14.7498 6.24072 11.7198 6.54072 11.3698C6.85786 10.9998 6.60072 10.4998 6.75372 8.94979C6.82775 8.19979 6.98907 7.63792 7.45072 7.21979C8.00072 6.69979 8.45075 6.69979 10.0007 6.6648C11.3507 6.69979 11.5107 6.54979 11.6507 6.8998C11.7507 7.1498 11.4507 7.29979 11.0907 7.6998C10.2907 8.49979 9.82075 8.89979 9.77575 9.14979C9.45075 10.2498 10.7307 10.8998 11.4307 10.1998C11.6954 9.93504 12.9207 8.69979 13.0407 8.59979C13.1307 8.51979 13.3462 8.52363 13.4507 8.64979C13.5407 8.73821 13.5507 8.74979 13.5407 9.14979C13.5315 9.52004 13.5356 10.0516 13.5367 10.5998C13.5382 11.31 13.5007 12.0998 13.2007 12.4998C12.6007 13.3998 11.6007 13.4498 10.7007 13.4898C9.85075 13.5398 9.15075 13.4498 8.93075 13.6098C8.75075 13.6998 7.80072 14.6998 6.65072 15.8498L4.60072 17.8998C2.90072 19.2498 1.03072 17.1498 2.28072 15.6498Z"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <span class="">{{__('Cms Settings')}}</span>
                        </a>
                        <div class="collapse {{@$showCmsSettings}}" id="collapseCmsSettings"
                             data-bs-parent="#sidebarMenu">
                            <ul class="zSidebar-submenu">
                                <li><a class="{{ @$landingPage }}"
                                       href="{{route('admin.cms-settings.section-settings')}}">{{__('Landing Page')}}</a>
                                </li>
                                <li><a class="{{ @$activeStudyLevel }}"
                                       href="{{route('admin.study_levels.index')}}">{{__('Study Levels')}}</a></li>
                                <li><a class="{{ @$countryActive }}"
                                       href="{{route('admin.cms-settings.countries.index')}}">{{__('Destination Country')}}</a>
                                </li>
                                <li><a class="{{ @$universityActive }}"
                                       href="{{route('admin.cms-settings.universities.index')}}">{{__('Manage Universities')}}</a>
                                </li>
                                <li><a class="{{ @$scholarshipActive }}"
                                       href="{{route('admin.cms-settings.scholarships.index')}}">{{__('Manage Scholarships')}}</a>
                                </li>
                                <li><a class="{{ @$aboutUsActive }}"
                                       href="{{route('admin.cms-settings.about-us.index')}}">{{__('Manage About Us')}}</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#"
                           class="d-flex align-items-center cg-10 {{isset($showSubjectSettings) ? 'collapsed active' : ''}}"
                           data-bs-toggle="collapse" data-bs-target="#collapseSubjectSection"
                           aria-expanded="{{isset($showSubjectSettings) ? 'true' : 'false'}}"
                           aria-controls="collapseSubjectSection">
                            <div class="d-flex">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13.8439 13.4039C11.6497 14.6398 10.3977 17.2008 10 17.9167V6.66669C10.3454 6.04499 11.335 4.26374 13.0264 3.05309C13.739 2.54308 14.0952 2.28807 14.5477 2.52059C15 2.75311 15 3.26638 15 4.29291V11.6595C15 12.214 15 12.4913 14.8862 12.6861C14.7722 12.8809 14.4628 13.0553 13.8439 13.4039Z"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                    <path
                                        d="M10.0001 6.50463C9.42766 5.90336 7.76821 4.47533 4.98388 3.97465C3.57333 3.721 2.86806 3.59418 2.2674 4.08028C1.66675 4.56638 1.66675 5.35573 1.66675 6.93444V12.6081C1.66675 14.0516 1.66675 14.7733 2.05225 15.2239C2.43775 15.6746 3.28646 15.8272 4.98388 16.1324C6.49702 16.4045 7.67795 16.8381 8.53275 17.2738C9.37375 17.7023 9.79425 17.9167 10.0001 17.9167C10.2059 17.9167 10.6264 17.7023 11.4674 17.2738C12.3222 16.8381 13.5032 16.4045 15.0162 16.1324C16.7137 15.8272 17.5624 15.6746 17.9479 15.2239C18.3334 14.7733 18.3334 14.0516 18.3334 12.6081V6.93444C18.3334 5.35573 18.3334 4.56638 17.7327 4.08028C17.1321 3.59418 15.8334 3.97465 15.0001 4.58333"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="">{{__('Manage Subject')}}</span>
                        </a>
                        <div class="collapse {{@$showSubjectSettings}}" id="collapseSubjectSection"
                             data-bs-parent="#sidebarMenu">
                            <ul class="zSidebar-submenu">
                                <li><a class="{{ @$showSubjectCategorySettings }}"
                                       href="{{route('admin.subjects.categories.index')}}">{{__('Categories')}}</a>
                                </li>
                                <li><a class="{{ @$subjectActive }}"
                                       href="{{route('admin.subjects.index')}}">{{__('Subject')}}</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#"
                           class="d-flex align-items-center cg-10 {{isset($showManageBlog) ? 'collapsed active' : ''}}"
                           data-bs-toggle="collapse" data-bs-target="#collapseManageBlog"
                           aria-expanded="{{isset($showManageBlog) ? 'true' : 'false'}}"
                           aria-controls="collapseManageBlog">
                            <div class="d-flex">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16.4586 9.16663V8.33329C16.4586 5.19059 16.4586 3.61925 15.4823 2.64293C14.506 1.66663 12.9346 1.66663 9.79196 1.66663H8.95871C5.81602 1.66663 4.24468 1.66663 3.26837 2.64293C2.29206 3.61923 2.29204 5.19056 2.29202 8.33323L2.29199 11.6666C2.29196 14.8093 2.29195 16.3806 3.26823 17.357C4.24453 18.3332 5.81593 18.3333 8.95863 18.3333"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                    <path d="M6.04199 5.83337H12.7086M6.04199 10H12.7086" stroke="white"
                                          stroke-width="1.25" stroke-linecap="round"/>
                                    <path
                                        d="M11.042 17.3556V18.3333H12.0198C12.361 18.3333 12.5316 18.3333 12.6849 18.2698C12.8383 18.2062 12.9589 18.0856 13.2002 17.8445L17.2198 13.8245C17.4473 13.597 17.5611 13.4832 17.6219 13.3605C17.7377 13.127 17.7377 12.853 17.6219 12.6195C17.5611 12.4967 17.4473 12.383 17.2198 12.1555C16.9922 11.928 16.8785 11.8142 16.7557 11.7534C16.5222 11.6377 16.2481 11.6377 16.0146 11.7534C15.8919 11.8142 15.7781 11.928 15.5506 12.1555L11.5309 16.1755C11.2897 16.4166 11.1691 16.5372 11.1056 16.6905C11.042 16.844 11.042 17.0145 11.042 17.3556Z"
                                        stroke="white" stroke-width="1.25" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="">{{__('Manage Blog')}}</span>
                        </a>
                        <div class="collapse {{@$showManageBlog}}" id="collapseManageBlog"
                             data-bs-parent="#sidebarMenu">
                            <ul class="zSidebar-submenu">
                                <li><a class="{{ @$activeBlogTag }}"
                                       href="{{route('admin.cms-settings.blogs.tags.index')}}">{{__('Tags')}}</a>
                                </li>
                                <li><a class="{{ @$activeBlogCategory }}"
                                       href="{{route('admin.cms-settings.blogs.categories.index')}}">{{__('Categories')}}</a>
                                </li>
                                <li><a class="{{ @$activeBlog }}"
                                       href="{{route('admin.cms-settings.blogs.index')}}">{{__('Blogs')}}</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="sidebar-divider"></li>
                @endcan
                <li>
                    <a href="{{ route('admin.notification.all') }}"
                       class="d-flex align-items-center cg-10 {{ @$activeNotification }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M4.29864 9.57579C4.23741 10.7391 4.3078 11.9775 3.26844 12.757C2.7847 13.1198 2.5 13.6891 2.5 14.2939C2.5 15.1256 3.1515 15.8333 4 15.8333H16C16.8485 15.8333 17.5 15.1256 17.5 14.2939C17.5 13.6891 17.2153 13.1198 16.7316 12.757C15.6922 11.9775 15.7626 10.7391 15.7013 9.57579C15.5418 6.54348 13.0365 4.16663 10 4.16663C6.96347 4.16663 4.45823 6.54348 4.29864 9.57579Z"
                                    stroke="white" stroke-width="1.25" stroke-linecap="round"
                                    stroke-linejoin="round"/>
                                <path
                                    d="M8.75 2.60413C8.75 3.29448 9.30967 4.16663 10 4.16663C10.6903 4.16663 11.25 3.29448 11.25 2.60413C11.25 1.91377 10.6903 1.66663 10 1.66663C9.30967 1.66663 8.75 1.91377 8.75 2.60413Z"
                                    stroke="white" stroke-width="1.25"/>
                                <path
                                    d="M12.5 15.8334C12.5 17.2141 11.3807 18.3334 10 18.3334C8.61925 18.3334 7.5 17.2141 7.5 15.8334"
                                    stroke="white" stroke-width="1.25" stroke-linecap="round"
                                    stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="">{{ __('Notifications') }}</span>
                    </a>
                </li>
                @can('Manage Setting')
                    <li>
                        <a href="{{ route('admin.setting.configuration-settings') }}"
                           class="d-flex align-items-center cg-10 {{ @$activeConfiguration }}">
                            <div class="d-flex">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12.5 14.5833C12.5 14.5833 12.9167 14.5833 13.3333 15.4166C13.3333 15.4166 14.6568 13.3333 15.8333 12.9166"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                    <path
                                        d="M18.3333 14.1667C18.3333 16.4678 16.4678 18.3333 14.1667 18.3333C11.8655 18.3333 10 16.4678 10 14.1667C10 11.8655 11.8655 10 14.1667 10C16.4678 10 18.3333 11.8655 18.3333 14.1667Z"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                                    <path
                                        d="M12.5238 7.70392C12.0189 6.83456 11.0777 6.25 9.99992 6.25C8.38909 6.25 7.08325 7.55583 7.08325 9.16667C7.08325 10.0547 7.48013 10.8501 8.10619 11.385"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                                    <path
                                        d="M18.3201 8.94996C18.3001 8.49996 17.9975 7.95146 17.3256 6.7933L15.7225 4.03014C15.0532 2.8766 14.7187 2.29983 14.1683 1.98323C13.6181 1.66663 12.9502 1.66663 11.6145 1.66663H8.38566C7.04993 1.66663 6.38208 1.66663 5.83181 1.98323C5.28153 2.29983 4.94691 2.87659 4.27767 4.03013L2.6746 6.79329C2.0027 7.95146 1.66675 8.53046 1.66675 9.16663C1.66675 9.80279 2.0027 10.3818 2.6746 11.54L4.27767 14.3031C4.94691 15.4566 5.28153 16.0335 5.83181 16.35C6.35008 16.6 6.66435 16.6666 8.00008 16.6666"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <span class="">{{ __('Configurations') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.setting.application-settings') }}"
                           class="d-flex align-items-center cg-10 {{ @$activeSetting }}">
                            <div class="d-flex">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M9.56527 0.757929C9.58283 0.828314 9.59114 0.911478 9.60778 1.07781C9.63919 1.39198 9.6549 1.54907 9.68762 1.65049C9.87683 2.23715 10.5466 2.51458 11.0952 2.23355C11.1901 2.18497 11.3123 2.085 11.5566 1.88506C11.686 1.77922 11.7507 1.72629 11.8129 1.68894C12.1607 1.48005 12.6002 1.50197 12.9254 1.74443C12.9836 1.78779 13.0427 1.84688 13.1609 1.96507L13.8682 2.6724C13.9864 2.7906 14.0455 2.8497 14.0889 2.90786C14.3313 3.23313 14.3533 3.67264 14.1444 4.02042C14.107 4.0826 14.0541 4.14729 13.9482 4.27666C13.7483 4.52104 13.6483 4.64323 13.5997 4.73809C13.3187 5.28671 13.5961 5.95648 14.1828 6.14571C14.2842 6.17842 14.4413 6.19413 14.7555 6.22555L14.7555 6.22555L14.7555 6.22555C14.9219 6.24219 15.005 6.2505 15.0754 6.26807C15.469 6.36627 15.7643 6.69255 15.8229 7.09398C15.8333 7.16577 15.8333 7.24936 15.8333 7.41653V8.4169C15.8333 8.58398 15.8333 8.66752 15.8229 8.73927C15.7643 9.14077 15.469 9.4671 15.0753 9.56529C15.005 9.58284 14.9219 9.59115 14.7556 9.60777L14.7556 9.60778C14.4416 9.63918 14.2846 9.65487 14.1832 9.68755C13.5964 9.87672 13.3189 10.5467 13.6001 11.0954C13.6487 11.1901 13.7486 11.3122 13.9484 11.5564C14.0541 11.6857 14.107 11.7503 14.1443 11.8125C14.3533 12.1603 14.3314 12.5999 14.0888 12.9252C14.0455 12.9833 13.9865 13.0424 13.8684 13.1605L13.1609 13.8679C13.0427 13.9861 12.9836 14.0452 12.9255 14.0886C12.6002 14.331 12.1607 14.3529 11.8129 14.144C11.7507 14.1067 11.686 14.0538 11.5567 13.9479C11.3123 13.748 11.1901 13.648 11.0953 13.5994C10.5467 13.3184 9.87687 13.5958 9.68765 14.1825C9.65494 14.2839 9.63922 14.441 9.6078 14.7553L9.6078 14.7553C9.59115 14.9218 9.58282 15.0051 9.56524 15.0755C9.46702 15.469 9.14079 15.7643 8.73943 15.8228C8.6676 15.8333 8.58397 15.8333 8.4167 15.8333H7.41652C7.24935 15.8333 7.16577 15.8333 7.09398 15.8229C6.69255 15.7643 6.36627 15.469 6.26806 15.0754C6.2505 15.005 6.24218 14.9219 6.22555 14.7555L6.22555 14.7555L6.22555 14.7555C6.19413 14.4413 6.17842 14.2842 6.1457 14.1828C5.95647 13.5961 5.28671 13.3187 4.73809 13.5997C4.64323 13.6483 4.52104 13.7483 4.27664 13.9483L4.27663 13.9483C4.14725 14.0541 4.08256 14.1071 4.02037 14.1444C3.6726 14.3533 3.23309 14.3314 2.90784 14.0889C2.84967 14.0456 2.79057 13.9865 2.67236 13.8683L2.67235 13.8682L1.96505 13.1609L1.96503 13.1609C1.84684 13.0427 1.78774 12.9836 1.74439 12.9255C1.50193 12.6002 1.48001 12.1607 1.6889 11.8129C1.72625 11.7507 1.77917 11.6861 1.88502 11.5567L1.88504 11.5567C2.08498 11.3123 2.18496 11.1901 2.23354 11.0952C2.51457 10.5466 2.23714 9.87684 1.65048 9.68762C1.54906 9.65491 1.39197 9.6392 1.0778 9.60778L1.07778 9.60778C0.911469 9.59115 0.828312 9.58283 0.757931 9.56527C0.364306 9.46707 0.0690197 9.14078 0.0104693 8.73934C0 8.66755 0 8.58398 0 8.41683V7.41661C0 7.24936 0 7.16574 0.0104808 7.09392C0.0690547 6.69254 0.364294 6.3663 0.757855 6.26808C0.828276 6.25051 0.911482 6.24219 1.0779 6.22554C1.39224 6.19411 1.54942 6.17839 1.65089 6.14565C2.23744 5.95639 2.51482 5.28674 2.23389 4.73816C2.18529 4.64325 2.08525 4.52098 1.88517 4.27644L1.88517 4.27644C1.77922 4.14695 1.72625 4.0822 1.68888 4.01996C1.48007 3.67223 1.50198 3.23282 1.74436 2.9076C1.78774 2.84938 1.84688 2.79024 1.96518 2.67195L1.96518 2.67195L2.67239 1.96473L2.67239 1.96473C2.7906 1.84652 2.8497 1.78742 2.90787 1.74406C3.23313 1.50161 3.67263 1.47969 4.0204 1.68857C4.0826 1.72593 4.14731 1.77888 4.27673 1.88477C4.52106 2.08467 4.64322 2.18462 4.73801 2.23319C5.28669 2.51433 5.9566 2.23684 6.14578 1.65007C6.17846 1.5487 6.19416 1.3917 6.22556 1.07771L6.22556 1.0777L6.22556 1.07769C6.24218 0.911467 6.25049 0.828354 6.26804 0.75801C6.36623 0.36432 6.69257 0.0689842 7.09407 0.0104577C7.16582 0 7.24935 0 7.4164 0H8.41682C8.58397 0 8.66755 0 8.73933 0.0104697C9.14077 0.0690208 9.46706 0.364306 9.56527 0.757929ZM7.91666 11.0833C9.66556 11.0833 11.0833 9.66557 11.0833 7.91667C11.0833 6.16776 9.66556 4.75 7.91666 4.75C6.16776 4.75 4.75 6.16776 4.75 7.91667C4.75 9.66557 6.16776 11.0833 7.91666 11.0833Z"
                                          fill="#5D697A"/>
                                </svg>
                            </div>
                            <span class="">{{ __('Settings') }}</span>
                        </a>
                    </li>
                @endcan
                <li>
                    <a href="{{ route('admin.contact_us.list') }}"
                       class="d-flex align-items-center cg-10 {{ @$activeContactUs }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.5135 16.6667H15.922C16.8802 16.6667 17.6423 16.2301 18.3267 15.6197C20.0651 14.0689 15.9785 12.5 14.5834 12.5M12.9167 4.224C13.106 4.18646 13.3025 4.16669 13.5041 4.16669C15.0207 4.16669 16.2501 5.28598 16.2501 6.66669C16.2501 8.0474 15.0207 9.16669 13.5041 9.16669C13.3025 9.16669 13.106 9.14694 12.9167 9.10935" stroke="white" stroke-width="1.25" stroke-linecap="round"></path>
                                <path d="M3.73443 13.426C2.75195 13.9525 0.175949 15.0276 1.7449 16.3728C2.51133 17.03 3.36493 17.5 4.4381 17.5H10.5619C11.6351 17.5 12.4887 17.03 13.2551 16.3728C14.8241 15.0276 12.2481 13.9525 11.2656 13.426C8.96167 12.1913 6.03833 12.1913 3.73443 13.426Z" stroke="white" stroke-width="1.25"></path>
                                <path d="M10.8334 6.25002C10.8334 8.09097 9.341 9.58335 7.50008 9.58335C5.65913 9.58335 4.16675 8.09097 4.16675 6.25002C4.16675 4.40907 5.65913 2.91669 7.50008 2.91669C9.341 2.91669 10.8334 4.40907 10.8334 6.25002Z" stroke="white" stroke-width="1.25"></path>
                            </svg>
                        </div>
                        <span class="">{{__('Contact Us')}}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.profile.index') }}"
                       class="d-flex align-items-center cg-10 {{ @$activeProfile }}">
                        <div class="d-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                 fill="none">
                                <path
                                    d="M5.48131 12.9014C4.30234 13.6034 1.21114 15.0369 3.09389 16.8306C4.01359 17.7068 5.03791 18.3334 6.32573 18.3334H13.6743C14.9621 18.3334 15.9864 17.7068 16.9061 16.8306C18.7888 15.0369 15.6977 13.6034 14.5187 12.9014C11.754 11.2552 8.24599 11.2552 5.48131 12.9014Z"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path
                                    d="M13.75 5.41675C13.75 7.48781 12.0711 9.16675 10 9.16675C7.92893 9.16675 6.25 7.48781 6.25 5.41675C6.25 3.34568 7.92893 1.66675 10 1.66675C12.0711 1.66675 13.75 3.34568 13.75 5.41675Z"
                                    stroke="white" stroke-width="1.5"/>
                            </svg>
                        </div>
                        <span class="">{{__('Profile')}}</span>
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
