<div class="header-left">
    <div class="menu-icon bi bi-list"></div>
    <div
        class="search-toggle-icon bi bi-search"
        data-toggle="header_search"
    ></div>
    <div class="header-search">
        <form class="card-body" action="{{ route('search') }}" method="GET" role="search">
            {{ csrf_field() }}
            <div class="form-group mb-0 input-group">
                <i class="dw dw-search2 search-icon"></i>
                <input type="text" class="form-control" placeholder="Rechercher..." name="q">
                <span class="input-group-btn">
            <button class="btn btn-secondary" type="submit">Go!</button>
          </span>
            </div>
        </form>
        {{-- <form action="{{ route('search') }}" method="GET" id="search-form">
            <div class="form-group mb-0">
                <i class="dw dw-search2 search-icon"></i>
                <input
                    type="text"
                    class="form-control search-input"
                    name="query"
                    placeholder="Search Here"
                />
            </div> --}}
        </form>
    </div>
</div>
