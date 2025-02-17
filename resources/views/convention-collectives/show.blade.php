@extends('layouts.admin')

@section('title', 'Détails de la Convention Collective')

@push('styles')
<style>
    .cc-form-container {
        position: relative;
        overflow: hidden;
    }
    
    .cc-form-container::before {
        content: 'CONVENTION';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        font-size: 6rem;
        font-weight: bold;
        color: rgba(229, 231, 235, 0.2);
        white-space: nowrap;
        pointer-events: none;
        z-index: 0;
    }

    .info-group {
        @apply bg-white p-3 rounded-lg shadow-sm border border-gray-100;
    }

    .info-label {
        @apply block text-gray-700 text-xs font-medium mb-1;
    }

    .info-value {
        @apply text-sm text-gray-800 py-1.5 px-2;
    }
</style>
@endpush

@section('content')
    <div class="main-content">
        <div class="container mx-auto px-4 py-6">
            <div class="cc-form-container bg-gray-50 rounded-xl shadow-lg p-6 relative">
                <div class="border-b border-gray-200 pb-3 mb-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">{{ __('Détails de la Convention Collective') }}</h2>
                        <p class="text-xs text-gray-500 mt-1">Informations complètes</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('convention-collectives.edit', $conventionCollective->id) }}" 
                            class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                            <i class="fas fa-edit mr-2"></i>Modifier
                        </a>
                        <form action="{{ route('convention-collectives.destroy', $conventionCollective->id) }}" 
                            method="POST" 
                            class="inline-block"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette convention collective ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white text-sm font-medium py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                                <i class="fas fa-trash-alt mr-2"></i>Supprimer
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 relative z-10">
                    <!-- Colonne gauche -->
                    <div class="space-y-4">
                        <div class="info-group">
                            <label class="info-label">
                                <i class="fas fa-hashtag text-blue-500"></i> N° IDCC
                            </label>
                            <div class="info-value bg-gray-50 rounded">
                                {{ $conventionCollective->idcc }}
                            </div>
                        </div>
                    </div>

                    <!-- Colonne droite -->
                    <div class="space-y-4">
                        <div class="info-group">
                            <label class="info-label">
                                <i class="fas fa-file-signature text-green-500"></i> Nom
                            </label>
                            <div class="info-value bg-gray-50 rounded">
                                {{ $conventionCollective->name }}
                            </div>
                        </div>
                    </div>

                    <!-- Informations supplémentaires -->
                    <div class="col-span-2 mt-4 space-y-4">
                        <div class="info-group">
                            <label class="info-label">
                                <i class="fas fa-clock text-purple-500"></i> Informations temporelles
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="info-value bg-gray-50 rounded">
                                    <span class="text-gray-500">Créé le:</span> 
                                    {{ $conventionCollective->created_at->format('d/m/Y à H:i') }}
                                </div>
                                <div class="info-value bg-gray-50 rounded">
                                    <span class="text-gray-500">Dernière modification:</span> 
                                    {{ $conventionCollective->updated_at->format('d/m/Y à H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end mt-6 pt-4 border-t border-gray-200">
                    <a href="{{ route('convention-collectives.index') }}" 
                        class="bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection