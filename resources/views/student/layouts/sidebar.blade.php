<div class="zSidebar">
    <div class="zSidebar-overlay"></div>
    <!--  -->
    <div class="zSidebar-wrap h-100">
        <!-- Logo -->
        <a href="{{ route('student.dashboard') }}" class="zSidebar-logo">
            <img class="" src="{{ getSettingImage('app_logo') }}" alt=""/>
        </a>
        <!-- Menu & Logout -->
        <div class="zSidebar-fixed">
            <ul class="zSidebar-menu" id="sidebarMenu">
                <li>
                    <a href="{{route('student.dashboard')}}"
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
                <li>
                    <a href="{{ route('student.services.index') }}"
                       class="d-flex align-items-center cg-10 {{ @$activeService }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16.1812 13.0944C19.4734 8.91983 18.584 4.77923 16.2189 3.32846C13.9842 1.95769 12.0337 2.51009 10.8621 3.39001L9.99991 4.03465M16.1812 13.0944C15.3909 14.0963 14.3598 15.1003 13.0505 16.0695C11.762 17.0232 11.1177 17.5 10.0001 17.5C8.88241 17.5 8.23818 17.0232 6.94969 16.0695C0.184847 11.0624 0.848472 5.12744 3.78128 3.32846C6.01599 1.95769 7.96643 2.51009 9.13808 3.39001L9.99991 4.03465M16.1812 13.0944L11.5768 7.87066C11.3883 7.65683 11.0728 7.6108 10.8311 7.76187L9.00933 8.9005C8.36825 9.30117 7.5362 9.211 6.99583 8.68225C6.28206 7.98385 6.35407 6.81474 7.14816 6.20922L9.99991 4.03465"
                                    stroke="white" stroke-width="1.25" stroke-linecap="round"
                                    stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="">{{ __('Services') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('student.service_orders')}}"
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
                    <a href="{{route('student.service_invoices.index')}}"
                       class="d-flex align-items-center cg-10 {{ @$showServiceOrderInvoice }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3.33325 15.5381V6.71184C3.33325 4.3335 3.33325 3.14433 4.06549 2.40548C4.79772 1.66663 5.97623 1.66663 8.33325 1.66663H11.6666C14.0236 1.66663 15.2021 1.66663 15.9343 2.40548C16.6666 3.14433 16.6666 4.3335 16.6666 6.71184V15.5381C16.6666 16.7979 16.6666 17.4277 16.2816 17.6756C15.6525 18.0809 14.68 17.2311 14.1908 16.9227C13.7867 16.6678 13.5847 16.5404 13.3603 16.533C13.118 16.525 12.9123 16.6473 12.4757 16.9227L10.8833 17.927C10.4537 18.1978 10.2389 18.3333 9.99992 18.3333C9.76092 18.3333 9.54617 18.1978 9.11658 17.927L7.52419 16.9227C7.12004 16.6678 6.91797 16.5404 6.69369 16.533C6.45135 16.525 6.24569 16.6473 5.80898 16.9227C5.31988 17.2311 4.34731 18.0809 3.71821 17.6756C3.33325 17.4277 3.33325 16.7979 3.33325 15.5381Z"
                                    stroke="white" stroke-width="1.25" stroke-linecap="round"
                                    stroke-linejoin="round"/>
                                <path d="M13.3334 5H6.66675" stroke="white" stroke-width="1.25" stroke-linecap="round"
                                      stroke-linejoin="round"/>
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
                <li>
                    <a href="{{ route('student.consultation-appointment.list') }}"
                       class="d-flex align-items-center cg-10 {{ @$activeConsultation }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3.75 8.33335C3.75 5.19065 3.75 3.61931 4.72631 2.643C5.70262 1.66669 7.27397 1.66669 10.4167 1.66669H11.6667C14.8093 1.66669 16.3807 1.66669 17.357 2.643C18.3333 3.61931 18.3333 5.19065 18.3333 8.33335V11.6667C18.3333 14.8094 18.3333 16.3808 17.357 17.357C16.3807 18.3334 14.8093 18.3334 11.6667 18.3334H10.4167C7.27397 18.3334 5.70262 18.3334 4.72631 17.357C3.75 16.3808 3.75 14.8094 3.75 11.6667V8.33335Z"
                                    stroke="white" stroke-width="1.25"/>
                                <path d="M3.75008 5H1.66675M3.75008 10H1.66675M3.75008 15H1.66675" stroke="white"
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
                        <span class="">{{ __('My Consultations') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.review') }}"
                       class="d-flex align-items-center cg-10 {{ @$activeConsultationReview }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11.4397 2.87017L12.9062 5.82742C13.1062 6.23908 13.6395 6.63393 14.0895 6.70954L16.7475 7.15481C18.4473 7.44046 18.8473 8.68385 17.6224 9.91043L15.556 11.9939C15.206 12.3468 15.0144 13.0273 15.1227 13.5146L15.7143 16.0938C16.1809 18.1353 15.106 18.925 13.3146 17.858L10.8232 16.371C10.3732 16.1022 9.63166 16.1022 9.17333 16.371L6.68197 17.858C4.89883 18.925 3.81562 18.1269 4.28224 16.0938L4.87384 13.5146C4.98216 13.0273 4.79051 12.3468 4.44055 11.9939L2.37411 9.91043C1.15758 8.68385 1.54921 7.44046 3.24901 7.15481L5.90706 6.70954C6.34867 6.63393 6.88195 6.23908 7.08192 5.82742L8.54841 2.87017C9.34833 1.26553 10.6482 1.26553 11.4397 2.87017Z"
                                    stroke="white" stroke-width="1.25" stroke-linecap="round"
                                    stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="">{{ __('Consultation Review') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.my_courses.list') }}"
                       class="d-flex align-items-center cg-10 {{ @$activeMyCourse }}">
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
                        <span class="">{{ __('My Courses') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.event.list') }}"
                       class="d-flex align-items-center cg-10 {{ @$activeEvent }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M15 1.66669V3.33335M5 1.66669V3.33335" stroke="white" stroke-width="1.25"
                                      stroke-linecap="round" stroke-linejoin="round"/>
                                <path
                                    d="M2.08325 10.2027C2.08325 6.57162 2.08325 4.75607 3.12669 3.62803C4.17012 2.5 5.84949 2.5 9.20825 2.5H10.7916C14.1503 2.5 15.8298 2.5 16.8732 3.62803C17.9166 4.75607 17.9166 6.57162 17.9166 10.2027V10.6307C17.9166 14.2617 17.9166 16.0773 16.8732 17.2053C15.8298 18.3333 14.1503 18.3333 10.7916 18.3333H9.20825C5.84949 18.3333 4.17012 18.3333 3.12669 17.2053C2.08325 16.0773 2.08325 14.2617 2.08325 10.6307V10.2027Z"
                                    stroke="white" stroke-width="1.25" stroke-linecap="round"
                                    stroke-linejoin="round"/>
                                <path d="M2.5 6.66669H17.5" stroke="white" stroke-width="1.25" stroke-linecap="round"
                                      stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="">{{ __('My Events') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.career-corner.index') }}"
                       class="d-flex align-items-center cg-10 {{ @$activeCareerCorner }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M6.66667 7.5V5.83333C6.66667 4.44928 6.66667 3.75725 7.04466 3.27874C7.24897 3.02108 7.52108 2.82223 7.83333 2.70224C8.45893 2.5 9.24022 2.5 10.8333 2.5C12.4264 2.5 13.2077 2.5 13.8333 2.70224C14.1456 2.82223 14.4177 3.02108 14.622 3.27874C15 3.75725 15 4.44928 15 5.83333V7.5M13.75 17.5V15M8.75 17.5V15M2.5 7.5H17.5M2.91667 7.5L3.77375 13.8204C3.97288 15.3071 4.07245 16.0504 4.45933 16.5919C4.59693 16.7924 4.76242 16.9709 4.95089 17.1216C5.51944 17.5 6.27036 17.5 7.77222 17.5H14.2278C15.7296 17.5 16.4806 17.5 17.0491 17.1216C17.2376 16.9709 17.4031 16.7924 17.5407 16.5919C17.9275 16.0504 18.0271 15.3071 18.2263 13.8204L19.0833 7.5"
                                    stroke="white" stroke-width="1.25" stroke-linecap="round"
                                    stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="">{{ __('Career Corner') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.universities.index') }}"
                       class="d-flex align-items-center cg-10 {{ @$activeUniversities }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M2.5 8.33335L10 3.33335L17.5 8.33335M2.5 8.33335V16.6667L10 18.3334M2.5 8.33335L10 13.3334M17.5 8.33335V16.6667L10 18.3334M17.5 8.33335L10 13.3334M10 13.3334V18.3334"
                                    stroke="white" stroke-width="1.25" stroke-linecap="round"
                                    stroke-linejoin="round"/>
                                <path
                                    d="M14.1667 6.66669V3.33335H15.8333V5.41669"
                                    stroke="white" stroke-width="1.25" stroke-linecap="round"
                                    stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="">{{ __('Universities') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.transactions') }}"
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
                <li class="sidebar-divider"></li>
                <li>
                    <a href="{{ route('student.notification.all') }}"
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
                <li>
                    <a href="{{ route('student.profile.index') }}"
                       class="d-flex align-items-center cg-10 {{ @$activeProfile }}">
                        <div class="d-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                 fill="none">
                                <path
                                    d="M5.48131 12.9014C4.30234 13.6034 1.21114 15.0369 3.09389 16.8306C4.01359 17.7068 5.03791 18.3334 6.32573 18.3334H13.6743C14.9621 18.3334 15.9864 17.7068 16.9061 16.8306C18.7888 15.0369 15.6977 13.6034 14.5187 12.9014C11.754 11.2552 8.24599 11.2552 5.48131 12.9014Z"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"/>
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
