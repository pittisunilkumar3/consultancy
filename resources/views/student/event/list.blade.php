@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <div class="search-one flex-grow-1 max-w-258">
            <input type="text" id="search-key" placeholder="Search here..."/>
            <button class="icon">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M8.71401 15.7857C12.6194 15.7857 15.7854 12.6197 15.7854 8.71428C15.7854 4.80884 12.6194 1.64285 8.71401 1.64285C4.80856 1.64285 1.64258 4.80884 1.64258 8.71428C1.64258 12.6197 4.80856 15.7857 8.71401 15.7857Z"
                        stroke="#707070" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M18.3574 18.3571L13.8574 13.8571" stroke="#707070" stroke-width="1.35902"
                          stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </button>
        </div>
    </div>
    <div class="p-sm-30 p-15" id="event-list-container">
        <!--  -->
        @include('student.event.partials.event-list')
    </div>

    <input type="hidden" id="my-event-route" value="{{ url()->current() }}">

@endsection
@push('script')
    <script src="{{asset('student/custom/js/event.js')}}"></script>
@endpush
