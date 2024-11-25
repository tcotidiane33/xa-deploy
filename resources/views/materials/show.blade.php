@extends('layouts.admin')

@section('title', 'Détails du Matériau')

@section('content')
    <div class="main-content">
        <div class="container mx-auto px-4 py-8">
            <div class="row">
                <div class="container">
                    <h2 class="text-2xl font-bold mb-4">{{ $material->title }}</h2>
                    <p><strong>Type:</strong> {{ $material->type }}</p>
                    <p><strong>Client:</strong> {{ $client->name }}</p>
                    <p><strong>Contenu:</strong> {{ $material->content }}</p>
                    <p><strong>URL du contenu:</strong> <a href="{{ $material->content_url }}" target="_blank">{{ $material->content_url }}</a></p>
                    <p><strong>Nom du champ:</strong> {{ $material->field_name }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
