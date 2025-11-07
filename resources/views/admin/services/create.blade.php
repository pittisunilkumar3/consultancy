@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{$pageTitle}}</h4>
        <a href="{{route('admin.services.index')}}"
           class="flipBtn sf-flipBtn-secondary flex-shrink-0">{{__('Back')}}</a>
    </div>
    <div class="p-sm-30 p-15">
        <div class="row rg-20">
            <form class="ajax reset" action="{{ route('admin.services.store') }}" method="post"
                  data-handler="commonResponseRedirect"
                  data-redirect-url="{{route('admin.services.index')}}">
                @csrf
                @include('admin.services.form')
                <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
                    <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">{{__('Update')}}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{asset('admin/custom/js/services.js')}}"></script>
@endpush
