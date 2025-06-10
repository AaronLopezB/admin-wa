
@if ($paginator->hasPages())
<nav aria-label="Page navigation example">
<ul class="pagination justify-content-end pagination-dark pagin-border-dark gap-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <button class="page-link rounded-circle"  aria-label="Previous">
                    <span aria-hidden="true">«</span>
                    <span class="sr-only">Previous</span>
                </button>
            </li>

            {{-- <li class="page-item page-indicator disabled">
                <a class="page-link"href="javascript:void(0)">
                    <i class="la la-angle-left"></i></a>
            </li> --}}
        @else
            <li class="page-item">
                <button class="page-link rounded-circle" wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" aria-label="Previous">
                    <span aria-hidden="true">«</span>
                    <span class="sr-only">Previous</span>
                </button>
            </li>
            {{-- <li class="page-item page-indicator">
                <a class="page-link" wire:click="previousPage" wire:loading.attr="disabled" aria-label="Previous">
                    <i class="la la-angle-left"></i></a>
            </li> --}}
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                {{-- <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li> --}}
                <li class="page-item disabled">
                    <button class="page-link rounded-circle">{{ $element }}</button>
                </li>
                {{-- <li class="page-item disabled"><span class="page-link" >{{ $element }}</span></li> --}}
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        {{-- <li class="page-item active"><span class="page-link">{{ $page }}</span></li> --}}
                        {{-- <li class="page-item active"><span class="page-link">{{ $page }}</span></li> --}}
                        <li class="page-item active" wire:key="paginator-{{ $paginator->getPageName() }}-page-{{ $page }}">
                            <button class="page-link rounded-circle" >{{ $page }}</button>
                        </li>
                    @else
                        {{-- <li class="page-item"><button class="page-link" wire:click="gotoPage({{ $page }})"
                                wire:loading.attr="disabled">{{ $page }}</button></li> --}}

                        <li class="page-item" wire:key="paginator-{{ $paginator->getPageName() }}-page-{{ $page }}">
                            <button class="page-link rounded-circle" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                wire:loading.attr="disabled">{{ $page }}</button>
                        </li>
                        {{-- <li class="page-item"><button class="page-link" wire:click="gotoPage({{ $page }})"
                                wire:loading.attr="disabled">{{ $page }}</button></li> --}}
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            {{-- <li class="page-item">
                <button class="page-link" wire:click="nextPage" wire:loading.attr="disabled" aria-label="Next">
                    <span aria-hidden="true">»</span><span class="sr-only">Next</span>
                </button>
            </li> --}}
            {{-- <li class="page-item page-indicator">
                <a class="page-link" wire:click="nextPage" wire:loading.attr="disabled" aria-label="Next">
                    <i class="la la-angle-right"></i></a>
            </li> --}}

            <li class="page-item">
                <button class="page-link rounded-circle" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" aria-label="Next">
                    <span aria-hidden="true">»</span>
                    <span class="sr-only">Next</span>
                </button>
            </li>
        @else
            {{-- <li class="page-item page-indicator disabled">
                <span class="page-link" aria-label="Next">
                    <i class="la la-angle-right"></i>
                </span>
            </li> --}}

            <li class="page-item disabled">
                <button class="page-link rounded-circle" aria-label="Next">
                    <span aria-hidden="true">»</span>
                    <span class="sr-only">Next</span>
                </button>
            </li>
        @endif
    </ul>
    </nav>
@endif
