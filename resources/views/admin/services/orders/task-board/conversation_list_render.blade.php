@if($conversationTeamTypeData != null && count($conversationTeamTypeData) >0 )
    @foreach($conversationTeamTypeData as $data)
        @if($data->user_id == auth()->id())
            <div class="message-user-right">
                @if($data->attachment !=null && count(json_decode($data->attachment)) > 0 && auth()->user()->role != USER_ROLE_STUDENT)
                    @foreach(json_decode($data->attachment) as $file)
                        <button class="message-file-save rounded-5 sf-btn-brand" title="{{__('Save the file')}}" onclick="getFileSaveModal({{$file}})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                <path d="M1.5 8.9904V6.35375C1.5 4.53708 1.5 3.62874 2.04918 3.06437C2.59835 2.5 3.48223 2.5 5.25 2.5C7.01775 2.5 7.90165 2.5 8.45085 3.06437C9 3.62874 9 4.53708 9 6.35375V8.9904C9 10.1434 9 10.7198 8.63775 10.9262C7.9362 11.3257 6.62025 9.9926 5.9953 9.5912C5.63285 9.3584 5.45165 9.242 5.25 9.242C5.04835 9.242 4.86713 9.3584 4.50469 9.5912C3.87975 9.9926 2.56381 11.3257 1.86227 10.9262C1.5 10.7198 1.5 10.1434 1.5 8.9904Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M4.5 1H5.5C7.857 1 9.03555 1 9.76775 1.73223C10.5 2.46446 10.5 3.64297 10.5 6V9" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    @endforeach
                @endif
                <div class="message-user-right-text">
                    <div class="text">
                        <p>{{$data->conversation_text}}</p>
                        @if($data->attachment !=null && count(json_decode($data->attachment)) > 0)
                            <ul class="d-flex flex-wrap g-10">
                                @foreach(json_decode($data->attachment) as $file)
                                    @if(in_array(getFileData($file, 'extension'), ['jpg','png','jpeg','webp']))
                                        <li>
                                            <div
                                                class="sf-popup-gallery">
                                                <a target="_blank" href="{{ getFileUrl($file) }}">
                                                    <img
                                                        src="{{ getFileUrl($file)  }}"
                                                        alt=""/>
                                                </a>
                                            </div>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{ getFileUrl($file)  }}"
                                               target="_blank"
                                               class="p-10 bd-one bd-c-stroke bd-ra-10 bg-body-bg d-inline-flex flex-column g-13">
                                                <div class="d-flex"><img
                                                        src="{{ asset('assets/images/icon/files-1.svg')}}"
                                                        alt=""/></div>
                                                <p class="fs-14 fw-400 lh-17 text-title-black">{{getFileData($file, 'file_name')}}</p>
                                                <div
                                                    class="d-flex align-items-center">
                                                    <!-- File size -->
                                                    <h4 class="fs-12 fw-400 lh-15 text-para-text pr-8 bd-r-one bd-c-stroke">{{getFileData($file, 'size')}}
                                                        B</h4>
                                                    <h4 class="fs-12 fw-400 lh-15 text-para-text pl-8">{{__("File")}}</h4>
                                                </div>
                                            </a>
                                        </li>
                                    @endif

                                @endforeach
                            </ul>
                        @endif
                        <div class="time-read">
                            <span class="time">{{$data->user->name}}</span>
                            <span
                                class="time fst-italic">{{date('d M Y, H:i', strtotime($data->created_at))}}</span>
                        </div>
                    </div>
                </div>
                <div class="message-user-img"><img src="{{ getFileUrl($data->user->image) }}" alt=""></div>
            </div>
        @else
            <div class="message-user-left">
                <div class="message-user-img"><img src="{{ getFileUrl($data->user->image) }}" alt=""></div>
                <div class="message-user-left-text">
                    <div class="text">
                        <p>{{$data->conversation_text}}</p>
                        @if($data->attachment !=null && count(json_decode($data->attachment)) > 0)
                            <ul class="d-flex flex-wrap g-10">
                                @foreach(json_decode($data->attachment) as $file)
                                    @if(in_array(getFileData($file, 'extension'), ['jpg','png','jpeg','webp']))
                                        <li>
                                            <div
                                                class="sf-popup-gallery">
                                                <a href="{{ getFileUrl($file) }}">
                                                    <img
                                                        src="{{ getFileUrl($file)  }}"
                                                        alt=""/>
                                                </a>
                                            </div>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{ getFileUrl($file)  }}"
                                               target="_blank"
                                               class="p-10 bd-one bd-c-stroke bd-ra-10 bg-body-bg d-inline-flex flex-column g-13">
                                                <div class="d-flex"><img
                                                        src="{{ asset('assets/images/icon/files-1.svg')}}"
                                                        alt=""/></div>
                                                <p class="fs-14 fw-400 lh-17 text-title-black">{{getFileData($file, 'file_name')}}</p>
                                                <div
                                                    class="d-flex align-items-center">
                                                    <!-- File size -->
                                                    <h4 class="fs-12 fw-400 lh-15 text-para-text pr-8 bd-r-one bd-c-stroke">{{getFileData($file, 'size')}}
                                                        B</h4>
                                                    <h4 class="fs-12 fw-400 lh-15 text-para-text pl-8">{{__("File")}}</h4>
                                                </div>
                                            </a>
                                        </li>
                                    @endif

                                @endforeach
                            </ul>
                        @endif
                        <div class="time-read">
                            <span class="time">{{$data->user->name}}</span>
                            <span class="time fst-italic">{{date('d M Y, H:i', strtotime($data->created_at))}}</span>
                        </div>
                    </div>
                </div>
                @if($data->attachment !=null && count(json_decode($data->attachment)) > 0 && auth()->user()->role != USER_ROLE_STUDENT)
                    @foreach(json_decode($data->attachment) as $file)
                        <button class="message-file-save rounded-5 sf-btn-brand" title="{{__('Save the file')}}" onclick="getFileSaveModal({{$file}})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                <path d="M1.5 8.9904V6.35375C1.5 4.53708 1.5 3.62874 2.04918 3.06437C2.59835 2.5 3.48223 2.5 5.25 2.5C7.01775 2.5 7.90165 2.5 8.45085 3.06437C9 3.62874 9 4.53708 9 6.35375V8.9904C9 10.1434 9 10.7198 8.63775 10.9262C7.9362 11.3257 6.62025 9.9926 5.9953 9.5912C5.63285 9.3584 5.45165 9.242 5.25 9.242C5.04835 9.242 4.86713 9.3584 4.50469 9.5912C3.87975 9.9926 2.56381 11.3257 1.86227 10.9262C1.5 10.7198 1.5 10.1434 1.5 8.9904Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M4.5 1H5.5C7.857 1 9.03555 1 9.76775 1.73223C10.5 2.46446 10.5 3.64297 10.5 6V9" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    @endforeach
                @endif
            </div>
        @endif
    @endforeach
@else
    <div class="no-chat">
        <div class="img">
            <img src="{{asset("assets/images/chat_no_image.png")}}" alt="">
        </div>
        <p>{{__("No Message, yet")}}</p>
    </div>
@endif

