@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="pagination-nav">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">
                        <svg class="icon-svg" viewBox="0 0 24 24" style="width:16px;height:16px;"><polyline points="15 18 9 12 15 6"></polyline></svg>
                        <span class="d-none-sm">Sebelumnya</span>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                        <svg class="icon-svg" viewBox="0 0 24 24" style="width:16px;height:16px;"><polyline points="15 18 9 12 15 6"></polyline></svg>
                        <span class="d-none-sm">Sebelumnya</span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link dots">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                        <span class="d-none-sm">Selanjutnya</span>
                        <svg class="icon-svg" viewBox="0 0 24 24" style="width:16px;height:16px;"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">
                        <span class="d-none-sm">Selanjutnya</span>
                        <svg class="icon-svg" viewBox="0 0 24 24" style="width:16px;height:16px;"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
