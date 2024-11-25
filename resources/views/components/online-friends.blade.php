<div class="chat-grid widget-shadow">
    <ul>
        <li class="head">Friends (Online)</li>
        @foreach($friends as $friend)
            <li>
                <a href="#">
                    <div class="chat-left">
                        <img class="img-circle" src="{{ $friend['avatar'] }}" alt="{{ $friend['name'] }}">
                        <label class="small-badge {{ $friend['status'] == 'online' ? 'bg-green' : '' }}"></label>
                    </div>
                    <div class="chat-right">
                        <p>{{ $friend['name'] }}</p>
                        <h6>{{ $friend['message'] }}</h6>
                    </div>
                    <div class="clearfix"></div>
                </a>
            </li>
        @endforeach
    </ul>
</div>

{{-- used:
@php
$friends = [
    ['name' => 'Andrew Josifn', 'avatar' => 'images/i1.png', 'status' => 'online', 'message' => 'Nullam quis risus eget'],
    ['name' => 'Justen Ferry', 'avatar' => 'images/i4.png', 'status' => 'online', 'message' => 'Urna mollis ornare vel'],
    // ... autres amis
];
@endphp

<x-online-friends :friends="$friends" /> --}}
