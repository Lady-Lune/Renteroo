@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="pagination-nav">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">‹</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
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
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">›</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">›</span>
                </li>
            @endif
        </ul>
    </nav>
@endif

<style>
.pagination-nav .pagination {
    margin: 0;
    justify-content: center;
    font-size: 0.875rem;
}

.pagination-nav .pagination .page-link {
    color: #667eea;
    border: 1px solid #dee2e6;
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
    line-height: 1.25;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    min-width: 2.5rem;
}

.pagination-nav .pagination .page-link:hover {
    color: white;
    background-color: #667eea;
    border-color: #667eea;
}

.pagination-nav .pagination .page-item.active .page-link {
    background-color: #667eea;
    border-color: #667eea;
    color: white;
}

.pagination-nav .pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
}
</style>