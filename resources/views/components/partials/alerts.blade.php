@if(isset($alerts) && count($alerts) > 0)
    <div class="alert alert-warning" role="alert">
        <h4 class="alert-heading">Alertes de performance</h4>
        <ul>
            @foreach($alerts as $alert)
                <li>{{ $alert['message'] }}</li>
            @endforeach
        </ul>
    </div>
@endif