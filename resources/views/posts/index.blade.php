@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="row">
            </br>
            </br>
            <div class="breadcrumb">
                <h1>Publications</h1>
            </div>

        </div>
        <div class="flex justify-arround  items-center mb-6">
            <h1 class="text-3xl font-bold ml-3 text-gray-800">Posts</h1>
            <a href="{{ route('posts.create') }}"
                class="bg-blue-500 ml-3 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Nouveau Post
            </a>
        </div>

        <section class=" dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16">
                <div
                    class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 md:p-12 mb-8">
                    @foreach ($posts as $post)
                        <div class="bg-white shadow-md rounded-lg mb-4 overflow-hidden">
                            <div class="p-4">
                                <h2 class="text-xl font-semibold mb-2">{{ $post->title }}</h2>
                                <p class="text-gray-600 mb-4">{{ Str::limit($post->content, 150) }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Par {{ $post->user->name }} le
                                        {{ $post->created_at->format('d/m/Y') }}</span>
                                    <a href="{{ route('posts.show', $post) }}"
                                        class="text-blue-500 hover:text-blue-700">Lire la suite</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <div class="mt-4">
            {{-- {{ $posts->links() }} --}}
            {{-- {{ $posts->links('pagination::tailwind') }} --}}
        </div>
    </div>
@endsection
