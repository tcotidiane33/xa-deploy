@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-2">
    <nav class="flex items-center mt-0 mb-1" aria-label="Breadcrumb">
        <span class="text-gray-700">Résultats de recherche pour "{{ $query }}"</span>
        <svg class="rtl:rotate-180 w-5 h-5 text-gray-400 mx-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
        </svg>
        <button class="relative inline-flex items-center justify-center p-2 mb-2 text-sm font-medium text-white bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg shadow-md hover:from-purple-400 hover:to-pink-400 focus:ring-4 focus:outline-none focus:ring-purple-200" id="back-button">
            Retour
        </button>
    </nav>

    @if($results->isEmpty())
        <p class="text-gray-500">Aucun résultat trouvé pour "{{ $query }}".</p>
    @else
        @foreach($results as $category => $items)
            @if(is_array($items) || is_object($items))
                <h5 class="m-0 text-sm font-bold text-gray-800 dark:text-white">{{ ucfirst($category) }}</h5>
                <ul class="space-y-4 text-left">
                    @foreach($items as $item)
                        <li class="flex items-center space-x-1 rtl:space-x-reverse mt-0">
                            <svg class="rtl:rotate-180 w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <div class="flex flex-col p-2 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 w-full mt-0">
                                <kbd class="px-2 py-1 text-xs font-semibold text-green-800 bg-gray-100 border border-gray-200 rounded-lg dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500">{{ $item->title ?? $item->name ?? $item->filename ?? 'Sans titre' }}</kbd>
                                <kbd class="px-2 py-1 text-xs font-semibold text-red-800 bg-gray-100 border border-gray-200 rounded-lg dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500">{{ $item->content ?? $item->description ?? '' }}</kbd>
                                <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-lg dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500">
                                    <a href="{{ route($category . '.show', $item->id) }}" class="text-red-500">Voir Détails</a>
                                </kbd>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    <hr class="h-px mb-0 bg-gray-200 border-0 dark:bg-gray-700">
        @endforeach
    @endif
</div>

<script>
    document.getElementById('back-button').addEventListener('click', function() {
        window.history.back();
    });
</script>

@endsection
