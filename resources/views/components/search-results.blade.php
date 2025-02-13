@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Résultats de recherche pour "{{ $query }}"</h1>
    <button type="button" class="btn btn-secondary" id="back-button">Retour</button>
    @if($results->isEmpty())
        <p>Aucun résultat trouvé pour "{{ $query }}".</p>
    @else
        @foreach($results as $category => $items)
            @if(is_array($items) || is_object($items))
                <h2>{{ ucfirst($category) }}</h2>
                <ul>
                    @foreach($items as $item)
                        <li>
                            <strong>{{ $item->title ?? $item->name ?? $item->filename ?? 'Sans titre' }}</strong>
                            <p>{{ $item->content ?? $item->description ?? '' }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        @endforeach
    @endif
</div>

<script>
    document.getElementById('back-button').addEventListener('click', function() {
        window.history.back();
    });
</script>

@endsection
