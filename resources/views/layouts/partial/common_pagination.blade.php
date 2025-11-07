
@if ($paginator->hasPages())
    <div class="pt-20 pt-sm-45">
        <ul class="zPaginatation-list justify-content-start">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <a href="#" class="item disabled"><i class="fa-solid fa-angle-left"></i></a>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" class="item"><i class="fa-solid fa-angle-left"></i></a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li><a href="#" class="item disabled">{{ $element }}</a></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><a href="#" class="item active">{{ $page }}</a></li>
                        @else
                            <li><a href="{{ $url }}" class="item">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" class="item"><i class="fa-solid fa-angle-right"></i></a>
                </li>
            @else
                <li>
                    <a href="#" class="item disabled"><i class="fa-solid fa-angle-right"></i></a>
                </li>
            @endif
        </ul>
    </div>
@endif

