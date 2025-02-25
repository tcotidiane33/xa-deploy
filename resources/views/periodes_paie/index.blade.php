@extends('layouts.admin')

@section('title', 'Périodes de Paie')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .modifiable {
            background-color: #f3e8ff;
            /* Couleur de fond pour les champs modifiables */
        }

        .non-modifiable {
            background-color: #e2e8f0;
            /* Couleur de fond pour les champs non modifiables */
        }

        .date-valid {
            color: #059669;
        }

        .date-invalid {
            color: #dc2626;
        }

        .date-warning {
            color: #d97706;
        }

        .progress {
            background-color: #ff00f231;
            border-radius: 5px;
            overflow: hidden;
        }

        .progress-bar {
            background-color: #04e90c;
            height: 20px;
            text-align: center;
            color: white;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 40%;
            /* Réduire la largeur du popup */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .table-container {
            overflow-x: auto;
            /* Ajout de l'overflow pour le tableau */
        }

        /* Styles pour le terminal */
        .terminal {
            background-color: #1a1a1a;
            border-radius: 0.5rem;
            font-family: 'Courier New', monospace;
        }

        .terminal-header {
            background-color: #2d2d2d;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            padding: 0.5rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .terminal-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .terminal-button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .terminal-close {
            background-color: #ff5f56;
        }

        .terminal-minimize {
            background-color: #ffbd2e;
        }

        .terminal-maximize {
            background-color: #27c93f;
        }

        .terminal-content {
            padding: 1rem;
            max-height: 400px;
            overflow-y: auto;
        }

        /* Styles pour le calendrier */
        .calendar-event {
            @apply p-2 rounded-lg mb-2 flex items-center;
        }
        .event-dot {
            @apply w-3 h-3 rounded-full mr-2;
        }
    </style>
@endpush

@section('content')
    <!-- En-tête de la page -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-4 md:mb-0">
                Gestion des Périodes de Paie
            </h1>
            
            @if(auth()->user()->hasRole(['Admin', 'Responsable', 'Gestionnaire']))
                <div class="flex gap-4">
                    <a href="{{ route('periodes-paie.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200 ease-in-out flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nouvelle Période
                    </a>
                </div>
            @endif
        </div>

        <!-- Période active -->
        @if($currentPeriode)
            <div class="bg-white rounded-lg shadow-md p-6 mb-8 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Période en cours</h2>
                        <p class="text-gray-600">
                            {{ $currentPeriode->reference }} 
                            ({{ $currentPeriode->debut->format('d/m/Y') }} - {{ $currentPeriode->fin->format('d/m/Y') }})
                        </p>
                    </div>
                    <div class="flex items-center">
                        <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">Active</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Liste des périodes -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Référence
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Période
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Progression
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($periodes as $periode)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $periode->reference }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $periode->debut->format('d/m/Y') }} - {{ $periode->fin->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $periode->validee 
                                            ? 'bg-green-100 text-green-800' 
                                            : ($periode->fin->isPast() 
                                                ? 'bg-red-100 text-red-800' 
                                                : 'bg-blue-100 text-blue-800') }}">
                                        {{ $periode->validee 
                                            ? 'Clôturée' 
                                            : ($periode->fin->isPast() 
                                                ? 'En retard' 
                                                : 'En cours') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $periode->progression }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $periode->progression }}%</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('periodes-paie.show', $periode) }}" 
                                           class="text-indigo-600 hover:text-indigo-900">
                                            Détails
                                        </a>
                                        @if(!$periode->validee && auth()->user()->hasRole(['Admin', 'Responsable']))
                                            <a href="{{ route('periodes-paie.edit', $periode) }}" 
                                               class="text-blue-600 hover:text-blue-900">
                                                Modifier
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Configuration des notifications
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
        }

        // Affichage des messages de notification
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    </script>
@endpush
