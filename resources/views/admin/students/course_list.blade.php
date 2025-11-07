@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="p-sm-30 p-15">
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
            <div class="">
                <div class="tableFilter-wrap">
                    <div class="item">
                        <label for="search_key" class="zForm-label-alt">{{__('Search')}}</label>
                        <input type="text" name="search_key" class="form-control zForm-control-alt" id="search_key"
                               placeholder="{{__('Search by title,price,duration')}}"/>
                    </div>
                    <div class="item">
                        <label for="program_id" class="zForm-label-alt">{{__('Program')}}</label>
                        <select class="sf-select-two" name="program_id" id="program_id">
                            <option value="">{{__('Select program')}}</option>
                            @foreach($programs as $program)
                                <option value="{{$program->id}}">{{$program->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filterBtn">
                        <button class="sf-btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20"
                                 fill="none">
                                <ellipse cx="9.26461" cy="8.99077" rx="6.7712" ry="6.69878" stroke="#121D35"
                                         stroke-width="1.5" stroke-linecap="square"/>
                                <path d="M13.8879 13.9236L17.7235 17.7083" stroke="#121D35" stroke-width="1.5"
                                      stroke-linecap="square"/>
                            </svg>
                            <span>{{__('Filter')}}</span>
                        </button>
                    </div>
                </div>
                <table class="table zTable zTable-last-item-right pt-15" id="myCourseDataTable">
                    <thead>
                    <tr>
                        <th>
                            <div>{{__('Thumbnail')}}</div>
                        </th>
                        <th>
                            <div>{{__('Title')}}</div>
                        </th>
                        <th>
                            <div>{{__('Program')}}</div>
                        </th>
                        <th>
                            <div class="text-nowrap">{{__('Duration (Days)')}}</div>
                        </th>
                        <th>
                            <div class="text-nowrap">{{__('Start Date')}}</div>
                        </th>
                        <th>
                            <div class="text-nowrap">{{__('Status)')}}</div>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
    <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">

            </div>
        </div>
    </div>
    <input type="hidden" id="my-course-route" value="{{ route('admin.students.courses', encodeId($user->id)) }}">

@endsection
@push('script')
    <script src="{{asset('admin/custom/js/student_courses.js')}}"></script>
@endpush
