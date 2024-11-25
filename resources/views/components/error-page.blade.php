<div class="error-page">
    <div class="">
        <h2>{{ $errorCode }}</h2>
    </div>
    <div class="">
        <h3><i class="fa fa-warning text-yellow"></i> {{ $errorMessage }}</h3>
        <form action="{{ $searchAction ?? '#' }}" method="post" class="search-form">
            @csrf
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search" required="">
                <div class="input-group-btn">
                    <button type="submit" name="submit" class="btn btn-warning btn-flat"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <p>{{ $errorDescription }}</p>
</div>
