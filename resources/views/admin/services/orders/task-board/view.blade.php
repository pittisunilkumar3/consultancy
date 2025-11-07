<div>
    <div class="modal-header justify-content-end bg-stroke border-0">
        <button type="button"
                class="w-30 h-30 border-0 bd-ra-4 bg-white text-para-text d-flex justify-content-center align-items-center"
                data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
    </div>
    <div class="modal-body task-modalBody-height overflow-y-auto">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="bd-b-one bd-c-stroke">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><p>{{__('Dashboard')}}</p></li>
                <li class="breadcrumb-item">{{__('Orders')}}</li>
                <li class="breadcrumb-item">{{__('Task Board')}}</li>
                <li class="breadcrumb-item active">#{{$orderTask->taskID}}</li>
            </ol>
        </nav>
        <!--  -->
        <div class="row">
            <!-- Left -->
            <div class="col-lg-8 pe-lg-0">
                <!--  -->
                <div class="py-16 mb-16 bd-b-one bd-c-stroke pe-lg-2">
                    <h4 class="fs-24 fw-600 text-title-text pb-20">{{$orderTask->task_name}}</h4>
                    <p class="fs-18 fw-500 text-title-text pb-16">{{__('Description')}}</p>
                    <p class="fs-16 fw-400 text-para-text pb-16">{!! nl2br($orderTask->description) !!}</p>
                </div>
                <div class="taskChatWrap">
                    <nav>
                        <div class="nav nav-tabs zTab-reset zTab-three flex-wrap pl-sm-20" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-clientMessage-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-clientMessage" type="button" role="tab"
                                    aria-controls="nav-clientMessage" aria-selected="true">{{__("Client")}}</button>
                            @if(auth()->user()->role != USER_ROLE_STUDENT)
                                <button class="nav-link chat-team-tab" id="nav-teamMessage-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-teamMessage" type="button" role="tab"
                                        aria-controls="nav-teamMessage" aria-selected="false">{{__("Team")}}</button>
                            @endif
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-clientMessage" role="tabpanel"
                             aria-labelledby="nav-clientMessage-tab" tabindex="0">
                            <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-sm-20 p-10">
                                <div class="bd-ra-10 bg-body-bg overflow-hidden"
                                     data-background="{{asset("admin")}}/images/chat_bg.png">
                                    <div class="content-chat-message-user-wrap">
                                        <div class="content-chat-message-user">
                                            <!-- Body -->
                                            <div
                                                class="body-chat-message-user admin-client-chat taskBoard-modal-chatBody">
                                                @php
                                                    $type = CONVERSATION_TYPE_CLIENT;
                                                @endphp
                                                @include('student.services.orders.task-board.conversation_list_render')
                                            </div>
                                            <!-- Footer -->
                                            <div class="footer-chat-message-user file-input-wrapper">
                                                <form method="POST" class="ajax reset"
                                                      action="{{ route(getPrefix().'.service_orders.task-board.conversation.store', [$order->id, $orderTask->id]) }}"
                                                      data-handler="chatResponse">
                                                    @csrf
                                                    <!-- Attachment preview -->
                                                    <div class="files-area">
                                                        <span class="filesList">
                                                            <span class="files-names"></span>
                                                        </span>
                                                    </div>
                                                    <input type="hidden" value="{{ CONVERSATION_TYPE_CLIENT }}"
                                                           name="type">
                                                    <!-- input - buttons -->
                                                    <div class="footer-inputs d-flex justify-content-between">
                                                        <div class="message-user-send">
                                                            <input type="text" name="conversation_text"
                                                                   class="conversation-text"
                                                                   placeholder="{{ __('Type your message here') }}..."/>
                                                        </div>
                                                        <button type="button" class="atta-btn">
                                                            <label for="client-chat-attachment-{{$orderTask->id}}"><img
                                                                    src="{{ asset('assets/images/icon/file.svg') }}"
                                                                    alt=""/></label>
                                                            <input type="file" name="file[]" class="file-input"
                                                                   id="client-chat-attachment-{{$orderTask->id}}"
                                                                   style="visibility: hidden; position: absolute"
                                                                   />
                                                        </button>
                                                        <button class="send-btn" type="submit">
                                                            <img src="{{ asset('assets/images/icon/send.svg') }}"
                                                                 alt=""/>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-teamMessage" role="tabpanel"
                             aria-labelledby="nav-teamMessage-tab" tabindex="0">
                            <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-sm-20 p-10">
                                <div class="bd-ra-10 bg-body-bg overflow-hidden"
                                     data-background="{{asset("admin")}}/images/chat_bg.png">
                                    <div class="content-chat-message-user-wrap">
                                        <div class="content-chat-message-user">
                                            <!-- Body -->
                                            <div
                                                class="body-chat-message-user admin-team-chat taskBoard-modal-chatBody">
                                                @php
                                                    $type = CONVERSATION_TYPE_TEAM;
                                                @endphp
                                                @if(auth()->user()->role == USER_ROLE_STUDENT)
                                                    @include('student.services.orders.task-board.conversation_list_render')
                                                @else
                                                    @include('admin.services.orders.task-board.conversation_list_render')
                                                @endif
                                            </div>
                                            <!-- Footer -->
                                            <div class="footer-chat-message-user file-input-wrapper">
                                                <form method="POST" class="ajax reset"
                                                      action="{{ route('admin.service_orders.task-board.conversation.store', [$order->id, $orderTask->id]) }}"
                                                      data-handler="chatResponse">
                                                    @csrf
                                                    <!-- Attachment preview -->
                                                    <div class="files-area">
                                                        <span class="filesList">
                                                            <span class="files-names"></span>
                                                        </span>
                                                    </div>
                                                    <input type="hidden" value="{{ CONVERSATION_TYPE_TEAM }}"
                                                           name="type">
                                                    <!-- input - buttons -->
                                                    <div class="footer-inputs d-flex justify-content-between">
                                                        <div class="message-user-send">
                                                            <input type="text" name="conversation_text"
                                                                   class="conversation-text"
                                                                   placeholder="{{ __('Type your message here') }}..."/>
                                                        </div>
                                                        <button type="button" class="atta-btn">
                                                            <label for="team-chat-attachment-{{$orderTask->id}}"><img
                                                                    src="{{ asset('assets/images/icon/file.svg') }}"
                                                                    alt=""/></label>
                                                            <input type="file" name="file[]" class="file-input"
                                                                   id="team-chat-attachment-{{$orderTask->id}}"
                                                                   style="visibility: hidden; position: absolute"
                                                                   />
                                                        </button>
                                                        <button class="send-btn" type="submit">
                                                            <img src="{{ asset('assets/images/icon/send.svg') }}"
                                                                 alt=""/>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right -->
            <div class="col-lg-4 ps-lg-0">
                <div class="bd-l-one bd-c-stroke h-100">
                    <div class="bd-b-one bd-c-stroke p-16">
                        <p class="fs-18 fw-500 text-title-text pb-16">{{__('Progress')}}</p>
                        <div class="d-flex align-items-center g-10">
                            <div class="taskProgress-wrap flex-grow-1">
                                <input type="range"
                                       {{ auth()->user()->role != USER_ROLE_STUDENT ? '' : 'disabled' }} value="{{$orderTask->progress}}"
                                       min="0" data-task_id="{{$orderTask->id}}" step="1" class="taskProgress">
                            </div>
                            <span id="taskProgress-txt">{{$orderTask->progress}}%</span>
                        </div>
                    </div>
                    <div class="bd-b-one bd-c-stroke p-16">
                        <div class="d-flex align-items-center g-10 pb-16">
                            <p class="fs-18 fw-500 text-title-text">{{__('Properties')}}</p>
                            @if(auth()->user()->role != USER_ROLE_STUDENT)
                                <button type="button" class="border-0 bg-transparent text-title-text fs-14"
                                        onclick="openEditModal('{{ route('admin.service_orders.task-board.edit', [$orderTask->student_service_order_id, $orderTask->id]) }}')">
                                    <i
                                        class="fa-solid fa-pen"></i></button>
                            @endif
                        </div>
                        <!--  -->
                        <ul class="d-flex flex-column g-10">
                            <li>
                                <div class="row align-items-start">
                                    <div class="col-5"><p class="fs-14 text-para-text">{{__('Task Date')}}</p></div>
                                    @if($orderTask->start_date)
                                        <div class="col-7"><p
                                                class="fs-14 text-para-text">{{$orderTask->start_date}}</p></div>
                                    @endif
                                </div>
                            </li>
                            <li>
                                <div class="row align-items-start">
                                    <div class="col-5"><p class="fs-14 text-para-text">{{__('Due Date')}}</p></div>
                                    @if($orderTask->end_date)
                                        <div class="col-7"><p class="fs-14 text-para-text">{{$orderTask->end_date}}</p>
                                        </div>
                                    @endif
                                </div>
                            </li>
                            <li>
                                <div class="row align-items-start">
                                    <div class="col-5"><p class="fs-14 text-para-text">{{__('Priority')}}</p></div>
                                    <div class="col-7"><p
                                            class="fs-14 text-para-text">{{getPriority($orderTask->priority)}}</p></div>
                                </div>
                            </li>
                            <li>
                                <div class="row align-items-start">
                                    <div class="col-5"><p class="fs-14 text-para-text">{{__('Status')}}</p></div>
                                    <div class="col-7"><p
                                            class="fs-14 text-para-text">{{getOrderTaskStatus($orderTask->status)}}</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row align-items-center">
                                    <div class="col-5"><p class="fs-14 text-para-text">{{__('Assigned To')}}</p></div>
                                    <div class="col-7">
                                        @if(count($orderTask->assignees))
                                            <div class="taskProgressImage">
                                                @foreach($orderTask->assignees as $agent)
                                                    <img src="{{ getFileUrl($agent->user->image) }}"
                                                         alt="{{ $agent->user->name }}"/>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row align-items-start">
                                    <div class="col-5"><p class="fs-14 text-para-text">{{__('Label')}}</p></div>
                                    <div class="col-7">
                                        <div class="d-flex flex-wrap rg-10 cg-5">
                                            @php
                                                $colorClasses = [
                                                    ['bg-button', 'text-title-text'],
                                                    ['bg-brand-primary', 'text-white'],
                                                    ['bg-green', 'text-title-text']
                                                ];
                                                $colorCount = count($colorClasses);
                                            @endphp

                                            @if(count($orderTask->labels))
                                                <div class="d-flex flex-wrap rg-10 cg-5">
                                                    @foreach($orderTask->labels as $index => $label)
                                                        @php
                                                            $randomClass = $colorClasses[$index % $colorCount];
                                                        @endphp
                                                        <div class="py-6 px-10 bd-ra-2 {{ $randomClass[0] }}">
                                                            <h4 class="fs-13 fw-400 lh-13 {{ $randomClass[1] }}">{{ $label->name }}</h4>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row align-items-start">
                                    <div class="col-5"><p class="fs-14 text-para-text">{{__('Created')}}</p></div>
                                    <div class="col-7"><p
                                            class="fs-14 text-para-text">{{formatDate($orderTask->created_at, 'Y-m-d')}}</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="p-16">
                        <div class="d-flex align-items-center g-10 pb-16">
                            <h4 class="fs-18 fw-500 text-title-text">{{__('Attachments')}}</h4>
                            <span
                                class="w-16 h-16 rounded-circle bg-stroke fs-12 fw-500 text-title-text d-flex justify-content-center align-items-center">{{count($orderTask->attachments)}}</span>
                        </div>
                        <ul class="d-flex flex-column flex-wrap g-10">
                            @foreach($orderTask->attachments as $attachment)
                                <li class="bd-one bd-c-stroke bd-ra-6 p-10 d-flex align-items-center g-10">
                                    <div class="flex-grow-1 d-flex align-items-center g-10">
                                        <div class="img max-w-50 bd-one bd-c-stroke bd-ra-4">
                                            @if(in_array(getFileData($attachment->filemanager->id, 'extension'), ['jpg','png','jpeg','webp','JPG','PNG','JPEG','WEBP']))
                                                <img src="{{ getFileUrl($attachment->filemanager->id) }}" alt="">
                                            @else
                                                <img src="{{ asset('assets/images/icon/files-1.svg')}}" alt=""/>
                                            @endif
                                        </div>
                                        <div class="">
                                            <h4 class="fs-14 fw-400 lh-17 text-title-text">{{getFileData($attachment->filemanager->id, 'file_name')}}</h4>
                                            <p class="fs-12 fw-400 lh-15 text-para-text">
                                                {{getFileData($attachment->filemanager->id, 'size')}} B
                                                <span>.</span>
                                                <span>({{getFileData($attachment->filemanager->id, 'extension')}})</span>
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ getFileUrl($attachment->filemanager->id)  }}" target="_blank"
                                       class="text-black flex-shrink-0 border-0 bg-transparent d-flex justify-content-center align-items-center"><i
                                            class="fa-solid fa-circle-down"></i></a>
                                    <button
                                        onclick="deleteAttachment('{{ route('admin.service_orders.task-board.delete-attachment', [$orderTask->student_service_order_id, $orderTask->id, $attachment->file]) }}', '{{ route('admin.service_orders.task-board.view', [$orderTask->student_service_order_id, $orderTask->id]) }}')"
                                        class="flex-shrink-0 w-30 h-30 bd-one bd-c-stroke rounded-circle bg-transparent d-flex justify-content-center align-items-center">
                                        <i class="fa-solid fa-times"></i></button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
