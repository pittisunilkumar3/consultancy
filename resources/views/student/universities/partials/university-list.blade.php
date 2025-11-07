<div class="row rg-15">
    @forelse($universities as $university)
        <div class="col-xl-3 col-md-4 col-sm-6">
            <div class="course-item-one">
                <div class="course-info">
                    <div class="img">
                        <img src="{{getFileUrl($university->logo)}}" alt="{{$university->name}}" style="height: 200px; object-fit: cover; width: 100%;"/>
                    </div>
                    <div class="content">
                        <h5 class="title mb-2">{{$university->name}}</h5>
                        @if($university->country)
                            <p class="fs-15 fw-500 lh-20 text-para-text pb-10">
                                <i class="fas fa-map-marker-alt"></i> {{$university->country->name}}
                            </p>
                        @endif
                        @if($university->address)
                            <p class="fs-14 fw-400 lh-18 text-muted pb-10">{{Str::limit($university->address, 50)}}</p>
                        @endif
                    </div>
                </div>
                <div class="course-action">
                    <a href="{{ route('universities.details', $university->slug) }}" class="link" target="_blank">{{ __('View Details') }}</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-xl-12">
            <div class="p-lg-50 p-md-30 p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white">
                <span class="course-info">{{__('No universities found')}}</span>
            </div>
        </div>
    @endforelse
</div>

{{ $universities->links('layouts.partial.common_pagination_with_count') }}
