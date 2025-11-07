@if ($paginator->hasPages())
    <div class="pt-30">
        <div class="align-items-center justify-content-between row">
            <div class="col-md-4">
                <p>
                    {{__('Showing')}}
                    {{ ($paginator->currentPage() - 1) * $paginator->perPage() + 1 }}
                    -
                    {{ min($paginator->currentPage() * $paginator->perPage(), $paginator->total()) }}
                    {{__('of')}}
                    {{ $paginator->total() }} {{__('Items')}}
                </p>
            </div>
            <div class="col-md-8">
                <ul class="zPaginatation-list justify-content-end">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li>
                            <a href="#" class="item bd-c-stroke-2 disabled"><i class="fa-solid fa-angle-left"></i></a>
                        </li>
                    @else
                        <li>
                            <a href="{{ $paginator->previousPageUrl() }}" class="item bd-c-stroke-2"><i
                                    class="fa-solid fa-angle-left"></i></a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li><a href="#" class="item bd-c-stroke-2 disabled">{{ $element }}</a></li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li><a href="#" class="item bd-c-stroke-2 active">{{ $page }}</a></li>
                                @else
                                    <li><a href="{{ $url }}" class="item bd-c-stroke-2">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li>
                            <a href="{{ $paginator->nextPageUrl() }}" class="item bd-c-stroke-2"><i
                                    class="fa-solid fa-angle-right"></i></a>
                        </li>
                    @else
                        <li>
                            <a href="#" class="item bd-c-stroke-2 disabled"><i class="fa-solid fa-angle-right"></i></a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endif

