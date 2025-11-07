<div class="row rg-23">
    @forelse($service as $data)
        <div class="col-xl-3 col-md-4 col-sm-6">
            <div class="course-item-one">
                <div class="course-info">
                    <a href="{{ route(getPrefix().'.services.details',encodeId($data->id)) }}" class="img">
                        <img src="{{ getFileUrl($data->image) }}" alt="{{ $data->title }}"/>
                    </a>
                    <div class="content">
                        <a href="{{ route('admin.services.details',encodeId($data->id)) }}" class="title">{{ $data->title }}</a>
                        <p class="fs-15 fw-500 lh-20 text-para-text">{{ __('Price') }}: {{ showPrice($data->price) }}</p>
                        @if(auth()->user()->role != USER_ROLE_STUDENT)
                            <p class="fs-15 fw-500 lh-20 text-para-text">
                                {{ __('Status') }}:
                                @if($data->status == STATUS_ACTIVE)
                                    {{ __('Active') }}
                                @elseif($data->status == STATUS_DEACTIVATE)
                                    {{ __('Inactive') }}
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
                <div class="course-action">
                    <a href="{{ route(getPrefix().'.services.details',encodeId($data->id)) }}" class="link">{{ __('View Details') }}</a>
                    @if(auth()->user()->role != USER_ROLE_STUDENT)
                        <div class="d-flex align-items-center g-10 justify-content-end">
                            <a href="{{ route('admin.services.edit',encodeId($data->id)) }}"
                               class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25"
                               title="{{ __('Edit') }}">
                                @include('partials.icons.edit')
                            </a>
                            <button type="button"
                                    onclick="deleteItem('{{ route('admin.services.delete', encodeId($data->id)) }}')"
                                    class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25"
                                    title="{{ __('Delete') }}">
                                @include('partials.icons.delete')
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-xl-12">
            <div class="p-lg-50 p-md-30 p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white">
                <span class="course-info">{{ __('No data found') }}</span>
            </div>
        </div>
    @endforelse
</div>

{{ $service->links('layouts.partial.common_pagination_with_count') }}
