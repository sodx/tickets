@if ($paginator->hasPages())
    <nav class="pagination-nav">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @php
                        $elementOld = $element;
                        $currentPage = $paginator->currentPage();
                            if(count($elementOld) > 4) {
                                if($currentPage > 4 && $currentPage <= count($elementOld) - 3) {
                                    $element = array_slice($elementOld, 0, 1, true)
                                    + ['el1' => '...']
                                    + array_slice($elementOld, $currentPage - 3, 5, true)
                                    + ['el2' => '...']
                                    + array_slice($elementOld, -1, 1, true);
                                } elseif ($currentPage > count($elementOld) - 3) {
                                    $element = array_slice($elementOld, 0, 1, true)
                                    + ['el1' => '...']
                                    + array_slice($elementOld, -5, 5, true);
                                } else {
                                    $element = array_slice($elementOld, 0, 5, true)
                                    + ['el1' => '...']
                                    + array_slice($elementOld, -1, 1, true);
                                }
                            }
                            $sort = '';
                            $perPage = 20;
                            $date = '';
                            $date_to = '';
                            if(request()->query('sort')) {
                                $sort = request()->query('sort');
                            }
                            if(request()->query('per_page')) {
                                $perPage = request()->query('per_page');
                            }
                            if(request()->query('date')) {
                                $date = request()->query('date');
                            }
                            if(request()->query('date_to')) {
                                $date_to = request()->query('date_to');
                            }
                    @endphp
                    @foreach ($element as $page => $url)
                        @if($page == 1)
                            <li><a href="{{ url(request()->fullUrlWithoutQuery(['sort', 'per_page', 'date', 'date_to', 'page'])) }}">{{ $page }}</a></li>
                        @elseif ($page == $paginator->currentPage())
                            <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                        @elseif($page == 'el1' || $page == 'el2')
                            <li><a href="#">...</a></li>
                        @else
                            <li><a href="{{ url($url) . '&' . http_build_query(['sort' => $sort, 'per_page' => $perPage, 'date' => $date, 'date_to' => $date_to]) }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
