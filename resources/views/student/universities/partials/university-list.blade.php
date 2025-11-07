@if($universityData->count() > 0)
    <div class="row rg-15">
        @foreach($universityData as $university)
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="course-item-two">
                    <a href="{{ route('universities.details', $university->slug) }}" class="img" target="_blank">
                        <img src="{{getFileUrl($university->thumbnail_image)}}" 
                             alt="{{$university->name}}" style="height: 180px; object-fit: cover; width: 100%;"/>
                    </a>
                    <div class="course-content">
                        <div class="text-content">
                            <a href="{{ route('universities.details', $university->slug) }}" 
                               class="title" target="_blank">{{$university->name}}</a>
                            @if($university->country)
                                <p class="author">{{$university->country->name}}</p>
                            @endif
                        </div>
                        <ul class="list zList-pb-6">
                            @if($university->world_ranking)
                                <li class="item">
                                    <div class="icon d-flex">
                                        <img src="{{asset('assets/images/icon/world-ranking.svg')}}" alt=""/>
                                    </div>
                                    <p class="text">{{__('World Ranking')}}: {{$university->world_ranking}}</p>
                                </li>
                            @endif
                            @if($university->international_student)
                                <li class="item">
                                    <div class="icon d-flex">
                                        <img src="{{asset('assets/images/icon/international-students.svg')}}" alt=""/>
                                    </div>
                                    <p class="text">{{__('International Students')}}: {{$university->international_student}}</p>
                                </li>
                            @endif
                        </ul>
                        <a href="{{route('universities.details', $university->slug)}}" 
                           class="link" target="_blank">{{__('View Details')}}<i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $universityData->links('layouts.partial.common_pagination_with_count') }}
    </div>
@else
    <div class="alert alert-info">
        {{ __('No universities found matching your search criteria.') }}
    </div>
@endif
