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

    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-8">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    {{ session('error') ?? 'Une erreur est survenue' }}
                </h2>

                <p class="text-gray-600 mb-6">
                    Nous nous excusons pour la gêne occasionnée.
                </p>

                <div class="space-y-4">
                    <a href="{{ route('dashboard') }}"
                       class="block w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Retour au tableau de bord
                    </a>

                    <button onclick="window.history.back()"
                            class="block w-full px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        Page précédente
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
@endsection
