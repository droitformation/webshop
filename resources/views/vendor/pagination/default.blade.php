@if ($paginator->hasPages())

    <?php $dots = 0; ?>

    <ul class="pagination">
        <!-- Previous Page Link -->
        @if ($paginator->onFirstPage())
            <li class="disabled"><span>&laquo;</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
        @endif

        <!-- Pagination Elements -->
        @foreach ($elements as $element)
            <!-- "Three Dots" Separator -->

            @if (is_string($element))
                <?php $dots++; ?>
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif

            <!-- Array Of Links -->
            @if (is_array($element))

                <!-- Tens before current page -->
                <?php $before = range_before_current_page($paginator->currentPage()); ?>
                @if($dots == 1 && !empty($before))
                    @foreach($before as $dix)
                        <li><a href="{{ $paginator->url($dix) }}">{{ $dix }}</a></li>
                    @endforeach
                @endif

                <!-- Tens after current page -->
                <?php $after = range_after_current_page($paginator->currentPage(),$paginator->lastPage()); ?>
                @if($dots > 1 && !empty($after))
                    @foreach($after as $dix)
                        <li><a href="{{ $paginator->url($dix) }}">{{ $dix }}</a></li>
                    @endforeach
                @endif

                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach

            @endif
        @endforeach

        <!-- Next Page Link -->
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
        @else
            <li class="disabled"><span>&raquo;</span></li>
        @endif
    </ul>
@endif
