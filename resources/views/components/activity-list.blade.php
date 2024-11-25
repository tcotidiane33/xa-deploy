<div class="card">
    <div class="card-body card-padding">
        <div class="">
            <header class="widget-header">
                <h4 class="widget-title">{{ $title ?? 'Activities' }}</h4>
            </header>
            <hr class="widget-separator">
            <div class="widget-body">
                <div class="streamline">
                    @foreach($activities as $activity)
                        <div class="sl-item sl-{{ $activity['type'] ?? 'primary' }}">
                            <div class="sl-content">
                                <small class="text-muted">{{ $activity['time'] }}</small>
                                <p>{{ $activity['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
