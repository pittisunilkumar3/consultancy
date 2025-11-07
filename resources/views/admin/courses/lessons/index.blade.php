@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@push('style')
    <!-- Plyr CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/plyr.css')}}"/>
@endpush
@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-24 fw-700 lh-32 text-title-text">{{$course->title}}</h4>
        <a href="{{route('admin.courses.edit', encodeId($course->id))}}"
           class="flipBtn-icon sf-flipBtn-primary flex-shrink-0">
            <div class="item">
                @include('partials.icons.edit')
                <span>{{__('Edit')}}</span>
            </div>
        </a>
    </div>
    <div class="p-sm-30 p-15">
        <!--  -->
        <div class="row rg-20">
            <div class="col-xll-3 col-lg-5 col-md-6">
                <div class="course-details-info bg-white p-16 bd-ra-5">
                    <div class="img"><img src="{{getFileUrl($course->thumbnail)}}" alt=""/></div>
                    <div class="content">
                        <ul class="zList-pb-13">
                            <li class="d-flex justify-content-between align-items-center g-10">
                                <div class="d-flex align-items-center g-10">
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
                                    <p class="fs-15 fw-500 lh-28 text-para-text">{{__('Program')}} :</p>
                                </div>
                                <p class="fs-15 fw-500 lh-28 text-para-text">{{$course->program->title}}</p>
                            </li>
                            <li class="d-flex justify-content-between align-items-center g-10">
                                <div class="d-flex align-items-center g-10">
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
                                    <p class="fs-15 fw-500 lh-28 text-para-text">{{__('Duration')}} :</p>
                                </div>
                                <p class="fs-15 fw-500 lh-28 text-para-text">{{$course->duration}} {{__('Days')}}</p>
                            </li>
                            @if($course->start_date)
                                <li class="d-flex justify-content-between align-items-center g-10">
                                    <div class="d-flex align-items-center g-10">
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
                                        <p class="fs-15 fw-500 lh-28 text-para-text">{{__('Start Date')}} :</p>
                                    </div>
                                    <p class="fs-15 fw-500 lh-28 text-para-text">{{date_format($course->start_date, 'd-m-Y')}}</p>
                                </li>
                            @endif
                            <li class="d-flex justify-content-between align-items-center g-10">
                                <div class="d-flex align-items-center g-10">
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
                                <p class="fs-15 fw-500 lh-28 text-para-text">{{showPrice($course->price)}}</p>
                            </li>
                            @foreach($course->course_benefits as $benefit)
                                <li class="d-flex justify-content-between align-items-center g-10">
                                    <div class="d-flex align-items-center g-10">
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
                                        <p class="fs-15 fw-500 lh-28 text-para-text">{{$benefit['name']}} :</p>
                                    </div>
                                    <p class="fs-15 fw-500 lh-28 text-para-text">{{$benefit['value']}}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-xll-9 col-lg-7 col-md-6">
                <div class="accordion zAccordion-reset zAccordion-one" id="accordionModule">
                    @foreach($course->lessons as $lesson)
                        <div class="item-wrap lesson-item">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button {{$loop->first ? '' : 'collapsed' }}" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapse-lesson-{{$lesson->id}}"
                                            aria-expanded="{{$loop->first ? 'true' : 'false' }}"
                                            aria-controls="collapse-lesson-{{$lesson->id}}">{{$lesson->title}}</button>
                                </h2>
                                <div id="collapse-lesson-{{$lesson->id}}"
                                     class="accordion-collapse collapse  {{$loop->first ? 'show' : '' }}"
                                     data-bs-parent="#accordionModule">
                                    <div class="accordion-body">
                                        <p class="fs-12 fw-500 lh-18 text-para-text pb-20">
                                            {!! $lesson->description !!}
                                        </p>
                                        <ul class="course-modules-list lecture-list">
                                            @foreach($lesson->lectures as $lecture)
                                                <li class="item">
                                                    <div class="addAttach d-flex justify-content-end">
                                                        <button data-lesson_id="{{$lesson->id}}"
                                                                data-lecture_id="{{$lecture->id}}"
                                                                class="add-resource-btn fs-12 fw-600 lh-17 text-brand-primary text-decoration-underline p-0 border-0 bg-transparent">
                                                            {{__('Add Attachments')}}</button>
                                                    </div>
                                                    <div class="content-wrap">
                                                        <p class="fs-12 fw-500 lh-24 text-para-text pt-11 lecture-number">{{addLeadingZero($loop->iteration)}}</p>
                                                        <div class="moduleDetails-content-alt">
                                                            <form class="lecture-form ajax" method="POST"
                                                                  action="{{route('admin.courses.lectures.store', ['course_id' => $course->id, 'lesson_id' => $lesson->id])}}"
                                                                  data-handler="settingCommonHandler">
                                                                @csrf
                                                                <div class="d-flex align-items-center g-10 titleWrap">
                                                                    <input type="hidden" name="id"
                                                                           value="{{$lecture->id}}">
                                                                    <input name="title" type="text"
                                                                           class="form-control zForm-control lecture-input"
                                                                           value="{{$lecture->title}}"
                                                                           placeholder="{{__('Type Lecture Title')}}"/>
                                                                    <button class="{{$loop->first ? '' : 'collapsed'}}"
                                                                            type="button"
                                                                            data-bs-toggle="collapse"
                                                                            data-bs-target="#collapse-lecture-{{$lecture->id}}"
                                                                            aria-expanded="{{$loop->first ? 'true' : ''}}"
                                                                            aria-controls="collapse-lecture-{{$lecture->id}}">
                                                                        <img
                                                                            src="{{asset('assets/images/icon/accordion-icon-1-alt.svg')}}"
                                                                            alt="" class="defaultIcon"/>
                                                                    </button>
                                                                </div>
                                                            </form>

                                                            <div class="collapse {{$loop->first ? 'show' : ''}}"
                                                                 id="collapse-lecture-{{$lecture->id}}">
                                                                @if(count($lecture->resources))
                                                                    <div class="card card-body">
                                                                        <ul class="zList-pb-5">
                                                                            @foreach($lecture->resources as $resource)
                                                                                <li class="d-flex justify-content-between align-items-center g-10">
                                                                                    <div class="d-flex g-7">
                                                                                        <div class="d-flex">
                                                                                            <img
                                                                                                src="{{asset('assets/images/icon/video-complete.svg')}}"
                                                                                                alt=""/></div>
                                                                                        <a href="#"
                                                                                           onclick="getEditModal('{{ route('admin.courses.resources.view', [$course->id, $lesson->id, $lecture->id, $resource->id]) }}', '#resource-view-modal')"
                                                                                           title="{{ __('View') }}">
                                                                                            <p class="fs-15 fw-500 lh-24 text-para-text">{{$resource->title}}
                                                                                                ({{getResourceType($resource->resource_type)}}
                                                                                                )</p>
                                                                                        </a>
                                                                                    </div>
                                                                                    <div
                                                                                        class="d-flex align-items-center g-5">
                                                                                        <p class="fs-15 fw-500 lh-24 text-para-text">
                                                                                            @if($resource->duration)
                                                                                                {{getResourceDuration($resource->duration)}}
                                                                                            @else
                                                                                                --:--
                                                                                            @endif
                                                                                        </p>
                                                                                        <button
                                                                                            onclick="deleteItem('{{ route('admin.courses.resources.delete', [$course->id, $lesson->id, $lecture->id, $resource->id]) }}', null, '{{ route('admin.courses.lessons.index', encodeId($course->id)) }}')"
                                                                                            class="delete-resource-btn w-12 h-12 border-0 bg-transparent p-0">
                                                                                            @include('partials.icons.delete')
                                                                                        </button>
                                                                                    </div>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                @else
                                                                    <div class="card card-body text-center">
                                                                        {{__('No resource')}}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <button class="deleteItem"
                                                                onclick="deleteItem('{{ route('admin.courses.lectures.delete', [$course->id, $lesson->id, $lecture->id]) }}', null, '{{ route('admin.courses.lessons.index', encodeId($course->id)) }}')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                 height="14" viewBox="0 0 14 14" fill="none">
                                                                <path
                                                                    d="M12.8337 1.16656L1.16699 12.8332M1.16699 1.16656L12.8337 12.8332"
                                                                    stroke="#D8D8D8" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div>
                                            <button type="button" data-lesson_id="{{$lesson->id}}"
                                                    class="mt-10 add-lecture-btn fs-12 fw-600 lh-17 text-brand-primary text-decoration-underline p-0 border-0 bg-transparent">
                                                + {{__('Add Lecture')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-column g-5">
                                <button type="button"
                                        onclick="getEditModal('{{ route('admin.courses.lessons.edit', [$course->id, $lesson->id]) }}', '#edit-module-modal')"
                                        title="{{ __('Edit') }}" class="editIcon">
                                    @include('partials.icons.edit')
                                </button>
                                <button type="button"
                                        onclick="deleteItem('{{ route('admin.courses.lessons.delete', [$course->id, $lesson->id]) }}', null, '{{ route('admin.courses.lessons.index', encodeId($course->id)) }}')"
                                        title="{{ __('Delete') }}" class="deleteIcon">
                                    @include('partials.icons.delete')
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if(!count($course->lessons))
                    <div
                        class="align-items-center bd-ra-5 bg-white course-details-info d-flex flex-column g-10 h-100 justify-content-center p-16">
                        <span class="text-center">{{__('No module found')}}</span>
                        <button data-bs-toggle="modal" data-bs-target="#add-module-modal"
                                class="fs-15 fw-600 lh-17 text-brand-primary text-decoration-underline p-0 border-0 bg-transparent">
                            + {{__('Add New Module')}}
                        </button>
                    </div>
                @else
                    <div class="mt-17">
                        <button data-bs-toggle="modal" data-bs-target="#add-module-modal"
                                class="fs-15 fw-600 lh-17 text-brand-primary text-decoration-underline p-0 border-0 bg-transparent">
                            + {{__('Add New Module')}}
                        </button>
                    </div>
                @endif
            </div>

            <div class="col-12">
                <div class="event-details-content">
                    <h4 class="fs-22 title">{{__('Enroll Students')}}</h4>
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
                            <th>
                                <div class="text-nowrap">{{__('Enroll Date')}}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{__('Status')}}</div>
                            </th>
                            <th>
                                <div>{{__('Action')}}</div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($course->enrolls as $enroll)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>
                                    <div class="min-w-160 d-flex align-items-center cg-10">
                                        <div
                                            class="flex-shrink-0 w-41 h-41 bd-one bd-c-stroke rounded-circle overflow-hidden bg-eaeaea d-flex justify-content-center align-items-center">
                                            <img src="{{getFileUrl($enroll->user->image)}}" alt="Image"
                                                 class="rounded avatar-xs w-100 h-100 object-fit-cover">
                                        </div>
                                    </div>
                                </td>
                                <td>{{$enroll->user->name}}</td>
                                <td>{{$enroll->user->email}}</td>
                                <td>{{$enroll->user->mobile}}</td>
                                <td>{{$enroll->created_at->format('Y-m-d')}}</td>
                                <td> {!! $enroll->status == STATUS_ACTIVE ? "<p class='zBadge zBadge-active'>" . __('Active') . "</p>" : "<p class='zBadge zBadge-inactive'>" . __('Deactivate') . "</p>" !!}</td>
                                <td>
                                    @if($enroll->status == STATUS_ACTIVE)
                                        <div class="d-flex g-10 justify-content-end">
                                            <button
                                                onclick="deleteItem('{{route('admin.courses.enrolls.revoke', $enroll->id)}}')"
                                                class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25"
                                                title="Delete">
                                                @include('partials.icons.delete')
                                            </button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal section start -->
    <div class="modal fade" id="add-module-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
                <div
                    class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                    <h2 class="fs-18 fw-600 lh-22 text-title-black">{{ __('Add Course Module') }}</h2>
                    <div class="mClose">
                        <button type="button"
                                class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                                data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                </div>
                <form class="ajax reset" action="{{ route('admin.courses.lessons.store', $course->id) }}"
                      method="post"
                      data-handler="commonResponseRedirect"
                      data-redirect-url="{{route('admin.courses.lessons.index', encodeId($course->id))}}">
                    @csrf
                    <div class="row rg-20">
                        <div class="">
                            <label for="title" class="zForm-label-alt">{{ __('Title') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" placeholder="{{ __('Type Title') }}"
                                   class="form-control zForm-control-alt">
                        </div>
                        <div class="">
                            <label for="description" class="zForm-label-alt">{{ __('Description') }} <span
                                    class="text-danger">*</span></label>
                            <textarea name="description" id="description" placeholder="{{ __('Type Description') }}"
                                      class="form-control zForm-control-alt"></textarea>
                        </div>
                    </div>
                    <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
                        <button type="submit" class="sf-btn-primary flex-shrink-0">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Modal section end -->

    <!-- Edit Modal section start -->
    <div class="modal fade" id="edit-module-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">

            </div>
        </div>
    </div>

    <!-- Add Lecture Modal section start -->
    <div class="modal fade" id="add-lecture-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
                <div
                    class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                    <h2 class="fs-18 fw-600 lh-22 text-title-black">{{ __('Add Lecture') }}</h2>
                    <div class="mClose">
                        <button type="button"
                                class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                                data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                </div>
                <form class="ajax reset"
                      action="{{ route('admin.courses.lectures.store', ['course_id' => $course->id, 'lesson_id' => 'LESSON_ID']) }}"
                      method="post"
                      data-handler="commonResponseRedirect"
                      data-redirect-url="{{route('admin.courses.lessons.index', encodeId($course->id))}}">
                    @csrf
                    <div class="row rg-20">
                        <div class="">
                            <label for="lecture_title" class="zForm-label-alt">{{ __('Title') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="lecture_title" id="lecture_title"
                                   placeholder="{{ __('Type Title') }}"
                                   class="form-control zForm-control-alt">
                        </div>
                    </div>
                    <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
                        <button type="submit" class="sf-btn-primary flex-shrink-0">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Lecture Modal section end -->

    <!-- Add Resource Modal section start -->
    <div class="modal fade" id="add-resource-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
                <div
                    class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                    <h2 class="fs-18 fw-600 lh-22 text-title-black">{{ __('Add Resource') }}</h2>
                    <div class="mClose">
                        <button type="button"
                                class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                                data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                </div>
                <form class="ajax reset"
                      action="{{ route('admin.courses.resources.store', ['course_id' => $course->id, 'lesson_id' => 'LESSON_ID', 'lecture_id' => 'LECTURE_ID']) }}"
                      method="post"
                      data-handler="commonResponseRedirect"
                      data-redirect-url="{{route('admin.courses.lessons.index', encodeId($course->id))}}">
                    @csrf
                    <div class="row rg-20">
                        <div class="col-md-12">
                            <label for="resource_name" class="zForm-label-alt">{{ __('Resource Name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="resource_name" id="resource_name"
                                   placeholder="{{ __('Type Name') }}"
                                   class="form-control zForm-control-alt">
                        </div>
                        <div class="col-md-12">
                            <label for="resource_type" class="zForm-label-alt">{{ __('Resource Type') }}</label>
                            <select class="sf-select-two" id="resource_type" name="resource_type">
                                @foreach(getResourceType() as $optionIndex => $resourceType)
                                    <option value="{{$optionIndex}}">{{$resourceType}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="duration" class="zForm-label-alt">{{ __('Resource Duration (Seconds)') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="duration" id="duration" placeholder="{{ __('Type Duration') }}"
                                   class="form-control zForm-control-alt">
                        </div>
                        <div class="col-md-12">
                            <label for="resource-file" class="zForm-label-alt">{{ __('File') }} <span
                                    class="text-danger">*</span></label>
                            <div class="file-upload-one">
                                <label for="resource-file">
                                    <p class="fileName fs-14 fw-500 lh-24 text-para-text">{{ __('Choose file to upload') }}</p>
                                    <p class="fs-15 fw-700 lh-28 text-white">{{ __('Browse File') }}</p>
                                </label>
                                <input type="file" name="file" id="resource-file"
                                       class="fileUploadInput invisible position-absolute">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="resource-youtube_id" class="zForm-label-alt">{{ __('Youtube ID') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="youtube_id" id="resource-youtube_id"
                                   placeholder="{{ __('Type Youtube Video ID') }}"
                                   class="form-control zForm-control-alt">
                        </div>
                        <div class="col-md-12">
                            <label for="resource-google_slide_link"
                                   class="zForm-label-alt">{{ __('Google Slide Link') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="google_slide_link" id="resource-google_slide_link"
                                   placeholder="{{ __('Type Google Slide Link') }}"
                                   class="form-control zForm-control-alt">
                        </div>
                    </div>
                    <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
                        <button type="submit" class="sf-btn-primary flex-shrink-0">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Resource Modal section end -->

    <!-- Resource View Modal section start -->
    <div class="modal fade" id="resource-view-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">

            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{asset('assets/js/plyr.js')}}"></script>
    <script src="{{asset('admin/custom/js/course-modules.js')}}"></script>
    <script src="{{asset('common/js/youtube-player.js')}}"></script>
    <script src="{{asset('common/js/player.js')}}"></script>
@endpush
