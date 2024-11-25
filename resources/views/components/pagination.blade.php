<nav>
    <ul class="pagination {{ $size ?? '' }}">
        <li class="{{ $currentPage == 1 ? 'disabled' : '' }}">
            <a href="{{ $currentPage == 1 ? '#' : $url . ($currentPage - 1) }}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        @for ($i = 1; $i <= $totalPages; $i++)
            <li class="{{ $currentPage == $i ? 'active' : '' }}">
                <a href="{{ $url . $i }}">{{ $i }}</a>
            </li>
        @endfor
        <li class="{{ $currentPage == $totalPages ? 'disabled' : '' }}">
            <a href="{{ $currentPage == $totalPages ? '#' : $url . ($currentPage + 1) }}" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
