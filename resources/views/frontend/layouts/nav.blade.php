<!-- Header -->
<div class="ld-header-section">
    <div class="container">
        <div class="ld-header-wrap">
            <div class="row align-items-center">
                <div class="col-lg-2 col-6">
                    <a href="{{ route('frontend') }}" class="ld-header-logo">
                        <img src="{{asset(getFileUrl(getOption('app_logo_black')))}}" alt=""/></a>
                </div>
                <div class="col-lg-8 col-6">
                    <nav class="navbar navbar-expand-lg p-0">
                        <button class="navbar-toggler menu-navbar-toggler bd-c-black-color ms-auto" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar"
                                aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="navbar-collapse menu-navbar-collapse offcanvas offcanvas-start" tabindex="-1"
                             id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                            <button type="button"
                                    class="d-lg-none w-30 h-30 p-0 rounded-circle bg-white border-0 position-absolute top-10 right-10"
                                    data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa-solid fa-times"></i>
                            </button>
                            <ul class="navbar-nav menu-navbar-nav justify-content-center flex-wrap cg-25 rg-10 w-100">
                                <li class="nav-item">
                                    <a class="nav-link fs-16 fw-400 lh-28 text-para-text p-0 {{ @$activeAboutUsMenu }}" href="{{ route('about-us.details') }}">{{__('About Us')}}</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="{{@$activeStudyAbroadMenu}} nav-link fs-16 fw-400 lh-28 text-para-text p-0 dropdown-toggle menu-dropdown-toggle"
                                       href="#" role="button" data-bs-toggle="dropdown"
                                       aria-expanded="false">{{__('Study Abroad')}}</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item {{@$activeUniversityMenu}}" href="{{ route('universities.list') }}">{{__('Universities')}}</a></li>
                                        <li><a class="dropdown-item {{@$activeSubjectMenu}}" href="{{ route('subject.list') }}">{{__('Subjects')}}</a></li>
                                        <li><a class="dropdown-item {{@$activeScholarshipMenu}}" href="{{ route('scholarship.list') }}">{{__('Scholarship')}}</a></li>
                                    </ul>
                                </li>
                                @php
                                    $coursePrograms = courseProgram();
                                @endphp
                                @if(count($coursePrograms))
                                    <li class="nav-item dropdown">
                                        <a class="nav-link {{@$activeProgramMenu}} fs-16 fw-400 lh-28 text-para-text p-0 dropdown-toggle menu-dropdown-toggle"
                                           href="#" role="button" data-bs-toggle="dropdown"
                                           aria-expanded="false">Courses</a>
                                        <ul class="dropdown-menu">
                                            @foreach($coursePrograms as $courseProgram)

                                                <li>
                                                    <a class="dropdown-item {{ @${'activeCourseProgram' . $courseProgram->id} ? 'active' : '' }}" href="{{route('courses.program', $courseProgram->slug)}}">{{__($courseProgram->title)}}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif
                                <li class="nav-item"><a class="nav-link fs-16 fw-400 lh-28 text-para-text p-0 {{ @$activeBlogMenu }}" href="{{ route('blog.list') }}">{{__('Blogs')}}</a>
                                </li>
                                <li class="nav-item"><a class="nav-link fs-16 fw-400 lh-28 text-para-text p-0 {{ @$activeEventMenu }}" href="{{ route('event.list') }}">{{__('Events')}}</a>
                                </li>
                                <li class="nav-item"><a class="nav-link fs-16 fw-400 lh-28 text-para-text p-0 {{ @$activeContactUsMenu }}" href="{{ route('contact-us') }}">{{__('Contact Us')}}</a></li>
                                <li class="nav-item d-lg-none">
                                    <a class="sf-btn-icon-white" href="{{ route('login') }}">
                                        @auth()
                                            {{__('Dashboard')}}
                                        @else
                                            {{__('Sign Up')}}
                                            <span class="icon">
                                                <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                                <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                            </span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
                <div class="col-lg-2 d-none d-lg-block text-end">
                    <a href="{{ route('login') }}" class="sf-btn-icon-white">
                        @auth()
                            {{__('Dashboard')}}
                        @else
                            {{__('Sign Up')}}
                            <span class="icon">
                                <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                            </span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
