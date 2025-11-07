<div class="main-header">
    <!-- Left -->
    <div class="d-flex align-items-center cg-15">
        <!-- Mobile Menu Button -->
        <div class="mobileMenu">
            <button
                class="bd-one bd-c-stroke rounded-circle w-30 h-30 d-flex justify-content-center align-items-center text-para-text p-0 bg-transparent">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
        <!-- Breadcrumb -->
        <ol class="breadcrumb sf-breadcrumb">
            @if(@$isDashboard != true)
                <li class="breadcrumb-item"><a href="{{ route(getPrefix().'.dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
            @endif
            @if(@$pageTitleParent != null)
                <li class="breadcrumb-item"><a href="{{ url()->previous() }}">{{ @$pageTitleParent }}</a></li>
            @endif
            <li class="breadcrumb-item active">{{ @$pageTitle }}</li>
        </ol>
    </div>
    <!-- Right -->
    <div class="right d-flex justify-content-end align-items-center cg-12">
        <!-- Language - Notify -->
        <div class="d-flex align-items-center cg-12">
            <!-- Language switcher -->
            @if (!empty(getOption('show_language_switcher')) && getOption('show_language_switcher') == STATUS_ACTIVE)
                <div class="dropdown lanDropdown">
                    <button class="dropdown-toggle p-0 border-0 bg-transparent d-flex align-items-center cg-8" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ getFileUrl(selectedLanguage()?->flag_id) }}" alt="" />
                        <p class="fs-13 fw-600 lh-18 text-title-text">{{ selectedLanguage()?->language }}</p>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdownItem-one">
                        <li>
                            @foreach(appLanguages() as $app_lang)
                                <a class="d-flex align-items-center cg-8" href="{{ route('local',$app_lang->iso_code) }}">
                                    <div class="d-flex">
                                        <img src="{{ getFileUrl($app_lang->flag_id) }}" alt="" class="max-w-20 w-100" />
                                    </div>
                                    <p class="fs-13 fw-600 lh-28 text-para-text">{{ $app_lang->language }}</p>
                                </a>
                            @endforeach
                        </li>
                    </ul>
                </div>
            @endif
            <!-- Notify -->
            <div class="dropdown notifyDropdown">
                <button
                    class="p-0 w-41 h-41 bd-one bd-c-stroke rounded-circle bg-white d-flex justify-content-center align-items-center dropdown-toggle"
                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                        <path
                            d="M5.96077 6.39393C6.21967 3.80494 8.39825 1.83334 11.0002 1.83334C13.6021 1.83334 15.7807 3.80494 16.0396 6.39393L16.3023 9.02123C16.4322 10.3206 16.8789 11.5682 17.6033 12.6547L18.3926 13.8387C19.0339 14.8007 19.3546 15.2816 19.2821 15.6793C19.2446 15.885 19.1436 16.0738 18.9932 16.2191C18.7025 16.5 18.1245 16.5 16.9684 16.5H5.03196C3.87585 16.5 3.29779 16.5 3.00712 16.2191C2.85675 16.0738 2.75571 15.885 2.71822 15.6793C2.64574 15.2816 2.96639 14.8007 3.60768 13.8387L4.39706 12.6547C5.12139 11.5682 5.56811 10.3206 5.69804 9.02123L5.96077 6.39393Z"
                            fill="#636370"/>
                        <path
                            d="M9.2293 18.9103C9.33375 19.0077 9.56392 19.0939 9.8841 19.1553C10.2043 19.2167 10.5966 19.25 11.0002 19.25C11.4037 19.25 11.796 19.2167 12.1162 19.1553C12.4364 19.0939 12.6666 19.0077 12.771 18.9103"
                            stroke="#636370" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    @if(count(userNotification('unseen')))
                    <span class="notify_no"></span>
                    @endif
                </button>
                <div class="dropdown-menu">
                    <ul class="notify-list">
                        @forelse (userNotification('seen-unseen') as $item)
                            <li class="align-items-center cg-6 d-flex">
                                @if($item->view_status == 1)
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
                                    <p class="fs-14 fw-500 lh-19 text-para-text max-w-220">{{ $item->title }}
                                        <a href="{{ route(getPrefix().'.notification.view', $item->id) }}"
                                           class="text-brand-primary text-decoration-underline hover-color-main-color">{{ __('See More') }}</a>
                                    </p>
                                </div>
                                <p class="flex-shrink-0 fs-12 fw-500 lh-16 text-para-text">{{ $item->created_at?->format('g : i A') }}</p>
                            </li>
                        @empty
                            <li class="align-items-center cg-6 d-flex">
                                {{__('No notification found')}}
                            </li>
                        @endforelse
                    </ul>
                    @if(count(userNotification('seen-unseen')))
                        <a href="{{ route(getPrefix().'.notification.all') }}"
                           class="link">{{ __('All Notifications') }}</a>
                    @endif
                </div>
            </div>
        </div>
        <!-- User -->
        <div class="dropdown headerUserDropdown">
            <button class="dropdown-toggle p-0 border-0 bg-transparent d-flex align-items-center cg-8" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-content">
                    <div class="wrap">
                        <div class="img">
                            <img src="{{ getFileUrl(auth()->user()->image) }}" alt="" class="rounded-circle w-100 h-100 object-fit-cover"/>
                            <span class="active"></span>
                        </div>
                    </div>
                    <h4 class="text-start d-none d-md-block fs-13 fw-600 lh-16 text-title-color">{{ auth()->user()->name }}</h4>
                </div>
            </button>
            <ul class="dropdown-menu dropdownItem-one">
                <li>
                    <a class="d-flex align-items-center cg-8" href="{{ route(getPrefix().'.profile.index') }}">
                        <div class="d-flex">
                            <svg width="12" height="13" viewBox="0 0 12 13" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.8966 11.6036C11.2651 11.5268 11.4846 11.1411 11.3015 10.8122C10.8978 10.0871 10.2617 9.44993 9.44812 8.96435C8.40026 8.33898 7.11636 8 5.79556 8C4.47475 8 3.19085 8.33897 2.14299 8.96435C1.32936 9.44993 0.693348 10.0871 0.289627 10.8122C0.106496 11.1411 0.325986 11.5268 0.694529 11.6036V11.6036C4.05907 12.3048 7.53204 12.3048 10.8966 11.6036V11.6036Z"
                                    fill="#63647B"/>
                                <circle cx="5.79574" cy="3.33333" r="3.33333" fill="#63647B"/>
                            </svg>
                        </div>
                        <p class="fs-14 fw-500 lh-17 text-para-text">{{ __('Profile') }}</p>
                    </a>
                </li>
                <li>
                    <a class="d-flex align-items-center cg-8" href="{{ route('logout') }}">
                        <div class="d-flex">
                            <svg width="10" height="14" viewBox="0 0 10 14" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M7.69547 0.823345L4.37659 0.301806C2.49791 0.00658503 1.55857 -0.141025 0.945912 0.382878C0.333252 0.906781 0.333252 1.85765 0.333252 3.75938V6.56258H4.75631L2.65829 3.94005L3.34155 3.39345L6.00821 6.72678L6.22686 7.00008L6.00821 7.27339L3.34155 10.6067L2.65829 10.0601L4.75631 7.43758H0.333252V10.2401C0.333252 12.1419 0.333252 13.0927 0.945912 13.6166C1.55857 14.1405 2.49791 13.9929 4.37658 13.6977L7.69547 13.1762C8.63623 13.0283 9.10661 12.9544 9.3866 12.627C9.66658 12.2996 9.66658 11.8234 9.66658 10.8711V3.12839C9.66658 2.17609 9.66658 1.69993 9.3866 1.37251C9.10661 1.0451 8.63623 0.971179 7.69547 0.823345Z"
                                      fill="#5D697A"/>
                            </svg>
                        </div>
                        <p class="fs-14 fw-500 lh-17 text-para-text">{{ __('Logout') }}</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
