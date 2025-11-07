<div class="row rg-15">
    @forelse($myEvent as $payment)
        <div class="col-xl-3 col-md-4 col-sm-6">
            <div class="course-item-one">
                <div class="course-info">
                    <a href="{{ route('student.event.details', encodeId($payment->id)) }}" class="img">
                        <img src="{{getFileUrl($payment->itemImage)}}" alt="{{$payment->itemTitle}}"/>
                    </a>
                    <div class="content">
                        <a href="{{ route('student.event.details', encodeId($payment->id)) }}" class="title">{{$payment->itemTitle}}</a>
                        @if($payment->itemType == EVENT_TYPE_PHYSICAL)
                            <p class="fs-15 fw-500 lh-20 text-para-text pb-10">{{__('Type')}} : {{__('Physical')}}</p>
                        @elseif($payment->itemType == EVENT_TYPE_VIRTUAL)
                            <p class="fs-15 fw-500 lh-20 text-para-text pb-10">{{__('Type')}} : {{__('Virtual')}}</p>
                        @endif
                    </div>
                    <p class="fs-15 fw-500 lh-20 text-para-text pb-10">{{__('Price')}} : {{ showPrice($payment->itemPrice) }}</p>
                    <p class="fs-15 fw-500 lh-20 text-para-text">{{__('Date & Time')}} : {{ \Carbon\Carbon::parse($payment->itemDate)->format('Y-m-d , h:i:s') }}</p>
                </div>
                <div class="course-action">
                    <a href="{{ route('student.event.details', encodeId($payment->id)) }}" class="link">{{ __('View Details') }}</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-xl-12">
            <div class="p-lg-50 p-md-30 p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white">
                <span class="course-info">{{__('No event found')}}</span>
            </div>
        </div>
    @endforelse
</div>

{{ $myEvent->links('layouts.partial.common_pagination_with_count') }}
