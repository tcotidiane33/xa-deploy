@extends('layouts.admin')

@section('title', 'Créer une Convention Collective')

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
        font-size: 8rem;
        font-weight: bold;
        color: rgba(229, 231, 235, 0.2);
        white-space: nowrap;
        pointer-events: none;
        z-index: 0;
    }

    .form-input {
        @apply shadow-sm border-gray-300 rounded-md w-full py-1.5 px-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200;
    }

    .form-label {
        @apply block text-gray-700 text-xs font-medium mb-1;
    }

    .form-group {
        @apply bg-white p-3 rounded-lg shadow-sm border border-gray-100;
    }
</style>
@endpush

@section('content')
    <div class="main-content">
        <div class="container mx-auto px-4 py-6">
            <div class="cc-form-container bg-gray-50 rounded-xl shadow-lg p-6 relative">
                <div class="border-b border-gray-200 pb-3 mb-4">
                    <h2 class="text-xl font-bold text-gray-800">{{ __('Créer une Convention Collective') }}</h2>
                    <p class="text-xs text-gray-500 mt-1">Remplissez les informations requises (*)</p>
                </div>
                
                <form action="{{ route('convention-collectives.store') }}" method="POST" class="grid grid-cols-2 gap-4 relative z-10">
                    @csrf
                    
                    <!-- Colonne gauche -->
                    <div class="space-y-4">
                        <div class="form-group">
                            <label for="idcc" class="form-label">
                                <i class="fas fa-hashtag text-blue-500"></i> N° IDCC *
                            </label>
                            <input type="text" 
                                class="form-input" 
                                id="idcc" 
                                name="idcc" 
                                required 
                                pattern="\d{4}" 
                                title="Veuillez entrer exactement 4 chiffres"
                                placeholder="0000">
                            <p class="text-xs text-gray-500 mt-1">Format: 4 chiffres</p>
                        </div>
                    </div>

                    <!-- Colonne droite -->
                    <div class="space-y-4">
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="fas fa-file-signature text-green-500"></i> Nom *
                            </label>
                            <input type="text" 
                                class="form-input" 
                                id="name" 
                                name="name" 
                                required
                                placeholder="Nom de la convention collective">
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="col-span-2 flex justify-end mt-4 pt-4 border-t border-gray-200 space-x-3">
                        <a href="{{ route('convention-collectives.index') }}" 
                            class="bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                            <i class="fas fa-times mr-2"></i>Annuler
                        </a>
                        <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                            <i class="fas fa-save mr-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Afficher les messages d'erreur pour les champs invalides
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error('{{ $error }}');
                @endforeach
            @endif
        });
    </script>
@endpush