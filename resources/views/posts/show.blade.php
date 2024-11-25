@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $post->title }}</h1>
            <p class="text-gray-600 mb-4">{{ $post->content }}</p>
            <div class="text-sm text-gray-500 mb-4">
                Par {{ $post->user->name }} le {{ $post->created_at->format('d/m/Y à H:i') }}
            </div>

            @if($post->attachments->count() > 0)
            <div class="mt-6">
                <h3 class="text-xl font-semibold mb-2">Pièces jointes</h3>
                <ul class="list-disc list-inside">
                    @foreach($post->attachments as $attachment)
                    <li class="mb-2">
                        <a href="{{ Storage::url($attachment->path) }}" class="text-blue-500 hover:text-blue-700" target="_blank">
                            {{ $attachment->filename }}
                        </a>
                        @can('update', $post)
                        <form action="{{ route('posts.remove-attachment', $attachment) }}" method="POST" class="inline ml-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">Supprimer</button>
                        </form>
                        @endcan
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @can('update', $post)
            <div class="mt-6">
                <a href="{{ route('posts.edit', $post) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Modifier
                </a>
                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce post ?')">
                        Supprimer
                    </button>
                </form>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection