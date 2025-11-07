<!-- CTA -->
@if(getOption('cta_footer_status') == STATUS_ACTIVE)
<section class="cta-section section-gap-top">
    <div class="container">
        <div class="cta-content" data-background="{{ getFileUrl(getOption('cta_footer_image')) }}">
            <div class="left">
                <h4 class="title">{{ getOption('cta_footer_title') }}</h4>
                <p class="info">{{ getOption('cta_footer_description') }}</p>
                <a href="{{route('consultations.list')}}" class="sf-btn-icon-primary">
                    {{__('Book a Consultation')}}
                    <span class="icon">
                        <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                        <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                    </span>
                </a>
            </div>
        </div>
    </div>
</section>
@endif
<!-- Footer -->
<div class="footer-section" data-background="{{ asset('assets/images/footer-overlay-shape.png') }}">
    <div class="container">
        <div class="topArea">
            <div class="row rg-20 justify-content-lg-between">
                <div class="col-lg-3 col-md-4">
                    <h4 class="footer-title">{{__('About')}} {{getOption('app_name')}}</h4>
                    <p class="footer-info">{{ getOption('app_footer_text') }}</p>
                </div>
                <div class="col-lg-7 col-md-8">
                    <div class="pl-xl-40">
                        <h4 class="footer-title">{{__('Useful Links')}}</h4>
                        <div class="row rg-13">
                            <div class="col-sm-4 col-6">
                                <ul class="footer-menu">
                                    <li><a href="{{ route('about-us.details') }}">{{__('About Us')}}</a></li>
                                    <li><a href="{{ route('contact-us') }}">{{__('Contact Us')}}</a></li>
                                    <li><a href="{{url('')}}#serviceSection">{{__('Services')}}</a></li>
                                </ul>
                            </div>
                            <div class="col-sm-4 col-6">
                                <ul class="footer-menu">
                                    <li><a href="{{ route('blog.list') }}">{{__('Blog')}}</a></li>
                                    <li><a href="{{ route('subject.list') }}">{{__('Discover a Subject')}}</a></li>
                                    <li><a href="{{ route('universities.list') }}">{{__('University')}}</a></li>
                                </ul>
                            </div>
                            <div class="col-sm-4 col-6">
                                <ul class="footer-menu">
                                    <li><a href="{{url('')}}#howWeWork">{{__('How We Work')}}</a></li>
                                    <li><a href="{{url('')}}#freeConsultation">{{__('Free Consultation')}}</a></li>
                                    <li><a href="{{route('consultations.list')}}">{{__('Book a Consultation')}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-auto col-md-4">
                    <h4 class="footer-title">{{__('Policies')}}</h4>
                    <ul class="footer-menu">
                        <li><a href="{{ route('page', 'terms_services_page') }}">{{ __('Terms & Services') }}</a></li>
                        <li><a href="{{ route('page', 'refund_policy_page') }}">{{ __('Refund Policy') }}</a></li>
                        <li><a href="{{ route('page', 'privacy_policy_page') }}">{{ __('Privacy Policy') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="middleArea">
            <div class="row rg-20 justify-content-md-between">
                <div class="col-md-auto">
                    <div class="text-center text-md-start">
                        <a class="footer-contact-item" href="mailto:{{ getOption('app_email') }}">
                            <div class="icon d-flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="#121D35" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M22 6L12 13L2 6" stroke="#121D35" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="text-break">{{ getOption('app_email') }}</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-auto">
                    <div class="text-center">
                        <a class="footer-contact-item" href="{{ getOption('develop_by_link') }}" target="_blank">
                            <div class="icon d-flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="26" viewBox="0 0 24 26" fill="none">
                                    <path d="M21 11C21 18 12 24 12 24C12 24 3 18 3 11C3 8.61305 3.94821 6.32387 5.63604 4.63604C7.32387 2.94821 9.61305 2 12 2C14.3869 2 16.6761 2.94821 18.364 4.63604C20.0518 6.32387 21 8.61305 21 11Z" stroke="#121D35" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M12 14C13.6569 14 15 12.6569 15 11C15 9.34315 13.6569 8 12 8C10.3431 8 9 9.34315 9 11C9 12.6569 10.3431 14 12 14Z" stroke="#121D35" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span>{{ getOption('app_location') }}</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-auto">
                    <div class="text-center text-md-start text-lg-end">
                        <a class="footer-contact-item" href="tel:{{ getOption('app_contact_number') }}">
                            <div class="icon d-flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                                    <path
                                        d="M15.6769 5.70817C16.6943 5.90668 17.6294 6.40427 18.3624 7.13727C19.0954 7.87027 19.593 8.80532 19.7915 9.82275M15.6769 1.5415C17.7907 1.77633 19.7619 2.72294 21.2667 4.22589C22.7716 5.72884 23.7207 7.69881 23.9582 9.81234M22.9165 18.1248V21.2498C22.9177 21.5399 22.8582 21.8271 22.742 22.0929C22.6258 22.3587 22.4553 22.5973 22.2416 22.7934C22.0278 22.9896 21.7754 23.1389 21.5006 23.2318C21.2258 23.3248 20.9346 23.3593 20.6457 23.3332C17.4403 22.9849 14.3613 21.8896 11.6561 20.1353C9.13923 18.5359 7.00539 16.4021 5.40608 13.8853C3.64564 11.1678 2.55008 8.07379 2.20816 4.854C2.18213 4.56595 2.21636 4.27563 2.30868 4.00153C2.401 3.72743 2.54938 3.47556 2.74438 3.26194C2.93937 3.04833 3.17671 2.87766 3.44128 2.7608C3.70585 2.64394 3.99185 2.58344 4.28108 2.58317H7.40608C7.91161 2.5782 8.40169 2.75721 8.785 3.08685C9.1683 3.41649 9.41866 3.87426 9.48941 4.37484C9.62131 5.37491 9.86592 6.35684 10.2186 7.30192C10.3587 7.67476 10.3891 8.07996 10.306 8.4695C10.2229 8.85905 10.0299 9.21662 9.74983 9.49984L8.42691 10.8228C9.90978 13.4306 12.0691 15.5899 14.6769 17.0728L15.9998 15.7498C16.283 15.4698 16.6406 15.2768 17.0302 15.1937C17.4197 15.1106 17.8249 15.1409 18.1977 15.2811C19.1428 15.6337 20.1248 15.8784 21.1248 16.0103C21.6308 16.0816 22.093 16.3365 22.4233 16.7264C22.7536 17.1163 22.9292 17.614 22.9165 18.1248Z"
                                        stroke="#121D35"
                                        stroke-width="2.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </div>
                            <span>{{ getOption('app_contact_number') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottomArea">
            <div class="row rg-20 align-items-center justify-content-lg-between">
                <div class="col-xl-5 col-lg-7 col-md-6">
                    <p class="footer-copywrite">{{ getOption('app_copyright') }} <a target="_blank" href="{{ getOption('develop_by_link') }}" class="link">{{ getOption('develop_by') }}</a></p>
                </div>
                <div class="col-lg-2 col-md-3">
                    <a href="{{getOption('develop_by_link')}}" target="_blank" class="footer-logo"><img src="{{asset(getFileUrl(getOption('app_logo')))}}" alt="" /></a>
                </div>
                <div class="offset-xl-3 col-xl-2 col-lg-2 col-md-3">
                    <ul class="d-flex justify-content-md-end justify-content-center align-items-center g-12">
                        <li>
                            <a href="{{ getOption('social_media_facebook') }}" target="_blank" class="footer-social-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="20" viewBox="0 0 10 20" fill="none">
                                    <path d="M8.61969 11.4902L9.0759 8.08211H6.22341V5.87051C6.22341 4.93813 6.62188 4.0293 7.89943 4.0293H9.19622V1.12771C9.19622 1.12771 8.01941 0.897461 6.89427 0.897461C4.54516 0.897461 3.00967 2.52977 3.00967 5.48469V8.08211H0.398438V11.4902H3.00967V19.7289H6.22341V11.4902H8.61969Z" fill="#121D35" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="{{ getOption('social_media_twitter') }}" target="_blank" class="footer-social-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                    <path d="M12.5372 0.553223H14.8951L9.74508 6.43803L15.8036 14.447H11.061L7.34373 9.59084L3.09545 14.447H0.73418L6.24158 8.15137L0.433594 0.553223H5.29641L8.65295 4.99187L12.5372 0.553223ZM11.7089 13.0376H13.0148L4.58502 1.88916H3.18229L11.7089 13.0376Z" fill="#121D35" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="{{ getOption('social_media_linkedin') }}" target="_blank" class="footer-social-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="15" viewBox="0 0 18 15" fill="none">
                                    <path
                                        d="M3.81735 14.2998H0.289157V5.00357H3.81735V14.2998ZM2.05135 3.73548C0.923153 3.73548 0.00805664 2.9709 0.00805664 2.0478C0.00805665 1.60441 0.223332 1.17917 0.606524 0.865643C0.989717 0.552115 1.50944 0.375977 2.05135 0.375977C2.59327 0.375977 3.11299 0.552115 3.49618 0.865643C3.87937 1.17917 4.09465 1.60441 4.09465 2.0478C4.09465 2.9709 3.17917 3.73548 2.05135 3.73548ZM17.0222 14.2998H13.5016V9.77444C13.5016 8.69594 13.475 7.31286 11.6673 7.31286C9.8329 7.31286 9.5518 8.48459 9.5518 9.69674V14.2998H6.02741V5.00357H9.41125V6.27166H9.46063C9.93166 5.54126 11.0823 4.77046 12.7989 4.77046C16.3696 4.77046 17.026 6.69435 17.026 9.19323V14.2998H17.0222Z"
                                        fill="#121D35"
                                    />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
