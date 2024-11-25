<div class="card card-contact-list">
    <div class="agileinfo-cdr">
        <div class="card-header">
            <h3>{{ $title ?? 'Contacts' }}</h3>
        </div>
        <hr class="widget-separator">
        <div class="card-body p-b-20">
            <div class="list-group">
                @foreach($contacts as $contact)
                    <a class="list-group-item media" href="#">
                        <div class="pull-left">
                            <img class="lg-item-img" src="{{ $contact['avatar'] }}" alt="">
                        </div>
                        <div class="media-body">
                            <div class="pull-left">
                                <div class="lg-item-heading">{{ $contact['name'] }}</div>
                                <small class="lg-item-text">{{ $contact['email'] }}</small>
                            </div>
                            <div class="pull-right">
                                <div class="lg-item-heading">{{ $contact['position'] }}</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
