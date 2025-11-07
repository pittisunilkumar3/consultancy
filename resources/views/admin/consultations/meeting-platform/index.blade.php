@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <!-- Content -->
    <div class="p-sm-30 p-15">
        <!--  -->
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white">
            <div class="bd-c-stroke bd-one bd-ra-5 bg-light p-10 p-sm-25">
                <div class="alert bg-tertiary mb-25">
                    <p>{{__('Configure your online and offline meeting platforms to ensure optimal service. Please enable at least one platform (Zoom or Google Meet) for use the virtual meeting.')}}</p>
                </div>
                <div class="row rg-20">
                    @foreach($platforms as $platform)
                        <div class="col-lg-4 col-sm-6">
                            <div
                                class="bd-c-stroke-2 bd-one bd-ra-8 bg-white d-flex flex-wrap g-10 h-100 justify-content-between p-15">
                                <!-- Left -->
                                <div class="">
                                    <div class="d-flex align-items-center cg-10 pb-6">
                                        <img src="{{asset($platform->image)}}" alt="{{$platform->name}}"
                                             class="max-w-23"/>
                                        <p class="fs-14 fw-500 lh-24 text-para-text">{{$platform->name}}</p>
                                    </div>
                                    {!! getStatusHtml($platform->status)!!}
                                </div>
                                <!-- Right -->
                                <div class="d-flex g-5">
                                    <button
                                        class="flex-shrink-0 border-0 p-0 w-20 h-20 rounded-circle d-flex justify-content-center align-items-center bg-transparent"
                                        onclick="getEditModal('{{route('admin.consultations.meeting_platforms.edit', [$platform->id])}}', '#edit-modal')">
                                        <img src="{{asset('assets/images/icon/settings.svg')}}" alt=""
                                             class="max-w-10"/>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
            </div>
        </div>
    </div>
@endsection
