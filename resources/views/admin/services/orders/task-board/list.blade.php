@extends('layouts.app')
@push('title')
    {{$pageTitle}}
@endpush
@section('content')
    <!-- Content -->
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="page-content-wrapper bg-white bd-ra-15 p-20">

            <div class="d-flex justify-content-between pb-18 mb-18 bd-b-one bd-c-stroke align-items-center">
                <!-- Page Title -->
                <h5 class="fs-18 fw-600 lh-20 text-title-text">{{$pageTitle}}</h5>

                <div class="d-flex gap-2 justify-content-between">
                    <a href="{{route(getPrefix().'.service_orders.documents', encodeId($order->id))}}"
                       class="flipBtn sf-flipBtn-brand">{{__('Documents')}}</a>
                    @if((auth()->user()->role == USER_ROLE_STUDENT && $order->onboard_status == 1) || (auth()->user()->role != USER_ROLE_STUDENT))
                        <a href="{{ route(getPrefix().'.service_orders.onboarding', encodeId($order->id)) }}"
                           class="flipBtn sf-flipBtn-secondary">
                            {{ __('Onboarding') }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="row flex-column-reverse flex-xl-row">
                <div class="col-xl-8">

                    <!-- Page Top Bar Start -->
                    <div
                        class="d-flex flex-column-reverse flex-sm-row justify-content-center justify-content-md-between align-items-center flex-wrap g-10 pb-25">
                        @if(auth()->user()->role == USER_ROLE_ADMIN)
                            <button type="button"
                                    class="flipBtn sf-flipBtn-primary"
                                    data-bs-toggle="modal" data-bs-target="#addTaskModal" title="Add Task">+ Add Task
                            </button>
                        @endif
                    </div>
                    <!-- Page Top Bar End -->

                    <!-- Project Board View Wrap Start -->
                    <div class="project-board-view-wrap">
                        <div class="row rg-25 project-board-view-wrap-row">
                            @php
                                $columns = [
                                    ['status' => ORDER_TASK_STATUS_PENDING, 'title' => __('Pending'), 'id' => 'pendingTask', 'statusClass' => 'pending'],
                                    ['status' => ORDER_TASK_STATUS_PROGRESS, 'title' => __('Progress'), 'id' => 'progressTask', 'statusClass' => 'progress'],
                                    ['status' => ORDER_TASK_STATUS_REVIEW, 'title' => __('Review'), 'id' => 'reviewTask', 'statusClass' => 'review'],
                                    ['status' => ORDER_TASK_STATUS_DONE, 'title' => __('Done'), 'id' => 'doneTask', 'statusClass' => 'done']
                                ];
                            @endphp

                            @foreach($columns as $column)
                                <div class="col-md-3">
                                    <div class="task-column">
                                        <div class="task-column-title task-column-title-{{ $column['statusClass'] }}">
                                            <h5 class="title">{{ $column['title'] }} (<span
                                                    class="itemCount">{{ $orderTasks->where('status', $column['status'])->count() }}</span>)
                                            </h5>
                                        </div>
                                        <div class="task-column-items d-flex flex-column rg-8" id="{{ $column['id'] }}"
                                             @if(auth()->user()->role != USER_ROLE_STUDENT) data-status="{{$column['status']}}"
                                             ondrop="dropIt(event)" ondragover="allowDrop(event)" @endif>
                                            @foreach($orderTasks->where('status', $column['status']) as $task)
                                                <div class="task-column-item"
                                                     id="{{ $column['id'] }}-{{ $loop->iteration }}"
                                                     draggable="true" ondragstart="dragStart(event)">
                                                    <div class="taskModalContent"
                                                         onclick="getEditModal('{{ route(getPrefix().'.service_orders.task-board.view', [$order->id, $task->id]) }}', '#viewTaskModal', 'taskViewResponse')"
                                                         title="Add Task">
                                                        <input type="hidden" name="task_id" value="{{$task->id}}">
                                                        @php
                                                            $colorClasses = [
                                                                ['bg-button', 'text-title-text'],
                                                                ['bg-brand-primary', 'text-white'],
                                                                ['bg-green', 'text-title-text']
                                                            ];
                                                            $colorCount = count($colorClasses);
                                                        @endphp

                                                        @if(count($task->labels))
                                                            <div class="d-flex flex-wrap rg-10 cg-5 pb-16">
                                                                @foreach($task->labels as $index => $label)
                                                                    @php
                                                                        $randomClass = $colorClasses[$index % $colorCount];
                                                                    @endphp
                                                                    <div class="py-6 px-10 bd-ra-2 {{ $randomClass[0] }}">
                                                                        <h4 class="fs-13 fw-400 lh-13 {{ $randomClass[1] }}">{{ $label->name }}</h4>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif

                                                        <h4 class="fs-14 fw-500 lh-19 text-title-text pb-17">#{{$task->taskID}} - {{ $task->task_name }}</h4>
                                                        @if($task->end_date || $task->priority)
                                                            <div class="align-items-center d-flex flex-wrap g-7 pb-17">
                                                                @if($task->end_date)
                                                                    <div
                                                                        class="p-5 bd-one bd-ra-2 bd-c-stroke d-flex align-items-center g-4">
                                                                        <div class="d-flex">
                                                                            <img
                                                                                src="{{ asset('assets/images/icon/clock.svg') }}"
                                                                                alt="">
                                                                        </div>
                                                                        <p class="fs-13 fw-400 lh-13 text-para-text">{{ formatDate($task->end_date) }}</p>
                                                                    </div>
                                                                @endif
                                                                @if($task->priority)
                                                                    <h4 class="fs-13 fw-400 lh-13 {{getPriorityClass($task->priority)}}">{{ getPriority($task->priority) }}</h4>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div
                                                        class="d-flex justify-content-between align-items-center flex-wrap g-10">
                                                        <div class="d-flex gap-1">
                                                            @if(auth()->user()->role != USER_ROLE_STUDENT)
                                                                <button
                                                                    onclick="getEditModal('{{ route('admin.service_orders.task-board.edit', [$order->id, $task->id]) }}', '#editTaskModal', 'editResponse')"
                                                                    class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-24 justify-content-center rounded w-24">
                                                                    <svg width="12" height="13" viewBox="0 0 12 13"
                                                                         fill="none"
                                                                         xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z"
                                                                            fill="#63647B"></path>
                                                                    </svg>
                                                                </button>
                                                                <button
                                                                    onclick="deleteItem('{{ route('admin.service_orders.task-board.delete', [$order->id, $task->id]) }}', null, '{{ route('admin.service_orders.task-board.index', encodeId($order->id)) }}')"
                                                                    class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-24 justify-content-center rounded w-24">
                                                                    <svg width="14" height="15" viewBox="0 0 14 15"
                                                                         fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                                              d="M5.76256 2.51256C6.09075 2.18437 6.53587 2 7 2C7.46413 2 7.90925 2.18437 8.23744 2.51256C8.4448 2.71993 8.59475 2.97397 8.67705 3.25H5.32295C5.40525 2.97397 5.5552 2.71993 5.76256 2.51256ZM3.78868 3.25C3.89405 2.57321 4.21153 1.94227 4.7019 1.4519C5.3114 0.84241 6.13805 0.5 7 0.5C7.86195 0.5 8.6886 0.84241 9.2981 1.4519C9.78847 1.94227 10.106 2.57321 10.2113 3.25H13C13.4142 3.25 13.75 3.58579 13.75 4C13.75 4.41422 13.4142 4.75 13 4.75H12V13C12 13.3978 11.842 13.7794 11.5607 14.0607C11.2794 14.342 10.8978 14.5 10.5 14.5H3.5C3.10217 14.5 2.72064 14.342 2.43934 14.0607C2.15804 13.7794 2 13.3978 2 13V4.75H1C0.585786 4.75 0.25 4.41422 0.25 4C0.25 3.58579 0.585786 3.25 1 3.25H3.78868ZM5 6.37646C5.34518 6.37646 5.625 6.65629 5.625 7.00146V11.003C5.625 11.3481 5.34518 11.628 5 11.628C4.65482 11.628 4.375 11.3481 4.375 11.003V7.00146C4.375 6.65629 4.65482 6.37646 5 6.37646ZM9.625 7.00146C9.625 6.65629 9.34518 6.37646 9 6.37646C8.65482 6.37646 8.375 6.65629 8.375 7.00146V11.003C8.375 11.3481 8.65482 11.628 9 11.628C9.34518 11.628 9.625 11.3481 9.625 11.003V7.00146Z"
                                                                              fill="#5D697A"></path>
                                                                    </svg>
                                                                </button>
                                                            @endif
                                                        </div>
                                                        <div class="dropdown-two taskImageDropdown">
                                                            <div class="dropdown-toggle justify-content-end min-w-auto">
                                                                @if(count($task->assignees))
                                                                    <div class="taskProgressImage">
                                                                        @foreach($task->assignees as $agent)
                                                                            <img
                                                                                src="{{ getFileUrl($agent->user->image) }}"
                                                                                alt="{{ $agent->user->name }}"/>
                                                                        @endforeach
                                                                        @if(auth()->user()->role != USER_ROLE_STUDENT)
                                                                            <button
                                                                                onclick="getEditModal('{{ route('admin.service_orders.task-board.edit', [$order->id, $task->id]) }}', '#editTaskModal', 'editResponse')"
                                                                                class="iconPlus"><i
                                                                                    class="fa-solid fa-plus"></i>
                                                                            </button>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Project Board View Wrap End -->
                </div>
                <div class="col-xl-4">
                    <div class="p-lg-30">
                        <div class="max-w-894 m-auto">
                            <!-- Order title -->
                            <h4 class="fs-18 fw-600 lh-20 text-title-text pb-11 text-md-start text-center">{{__("Order ID")}}
                                : {{$order->orderID}}</h4>
                            <!-- Order info - Note + Assign + Status -->
                            <div
                                class="d-flex justify-content-center justify-content-md-between align-items-start flex-wrap g-10 pb-33">
                                <!-- Left -->
                                <ul class="bd-ra-5 py-15 px-15 px-6 bg-secondary d-flex justify-content-between w-100">
                                    @if(auth()->user()->role != USER_ROLE_STUDENT)
                                        <li>
                                            <h4 class="fs-10 fw-500 lh-20 text-main-color">{{__("Assign")}}</h4>
                                            <div class="dropdown dropdown-two imageDropdown taskImageDropdown">
                                                <button
                                                    class="dropdown-toggle border-0 p-0 bg-transparent justify-content-start"
                                                    type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <div class="taskProgressImage">
                                                        @if(count($orderAssignee) > 0)
                                                            @foreach($orderAssignee as $assignee)
                                                                <img
                                                                    src="{{getFileUrl(getUserData($assignee, 'image'))}}"
                                                                    title=" {{ getUserData($assignee, 'name') }} "/>
                                                            @endforeach
                                                        @endif
                                                        <div class='iconPlus {{count($orderAssignee) ? '' : 'ml-0'}}'><i
                                                                class='fa-solid fa-plus'></i>
                                                        </div>
                                                    </div>
                                                </button>
                                                <ul class="dropdown-menu dropdownItem-three">
                                                    @forelse($teamMember as $key=>$member)
                                                        <li>
                                                            <div class="zForm-wrap-checkbox-2">
                                                                <input type="checkbox"
                                                                       class="form-check-input assign-member"
                                                                       {{in_array($member->id, $orderAssignee) ? 'checked' : ''}} id="userOne{{$key}}"
                                                                       value="{{$member->id}}"
                                                                       data-order="{{$order->id}}"/>
                                                                <label for="userOne">
                                                                    <div class="d-flex align-items-center g-8">
                                                                        <div
                                                                            class="flex-shrink-0 w-25 h-25 rounded-circle overflow-hidden">
                                                                            <img src="{{getFileUrl($member->image)}}"
                                                                                 alt="{{$member->name}}"/></div>
                                                                        <h4 class="fs-12 fw-500 lh-15 text-para-text text-nowrap">{{$member->name}}</h4>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @empty
                                                        <p class="">{{__("No Member Available")}}</p>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        </li>
                                    @endif
                                    <li>
                                        <h4 class="fs-10 fw-500 lh-20 text-main-color">{{__("Status")}}</h4>
                                        <div class="dropdown dropdown-two imageDropdown taskImageDropdown">
                                            <button
                                                class="dropdown-toggle border-0 p-0 bg-transparent g-10 min-w-auto"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <p class="fs-12 fw-700 lh-20 text-title-text">{{getOrderStatusName($order->working_status)}}</p>
                                            </button>
                                            @if(auth()->user()->role != USER_ROLE_STUDENT)
                                                <ul class="dropdown-menu dropdown-menu-end dropdownItem-four">
                                                    <li>
                                                        <a href="{{route('admin.service_orders.status.change',[ encodeId($order->id), WORKING_STATUS_PROCESSING])}}">
                                                            <p class="fs-14 fw-600 lh-17 text-para-text">{{__("Processing")}}</p>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{route('admin.service_orders.status.change',[ encodeId($order->id), WORKING_STATUS_SUCCEED])}}">
                                                            <p class="fs-14 fw-600 lh-17 text-para-text">{{__("Succeed")}}</p>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{route('admin.service_orders.status.change',[ encodeId($order->id), WORKING_STATUS_FAILED])}}">
                                                            <p class="fs-14 fw-600 lh-17 text-para-text">{{__("Failed")}}</p>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{route('admin.service_orders.status.change',[ encodeId($order->id), WORKING_STATUS_HOLD])}}">
                                                            <p class="fs-14 fw-600 lh-17 text-para-text">{{__("Hold")}}</p>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{route('admin.service_orders.status.change',[ encodeId($order->id), WORKING_STATUS_CANCELED])}}">
                                                            <p class="fs-14 fw-600 lh-17 text-para-text">{{__("Canceled")}}</p>
                                                        </a>
                                                    </li>
                                                </ul>
                                            @endif
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <!-- Info - Note -->
                            <div class="">
                                <!-- Info -->
                                <div class="pb-20">
                                    <div class="bd-one bd-c-stroke bd-ra-8 bg-white pt-12 pb-18">
                                        <!-- Created Date -->
                                        <div class="bd-b-one bd-c-stroke pb-15 mb-15 px-15">
                                            <div class="row justify-content-between g-10">
                                                <div class="col-auto">
                                                    <h4 class="fs-14 fw-500 lh-17 text-title-text">{{__("Created")}}
                                                        :</h4>
                                                </div>
                                                <div class="col-auto">
                                                    <h4 class="fs-14 fw-500 lh-17 text-para-text">{{date('d/m/Y', strtotime($order->created_at))}}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Client name -->
                                        <div class="bd-b-one bd-c-stroke pb-15 mb-15 px-15">
                                            <div class="row justify-content-between g-10">
                                                <div class="col-auto">
                                                    <h4 class="fs-14 fw-500 lh-17 text-title-text">{{__("Student")}}
                                                        :</h4>
                                                </div>
                                                <div class="col-auto">
                                                    <h4 class="fs-14 fw-500 lh-17 text-para-text">{{$order->student->name}}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Service -->
                                        <div class="bd-b-one bd-c-stroke pb-15 mb-15 px-15">
                                            <div class="row justify-content-between g-10">
                                                <div class="col-auto">
                                                    <h4 class="fs-14 fw-500 lh-17 text-title-text">{{__("Service")}}
                                                        :</h4>
                                                </div>
                                                <div class="col-auto">
                                                    <h4 class="fs-14 fw-500 lh-15 text-para-text">{{$order->service->title}}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Amount -->
                                        <div class="bd-b-one bd-c-stroke pb-15 mb-15 px-15">
                                            <div class="row justify-content-between g-10">
                                                <div class="col-auto">
                                                    <h4 class="fs-14 fw-500 lh-17 text-title-text">{{__("Amount")}}
                                                        :</h4>
                                                </div>
                                                <div class="col-auto">
                                                    <h4 class="text-end fs-14 fw-500 lh-17 text-para-text">{{currentCurrency('symbol')}}{{$order->total}}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Payment Status -->
                                        <div
                                            class="{{auth()->user()->role != USER_ROLE_STUDENT ? 'bd-b-one bd-c-stroke pb-15 mb-15' : '' }} px-15">
                                            <div class="row justify-content-between g-10">
                                                <div class="col-auto">
                                                    <h4 class="fs-14 fw-500 lh-17 text-title-text">{{__("Payment Status")}}
                                                        :</h4>
                                                </div>
                                                <div class="col-auto">
                                                    @if($order->payment_status == PAYMENT_STATUS_PENDING)
                                                        <h4 class="text-end fs-14 fw-500 lh-17 text-para-text">{{__("Unpaid")}}</h4>
                                                    @elseif($order->payment_status == PAYMENT_STATUS_PAID)
                                                        <h4 class="text-end fs-14 fw-500 lh-17 text-para-text">{{__("Paid")}}</h4>
                                                    @elseif($order->payment_status == PAYMENT_STATUS_CANCELLED)
                                                        <h4 class="text-end fs-14 fw-500 lh-17 text-para-text">{{__("Cancelled")}}</h4>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @if(auth()->user()->role != USER_ROLE_STUDENT)
                                            <div class="px-15">
                                                <div class="row justify-content-between g-10">
                                                    <div class="col-auto">
                                                        <h4 class="fs-14 fw-500 lh-17 text-title-text">{{__("Student Onboarding")}}
                                                            :</h4>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="d-flex form-check ps-0">
                                                            <div class="zCheck form-check form-switch">
                                                                <input class="form-check-input" type="checkbox"
                                                                       value="1"
                                                                       name="enable_onboarding"
                                                                       onclick="enableStatusOnboarding('{{ route('admin.service_orders.onboarding_enable', encodeId($order->id)) }}')"
                                                                       {{ $order->onboard_status == STATUS_ACTIVE ? 'checked' : '' }}
                                                                       role="switch" id="enableOnboarding"/>
                                                            </div>
                                                            <label class="form-check-label ps-3" for="enableOnboarding">
                                                                {{ __('Enable') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if(auth()->user()->role != USER_ROLE_STUDENT)
                                    <!-- Note -->
                                    <div class="">
                                        <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-20 p-10 mb-25">
                                            <div
                                                class="d-flex justify-content-between align-items-center flex-wrap g-10 pb-14">
                                                <h4 class="fs-14 fw-500 lh-17 text-title-text">{{__("All Notes")}}</h4>
                                                <button class="border-0 bg-transparent p-0 d-inline-block fs-15 fw-600 lh-20 text-brand-primary text-decoration-underline" id="noteAddModal" data-bs-toggle="modal" data-bs-target="#addNoteModal" data-order_id="{{encodeId($order->id)}}">+ {{__("Note")}}</button>
                                            </div>
                                            @if($order->notes !=null && count($order->notes) > 0)
                                                <!--  -->
                                                <ul class="note-list">
                                                    @foreach($order->notes as $note)
                                                        @if($note->user_id == auth()->id())
                                                            <li class="d-flex justify-content-between g-10 bg-note-self">
                                                                <!--  -->
                                                                <div class="flex-grow-1">
                                                                    <!--  -->
                                                                    <h4 class="title pb-15">{{$note->details}}</h4>
                                                                    <!--  -->
                                                                    <div
                                                                        class="d-flex align-items-center g-7 flex-wrap">
                                                                        <div
                                                                            class="flex-shrink-0 w-24 h-24 rounded-circle overflow-hidden">
                                                                            <img
                                                                                src="{{getFileUrl(getUserData($note->user_id, 'image'))}}"
                                                                                alt=""/></div>
                                                                        <h4 class="fs-12 fw-500 lh-14 text-title-text">{{getUserData($note->user_id, 'name')}}
                                                                            ({{__("You")}})</h4>
                                                                    </div>
                                                                </div>
                                                                <!--  -->
                                                                <div class="dropdown dropdown-one">
                                                                    <button
                                                                        class="dropdown-toggle p-0 bg-transparent w-24 h-24 d-flex justify-content-center align-items-center"
                                                                        type="button" data-bs-toggle="dropdown"
                                                                        aria-expanded="false"><i
                                                                            class="fa-solid fa-ellipsis-vertical"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu dropdown-menu-end dropdownItem-two">
                                                                        <li>
                                                                            <button
                                                                                class="d-flex align-items-center cg-8"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#addNoteModal"
                                                                                id="noteEditModal"
                                                                                data-order_id="{{encodeId($order->id)}}"
                                                                                data-details="{{$note->details}}"
                                                                                data-id="{{encodeId($note->id)}}">
                                                                                <div class="d-flex">
                                                                                    <svg width="12" height="13"
                                                                                         viewBox="0 0 12 13"
                                                                                         fill="none"
                                                                                         xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            d="M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z"
                                                                                            fill="#5D697A"/>
                                                                                    </svg>
                                                                                </div>
                                                                                <p class="fs-14 fw-500 lh-17 text-para-text">{{__("Edit")}}</p>
                                                                            </button
                                                                            >
                                                                        </li>
                                                                        <li>
                                                                            <button
                                                                                class="d-flex align-items-center cg-8"
                                                                                onclick="deleteItem('{{route('admin.service_orders.note.delete', encodeId($note->id))}}' , 'no','{{route('admin.service_orders.task-board.index', [encodeId($order->id)])}}')">
                                                                                <div class="d-flex">
                                                                                    <svg width="14" height="15"
                                                                                         viewBox="0 0 14 15"
                                                                                         fill="none"
                                                                                         xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            fill-rule="evenodd"
                                                                                            clip-rule="evenodd"
                                                                                            d="M5.76256 2.51256C6.09075 2.18437 6.53587 2 7 2C7.46413 2 7.90925 2.18437 8.23744 2.51256C8.4448 2.71993 8.59475 2.97397 8.67705 3.25H5.32295C5.40525 2.97397 5.5552 2.71993 5.76256 2.51256ZM3.78868 3.25C3.89405 2.57321 4.21153 1.94227 4.7019 1.4519C5.3114 0.84241 6.13805 0.5 7 0.5C7.86195 0.5 8.6886 0.84241 9.2981 1.4519C9.78847 1.94227 10.106 2.57321 10.2113 3.25H13C13.4142 3.25 13.75 3.58579 13.75 4C13.75 4.41422 13.4142 4.75 13 4.75H12V13C12 13.3978 11.842 13.7794 11.5607 14.0607C11.2794 14.342 10.8978 14.5 10.5 14.5H3.5C3.10217 14.5 2.72064 14.342 2.43934 14.0607C2.15804 13.7794 2 13.3978 2 13V4.75H1C0.585786 4.75 0.25 4.41422 0.25 4C0.25 3.58579 0.585786 3.25 1 3.25H3.78868ZM5 6.37646C5.34518 6.37646 5.625 6.65629 5.625 7.00146V11.003C5.625 11.3481 5.34518 11.628 5 11.628C4.65482 11.628 4.375 11.3481 4.375 11.003V7.00146C4.375 6.65629 4.65482 6.37646 5 6.37646ZM9.625 7.00146C9.625 6.65629 9.34518 6.37646 9 6.37646C8.65482 6.37646 8.375 6.65629 8.375 7.00146V11.003C8.375 11.3481 8.65482 11.628 9 11.628C9.34518 11.628 9.625 11.3481 9.625 11.003V7.00146Z"
                                                                                            fill="#5D697A"
                                                                                        />
                                                                                    </svg>
                                                                                </div>
                                                                                <p class="fs-14 fw-500 lh-17 text-para-text">{{__("Delete")}}</p>
                                                                            </button>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </li>
                                                        @else
                                                            <li class="d-flex justify-content-between g-10">
                                                                <!--  -->
                                                                <div class="flex-grow-1">
                                                                    <!--  -->
                                                                    <h4 class="title pb-15">{{$note->details}}</h4>
                                                                    <!--  -->
                                                                    <div
                                                                        class="d-flex align-items-center g-7 flex-wrap">
                                                                        <div
                                                                            class="flex-shrink-0 w-24 h-24 rounded-circle overflow-hidden">
                                                                            <img
                                                                                src="{{getFileUrl(getUserData($note->user_id, 'image'))}}"
                                                                                alt=""/></div>
                                                                        <h4 class="fs-12 fw-500 lh-14 text-title-text">{{getUserData($note->user_id, 'name')}}
                                                                            ({{__("Team Member")}})</h4>
                                                                    </div>
                                                                </div>
                                                                <!--  -->
                                                                <div class="dropdown dropdown-one">
                                                                    <button
                                                                        class="dropdown-toggle p-0 bg-transparent w-24 h-24 d-flex justify-content-center align-items-center"
                                                                        type="button" data-bs-toggle="dropdown"
                                                                        aria-expanded="false"><i
                                                                            class="fa-solid fa-ellipsis-vertical"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu dropdown-menu-end dropdownItem-two">
                                                                        <li>
                                                                            <button
                                                                                class="d-flex align-items-center cg-8"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#addNoteModal"
                                                                                id="noteEditModal"
                                                                                data-order_id="{{encodeId($order->id)}}"
                                                                                data-details="{{$note->details}}"
                                                                                data-id="{{encodeId($note->id)}}">
                                                                                <div class="d-flex">
                                                                                    <svg width="12" height="13"
                                                                                         viewBox="0 0 12 13"
                                                                                         fill="none"
                                                                                         xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            d="M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z"
                                                                                            fill="#5D697A"/>
                                                                                    </svg>
                                                                                </div>
                                                                                <p class="fs-14 fw-500 lh-17 text-para-text">{{__("Edit")}}</p>
                                                                            </button
                                                                            >
                                                                        </li>
                                                                        <li>
                                                                            <button
                                                                                class="d-flex align-items-center cg-8"
                                                                                onclick="deleteItem('{{route('admin.service_orders.note.delete', encodeId($note->id))}}' , 'no','{{route('admin.service_orders', encodeId($order->id))}}')">
                                                                                <div class="d-flex">
                                                                                    <svg width="14" height="15"
                                                                                         viewBox="0 0 14 15"
                                                                                         fill="none"
                                                                                         xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            fill-rule="evenodd"
                                                                                            clip-rule="evenodd"
                                                                                            d="M5.76256 2.51256C6.09075 2.18437 6.53587 2 7 2C7.46413 2 7.90925 2.18437 8.23744 2.51256C8.4448 2.71993 8.59475 2.97397 8.67705 3.25H5.32295C5.40525 2.97397 5.5552 2.71993 5.76256 2.51256ZM3.78868 3.25C3.89405 2.57321 4.21153 1.94227 4.7019 1.4519C5.3114 0.84241 6.13805 0.5 7 0.5C7.86195 0.5 8.6886 0.84241 9.2981 1.4519C9.78847 1.94227 10.106 2.57321 10.2113 3.25H13C13.4142 3.25 13.75 3.58579 13.75 4C13.75 4.41422 13.4142 4.75 13 4.75H12V13C12 13.3978 11.842 13.7794 11.5607 14.0607C11.2794 14.342 10.8978 14.5 10.5 14.5H3.5C3.10217 14.5 2.72064 14.342 2.43934 14.0607C2.15804 13.7794 2 13.3978 2 13V4.75H1C0.585786 4.75 0.25 4.41422 0.25 4C0.25 3.58579 0.585786 3.25 1 3.25H3.78868ZM5 6.37646C5.34518 6.37646 5.625 6.65629 5.625 7.00146V11.003C5.625 11.3481 5.34518 11.628 5 11.628C4.65482 11.628 4.375 11.3481 4.375 11.003V7.00146C4.375 6.65629 4.65482 6.37646 5 6.37646ZM9.625 7.00146C9.625 6.65629 9.34518 6.37646 9 6.37646C8.65482 6.37646 8.375 6.65629 8.375 7.00146V11.003C8.375 11.3481 8.65482 11.628 9 11.628C9.34518 11.628 9.625 11.3481 9.625 11.003V7.00146Z"
                                                                                            fill="#5D697A"
                                                                                        />
                                                                                    </svg>
                                                                                </div>
                                                                                <p class="fs-14 fw-500 lh-17 text-para-text">{{__("Delete")}}</p>
                                                                            </button>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="client-order-list-route" value="{{ route('admin.service_orders') }}">
    <input type="hidden" id="order_status_change_route"
           value="{{ route('admin.service_orders.task-board.update_status', $order->id) }}">

    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <form class="ajax" data-handler="commonResponseWithPageLoad"
                      action="{{route('admin.service_orders.task-board.store', $order->id)}}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="fs-18 fw-600 lh-22 text-title-text">{{__('Create Task')}}</h5>
                        <button type="button"
                                class="w-32 h-32 d-flex justify-content-center align-items-center bd-one bd-c-stroke rounded-circle bg-transparent fs-20 text-para-text"
                                data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                    </div>
                    <div class="modal-body task-modalBody-height overflow-y-auto">
                        @include('admin.services.orders.task-board.form')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="flipBtn sf-flipBtn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="flipBtn sf-flipBtn-primary">{{__('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">

            </div>
        </div>
    </div>

    <div class="modal fade" id="viewTaskModal" tabindex="-1" aria-labelledby="taskViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 bd-ra-10">

            </div>
        </div>
    </div>

    <!-- Add Note Modal -->
    <div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bd-c-stroke bd-one bd-ra-10">
                <div class="modal-body p-sm-25 p-15">
                    <!-- Header -->
                    <div
                        class="d-flex justify-content-between align-items-center g-10 pb-20 mb-17 bd-b-one bd-c-stroke">
                        <h4 class="fs-18 fw-600 lh-22 text-title-text">{{__('Add note')}}</h4>
                        <button type="button"
                                class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-12"
                                data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                    </div>
                    <!-- Body -->
                    <form method="POST" class="ajax reset"
                          action="{{ route('admin.service_orders.note.store') }}"
                          data-handler="commonResponseWithPageLoad">
                        @csrf
                        <div class="pb-25">
                            <label for="noteDetails" class="zForm-label-alt">{{__("Note Details")}}</label>
                            <textarea id="noteDetails" name="details" class="form-control zForm-control-alt min-h-175"
                                      placeholder="{{__("Write note details here")}}...."></textarea>
                            <input type="hidden" name="order_id" id="orderIdField">
                            <input type="hidden" name="id" id="noteIdField">
                        </div>
                        <!-- Button -->
                        <div class="d-flex g-12">
                            <button type="submit" class="flipBtn sf-flipBtn-primary">{{__("Save Note")}}</button>
                            <a href="{{ URL::previous() }}" type="button" class="flipBtn sf-flipBtn-secondary">{{__("Cancel")}}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Note Modal -->
    <div class="modal fade" id="fileSaveModal" tabindex="-1" aria-labelledby="fileSaveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bd-c-stroke bd-one bd-ra-10">
                <div class="modal-body p-sm-25 p-15">
                    <!-- Header -->
                    <div
                        class="d-flex justify-content-between align-items-center g-10 pb-20 mb-17 bd-b-one bd-c-stroke">
                        <h4 class="fs-18 fw-600 lh-22 text-title-text">{{__('Add File to Manager')}}</h4>
                        <button type="button"
                                class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                                data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                    </div>
                    <!-- Body -->
                    <form method="POST" id="fileSaveForm" class="ajax reset"
                          action="{{route('admin.service_orders.save-file', $order->id)}}"
                          data-handler="responseForFileSave">
                        @csrf
                        <input type="hidden" name="file" id="file-id">
                        <div class="pb-25">
                            <label for="file_name" class="zForm-label-alt">{{__("File Name")}}</label>
                            <input name="file_name" type="text" class="form-control zForm-control-alt" id="file_name"
                                   placeholder="{{ __('File Name') }}"/>
                        </div>
                        <!-- Button -->
                        <div class="d-flex g-12">
                            <button type="submit" class="sf-btn-primary">{{__("Save File")}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="progress_change_route"
           value="{{ route('admin.service_orders.task-board.change_progress', ['order_id' => $order->id, 'id' => '__ID__']) }}">
    <input type="hidden" id="assignMemberRoute" value="{{route('admin.service_orders.assign.member')}}">
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/client-order-task-boards.js') }}"></script>
@endpush
