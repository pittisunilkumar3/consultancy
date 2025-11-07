@extends('frontend.layouts.app')
@push('title')
    {{ __('Home') }}
@endpush
@section('content')
    <!-- Breadcrumb -->
    <section class="inner-breadcrumb-section">
        <div class="container">
            <div class="inner-breadcrumb-wrap" data-aos="fade-up" data-aos-duration="1000">
            <h4 class="inner-breadcrumb-title">{{__('Blog')}}</h4>
            <ol class="breadcrumb inner-breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('frontend')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('blog.list')}}">{{__('Subjects List')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('blog.details',$blogDetails->slug)}}">{{ $blogDetails->title }}</a></li>
            </ol>
        </div>
        </div>
    </section>
    <!--  -->
    <section class="section-gap-top">
            <div class="container">
                <div class="row rg-24">
                    <div class="col-lg-7">
                        <div class="blog-details-content" data-aos="fade-up" data-aos-duration="1000">
                            <div class="textBlock-wrap">
                                <div class="text-content">
                                    <div class="imgBlock">
                                        <img src="{{ getFileUrl($blogDetails->banner_image) }}" class="w-100" alt=""/>
                                    </div>
                                    <h4 class="title">{{ $blogDetails->title }}</h4>
                                    <div class="d-flex align-items-center flex-wrap g-17">
                                        <p class="date">{{ \Carbon\Carbon::parse($blogDetails->publish_date)->format('M jS Y') }}</p>
                                        <div class="author">
                                            <div class="d-flex">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M16.4585 9.16667V8.33334C16.4585 5.19064 16.4585 3.6193 15.4822 2.64298C14.5058 1.66667 12.9345 1.66667 9.79184 1.66667H8.95859C5.8159 1.66667 4.24455 1.66667 3.26825 2.64297C2.29194 3.61927 2.29192 5.19061 2.2919 8.33328L2.29187 11.6667C2.29184 14.8093 2.29183 16.3807 3.2681 17.357C4.24441 18.3333 5.81581 18.3333 8.9585 18.3333" stroke="#141B34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M6.04187 5.83333H12.7085M6.04187 9.99999H12.7085" stroke="#141B34" stroke-width="1.5" stroke-linecap="round"/>
                                                    <path d="M11.0418 17.3557V18.3333H12.0196C12.3608 18.3333 12.5314 18.3333 12.6847 18.2698C12.8381 18.2063 12.9587 18.0857 13.2 17.8445L17.2196 13.8245C17.4471 13.597 17.5609 13.4833 17.6217 13.3606C17.7375 13.1271 17.7375 12.853 17.6217 12.6195C17.5609 12.4968 17.4471 12.383 17.2196 12.1555C16.9921 11.928 16.8783 11.8143 16.7556 11.7534C16.5221 11.6378 16.2479 11.6378 16.0144 11.7534C15.8917 11.8143 15.7779 11.928 15.5504 12.1555L11.5307 16.1755C11.2895 16.4167 11.1689 16.5373 11.1054 16.6906C11.0418 16.844 11.0418 17.0145 11.0418 17.3557Z" stroke="#141B34" stroke-width="1.5" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                            <p class="name">{{ $blogDetails->blogCategoryName }}</p>
                                        </div>
                                    </div>
                                    <div class="pt-30 text">
                                        {!! $blogDetails->details !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="blog-details-sidebar" data-aos="fade-up" data-aos-duration="1000">
                            <div class="item">
                                <h4 class="title">{{ __('Related Post') }}</h4>
                                <ul class="zList-pb-15">
                                    @foreach($relatedPost as $data)
                                    <li>
                                        <a href="{{ route('blog.details',$data->slug) }}" class="blog-popular-item">
                                            <div class="img"><img src="{{ getFileUrl($data->banner_image) }}" alt="" /></div>
                                            <div class="content">
                                                <p class="date">{{ \Carbon\Carbon::parse($data->publish_date)->format('M jS Y') }}</p>
                                                <h4 class="title">{{ $data->title }}</h4>
                                            </div>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="item">
                                <h4 class="title">{{__('Share Us')}}</h4>
                                <ul class="d-flex justify-content-start align-items-center g-12">
                                    <li>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ (route('blog.details', ['slug' => $blogDetails->slug])) }}"
                                           class="footer-social-link" target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="20" viewBox="0 0 10 20" fill="none">
                                                <path d="M8.61969 11.4902L9.0759 8.08211H6.22341V5.87051C6.22341 4.93813 6.62188 4.0293 7.89943 4.0293H9.19622V1.12771C9.19622 1.12771 8.01941 0.897461 6.89427 0.897461C4.54516 0.897461 3.00967 2.52977 3.00967 5.48469V8.08211H0.398438V11.4902H3.00967V19.7289H6.22341V11.4902H8.61969Z" fill="#121D35" />
                                            </svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://twitter.com/intent/tweet?url={{ (route('blog.details', $blogDetails->slug)) }}"
                                           class="footer-social-link" target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                                <path d="M12.5372 0.553223H14.8951L9.74508 6.43803L15.8036 14.447H11.061L7.34373 9.59084L3.09545 14.447H0.73418L6.24158 8.15137L0.433594 0.553223H5.29641L8.65295 4.99187L12.5372 0.553223ZM11.7089 13.0376H13.0148L4.58502 1.88916H3.18229L11.7089 13.0376Z" fill="#121D35" />
                                            </svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ (route('blog.details', $blogDetails->slug)) }}"
                                           class="footer-social-link" target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="15" viewBox="0 0 18 15" fill="none">
                                                <path d="M3.81735 14.2998H0.289157V5.00357H3.81735V14.2998ZM2.05135 3.73548C0.923153 3.73548 0.00805664 2.9709 0.00805664 2.0478C0.00805665 1.60441 0.223332 1.17917 0.606524 0.865643C0.989717 0.552115 1.50944 0.375977 2.05135 0.375977C2.59327 0.375977 3.11299 0.552115 3.49618 0.865643C3.87937 1.17917 4.09465 1.60441 4.09465 2.0478C4.09465 2.9709 3.17917 3.73548 2.05135 3.73548ZM17.0222 14.2998H13.5016V9.77444C13.5016 8.69594 13.475 7.31286 11.6673 7.31286C9.8329 7.31286 9.5518 8.48459 9.5518 9.69674V14.2998H6.02741V5.00357H9.41125V6.27166H9.46063C9.93166 5.54126 11.0823 4.77046 12.7989 4.77046C16.3696 4.77046 17.026 6.69435 17.026 9.19323V14.2998H17.0222Z" fill="#121D35" />
                                            </svg>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
@endsection
