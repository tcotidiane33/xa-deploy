@extends('layouts.admin')

@section('title', 'Erreur')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col items-center">
        <div class="w-64 h-64">
            <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_jcikwtux.json" background="transparent" speed="1" style="width: 100%; height: 100%;" loop autoplay></lottie-player>
        </div>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
            <strong class="font-bold">Oops! Une erreur est survenue.</strong>
            <span class="block sm:inline">{{ $exception->getMessage() }}</span>
        </div>
        <div class="mt-4">
            <a href="{{ route('home') }}" class="text-white bg-blue-500 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Retour à la page d'accueil</a>
            <a href="{{ route('tickets.create') }}" class="text-white bg-green-500 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Signaler un problème</a>
        </div>
    </div>
</div>

<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
@endsection