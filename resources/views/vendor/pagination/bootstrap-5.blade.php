@if ($paginator->hasPages())
    <div class="dataTables_paginate paging_simple_numbers" id="custom-laravel-paginate">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <a class="paginate_button previous disabled" aria-disabled="true" tabindex="-1" role="link">&lt;</a>
        @else
            <a class="paginate_button previous" href="{{ $paginator->previousPageUrl() }}" rel="prev" role="link">&lt;</a>
        @endif

        <span>
            {{-- Pagination Elements --}}
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
            @endphp

            {{-- Show first page --}}
            @if ($currentPage > 2)
                <a class="paginate_button" href="{{ $paginator->url(1) }}" role="link">1</a>
                @if ($currentPage > 3)
                    <a class="paginate_button disabled" tabindex="-1" role="link">...</a>
                @endif
            @endif

            {{-- Show current, one before and after --}}
            @for ($i = max(1, $currentPage - 1); $i <= min($lastPage, $currentPage + 1); $i++)
                @if ($i == $currentPage)
                    <a class="paginate_button current" aria-current="page" role="link">{{ $i }}</a>
                @else
                    <a class="paginate_button" href="{{ $paginator->url($i) }}" role="link">{{ $i }}</a>
                @endif
            @endfor

            {{-- Show last page --}}
            @if ($currentPage < $lastPage - 1)
                @if ($currentPage < $lastPage - 2)
                    <a class="paginate_button disabled" tabindex="-1" role="link">...</a>
                @endif
                <a class="paginate_button" href="{{ $paginator->url($lastPage) }}" role="link">{{ $lastPage }}</a>
            @endif
        </span>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="paginate_button next" href="{{ $paginator->nextPageUrl() }}" rel="next" role="link">&gt;</a>
        @else
            <a class="paginate_button next disabled" aria-disabled="true" tabindex="-1" role="link">&gt;</a>
        @endif
    </div>
@endif
