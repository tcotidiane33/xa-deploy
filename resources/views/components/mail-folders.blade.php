<div class="folder widget-shadow">
    <ul>
        <li class="head">Folders</li>
        @foreach($folders as $folder)
            <li>
                <a href="{{ $folder['url'] }}">
                    <i class="fa fa-{{ $folder['icon'] }}"></i>
                    {{ $folder['name'] }}
                    @if(isset($folder['count']))
                        <span>{{ $folder['count'] }}</span>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</div>


{{-- used:
@php
$folders = [
    ['name' => 'Inbox', 'icon' => 'inbox', 'url' => route('inbox'), 'count' => 52],
    ['name' => 'Sent', 'icon' => 'envelope-o', 'url' => route('sent')],
    ['name' => 'Drafts', 'icon' => 'file-text-o', 'url' => route('drafts'), 'count' => 3],
    ['name' => 'Spam', 'icon' => 'flag-o', 'url' => route('spam')],
    ['name' => 'Trash', 'icon' => 'trash-o', 'url' => route('trash')],
];
@endphp

<x-mail-folders :folders="$folders" /> --}}
