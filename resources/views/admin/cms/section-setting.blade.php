@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="p-sm-30 p-15">
        <div class="">
            <div class="row rg-20">
                <div class="col-xl-3">
                    <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                        @include('admin.cms.partials.cms-sidebar')
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="align-items-center bd-b-one bd-c-one bd-c-stroke bd-c-stroke-2 d-flex justify-content-between mb-30 pb-15 pb-sm-30">
                        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{$pageTitle}}</h4>
                    </div>
                    <div class="bg-white p-sm-3 p-15 bd-one bd-c-stroke bd-ra-8">
                        <form class="ajax" action="{{ route('admin.setting.configuration-settings.update') }}" method="POST"
                              enctype="multipart/form-data" data-handler="settingCommonHandler">
                            @csrf
                            <div class="bg-white p-15 bd-one bd-c-stroke bd-ra-8">
                                <div class="table-responsive zTable-responsive">
                                    <table class="table zTable zTable-last-item-right zTable-section-setting">
                                        <thead>
                                            <tr>
                                                <th class="min-w-160">
                                                    <div>{{ __('Section Name') }}</div>
                                                </th>
                                                <th>
                                                    <div class="text-center">{{ __('Status') }} </div>
                                                </th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <h6>{{ __('Hero Banner') }}</h6>
                                                    <small class="fst-italic fw-normal">({{ __('Turning on the Hero Banner Section will make sure it is clearly visible to users on the landing page.') }})</small>
                                                </td>
                                                <td class="text-center pt-17">
                                                    <div class="zCheck form-switch">
                                                        <input class="form-check-input mt-0" onchange="changeSettingStatus(this,'hero_banner_status')" value="1" {{ getOption('hero_banner_status')==STATUS_ACTIVE ? 'checked' : '' }} name="hero_banner_status" type="checkbox" role="switch" id="hero_banner_status">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>{{ __('Service Section') }}</h6>
                                                    <small class="fst-italic fw-normal">({{ __('Turning on the Service Section will make sure it is clearly visible to users on the landing page.') }})</small>
                                                </td>
                                                <td class="text-center pt-17">
                                                    <div class="zCheck form-switch">
                                                        <input class="form-check-input mt-0" onchange="changeSettingStatus(this,'service_status')" value="1" {{ getOption('service_status')==STATUS_ACTIVE ? 'checked' : '' }} name="service_status" type="checkbox" role="switch" id="service_status">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>{{ __('About Us Section') }}</h6>
                                                    <small class="fst-italic fw-normal">({{ __('Turning on the About Us Section will make sure it is clearly visible to users on the landing page.') }})</small>
                                                </td>
                                                <td class="text-center pt-17">
                                                    <div class="zCheck form-switch">
                                                        <input class="form-check-input mt-0" onchange="changeSettingStatus(this,'about_us_status')" value="1" {{ getOption('about_us_status')==STATUS_ACTIVE ? 'checked' : '' }} name="about_us_status" type="checkbox" role="switch" id="service_status">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>{{ __('How We Work Section') }}</h6>
                                                    <small class="fst-italic fw-normal">({{ __('Turning on the How We Work Section will make sure it is clearly visible to users on the landing page.') }})</small>
                                                </td>
                                                <td class="text-center pt-17">
                                                    <div class="zCheck form-switch">
                                                        <input class="form-check-input mt-0" onchange="changeSettingStatus(this,'how_we_work_status')" value="1" {{ getOption('how_we_work_status')==STATUS_ACTIVE ? 'checked' : '' }} name="how_we_work_status" type="checkbox" role="switch" id="faq_status">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>{{ __('Country Section') }}</h6>
                                                    <small class="fst-italic fw-normal">({{ __('Turning on the Country Section will make sure it is clearly visible to users on the landing page.') }})</small>
                                                </td>
                                                <td class="text-center pt-17">
                                                    <div class="zCheck form-switch">
                                                        <input class="form-check-input mt-0" onchange="changeSettingStatus(this,'country_status')" value="1" {{ getOption('country_status')==STATUS_ACTIVE ? 'checked' : '' }} name="country_status" type="checkbox" role="switch" id="faq_status">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>{{ __('Booking Consultation From') }}</h6>
                                                    <small class="fst-italic fw-normal">({{ __('Turning on the Booking Consultation From will make sure it is clearly visible to users on the landing page.') }})</small>
                                                </td>
                                                <td class="text-center pt-17">
                                                    <div class="zCheck form-switch">
                                                        <input class="form-check-input mt-0" onchange="changeSettingStatus(this,'booking_consultation_from')" value="1" {{ getOption('booking_consultation_from')==STATUS_ACTIVE ? 'checked' : '' }} name="booking_consultation_from" type="checkbox" role="switch" id="faq_status">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>{{ __('Top University Section') }}</h6>
                                                    <small class="fst-italic fw-normal">({{ __('Turning on the Top University Section will make sure it is clearly visible to users on the landing page.') }})</small>
                                                </td>
                                                <td class="text-center pt-17">
                                                    <div class="zCheck form-switch">
                                                        <input class="form-check-input mt-0" onchange="changeSettingStatus(this,'top_university_status')" value="1" {{ getOption('top_university_status')==STATUS_ACTIVE ? 'checked' : '' }} name="top_university_status" type="checkbox" role="switch" id="faq_status">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>{{ __('Upcoming Event Section') }}</h6>
                                                    <small class="fst-italic fw-normal">({{ __('Turning on the Upcoming Event Section will make sure it is clearly visible to users on the landing page.') }})</small>
                                                </td>
                                                <td class="text-center pt-17">
                                                    <div class="zCheck form-switch">
                                                        <input class="form-check-input mt-0" onchange="changeSettingStatus(this,'upcoming_event_status')" value="1" {{ getOption('upcoming_event_status')==STATUS_ACTIVE ? 'checked' : '' }} name="upcoming_event_status" type="checkbox" role="switch" id="faq_status">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>{{ __('Why Choose Us Section') }}</h6>
                                                    <small class="fst-italic fw-normal">({{ __('Turning on the Why Choose Us Section will make sure it is clearly visible to users on the landing page.') }})</small>
                                                </td>
                                                <td class="text-center pt-17">
                                                    <div class="zCheck form-switch">
                                                        <input class="form-check-input mt-0" onchange="changeSettingStatus(this,'why_choose_us_status')" value="1" {{ getOption('why_choose_us_status')==STATUS_ACTIVE ? 'checked' : '' }} name="why_choose_us_status" type="checkbox" role="switch" id="why_choose_us_status">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>{{ __('Blog / Articles Section') }}</h6>
                                                    <small class="fst-italic fw-normal">({{ __('Turning on the Blog / Articles Section will make sure it is clearly visible to users on the landing page.') }})</small>
                                                </td>
                                                <td class="text-center pt-17">
                                                    <div class="zCheck form-switch">
                                                        <input class="form-check-input mt-0" onchange="changeSettingStatus(this,'blog_article_status')" value="1" {{ getOption('blog_article_status')==STATUS_ACTIVE ? 'checked' : '' }} name="blog_article_status" type="checkbox" role="switch" id="why_choose_us_status">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>{{ __('Testimonial Section') }}</h6>
                                                    <small class="fst-italic fw-normal">({{ __('Turning on the Testimonial Section will make sure it is clearly visible to users on the landing page.') }})</small>
                                                </td>
                                                <td class="text-center pt-17">
                                                    <div class="zCheck form-switch">
                                                        <input class="form-check-input mt-0" onchange="changeSettingStatus(this,'testimonial_status')" value="1" {{ getOption('testimonial_status')==STATUS_ACTIVE ? 'checked' : '' }} name="testimonial_status" type="checkbox" role="switch" id="faq_status">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>{{ __('Faq Section') }}</h6>
                                                    <small class="fst-italic fw-normal">({{ __('Turning on the Faq Section will make sure it is clearly visible to users on the landing page.') }})</small>
                                                </td>
                                                <td class="text-center pt-17">
                                                    <div class="zCheck form-switch">
                                                        <input class="form-check-input mt-0" onchange="changeSettingStatus(this,'faq_status')" value="1" {{ getOption('faq_status')==STATUS_ACTIVE ? 'checked' : '' }} name="faq_status" type="checkbox" role="switch" id="faq_status">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>{{ __('CTA Footer Section') }}</h6>
                                                    <small class="fst-italic fw-normal">({{ __('Turning on the CTA Footer Section will make sure it is clearly visible to users on the landing page.') }})</small>
                                                </td>
                                                <td class="text-center pt-17">
                                                    <div class="zCheck form-switch">
                                                        <input class="form-check-input mt-0" onchange="changeSettingStatus(this,'cta_footer_status')" value="1" {{ getOption('cta_footer_status')==STATUS_ACTIVE ? 'checked' : '' }} name="cta_footer_status" type="checkbox" role="switch" id="faq_status">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>{{ __('Recapture In Contact Us Page') }}</h6>
                                                    <small class="fst-italic fw-normal">({{ __('Turning on the Contact Us section will ensure it is recaptured on the Contact Us page.') }})</small>
                                                </td>
                                                <td class="text-center pt-17">
                                                    <div class="zCheck form-switch">
                                                        <input class="form-check-input mt-0" onchange="changeSettingStatus(this,'recapture_in_contact_us')" value="1" {{ getOption('recapture_in_contact_us')==STATUS_ACTIVE ? 'checked' : '' }} name="recapture_in_contact_us" type="checkbox" role="switch">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="statusChangeRoute" value="{{ route('admin.setting.configuration-settings.update') }}">
@endsection
@push('script')
    <script src="{{ asset('admin/custom/js/configuration.js') }}"></script>
@endpush
