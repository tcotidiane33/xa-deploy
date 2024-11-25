<div class="card">
    <div class="card-body">
        <div class="agileinfo-cdr">
            <div class="card-header">
                <h3>{{ $title ?? 'Report' }}</h3>
            </div>
            <hr class="widget-separator">
            <div class="widget-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            @foreach($headers as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $row)
                            <tr>
                                @foreach($row as $cell)
                                    <td>{{ $cell }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
