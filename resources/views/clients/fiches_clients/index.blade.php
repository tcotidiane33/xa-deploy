@extends('layouts.admin')

@push('styles')
    <style>
        .hidden {
            display: none;
        }

        .notes-container {
            position: relative;
        }

        .short-notes {
            display: inline;
        }

        .full-notes {
            display: none;
        }

        .notes-container.expanded .short-notes {
            display: none;
        }

        .notes-container.expanded .full-notes {
            display: inline;
        }

        /* clignote */
        @keyframes softBlink {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }

            100% {
                opacity: 1;
            }
        }

        .blink-animation {
            animation: softBlink 2s ease-in-out infinite;
            background-color: rgba(255, 0, 0, 0.95);
            transition: all 0.1ms ease;
        }

        .blink-animation:hover {
            animation: none;
            background-color: rgba(255, 255, 255, 0.95);
        }
    </style>
@endpush
@section('content')
    <div class="container mx-auto px-4 py-8">

        <div class="mx-auto flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Fiches Clients</h1>
            <a href="{{ route('fiches-clients.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 h-10 rounded">
                Créer une Fiche Client
            </a>
            <span class="p-1"></span>
            <a href="{{ route('fiches-clients.export.excel', request()->query()) }}"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ">
                Exporter en Excel
            </a>
            <span class="p-1"></span>
            <a href="{{ route('fiches-clients.export.pdf', request()->query()) }}"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Exporter en PDF
            </a>

        </div>
        <div class="grid grid-cols-2 md:grid-cols-2 gap-2 mb-4">
            <!-- Formulaire de filtre -->
            <div class="bg-white p-1 rounded-lg shadow ">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-filter mr-2 text-blue-500"></i>Filtres
                    </h2>
                    <button type="button" onclick="resetFilters()"
                        class="flex items-center text-sm text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="fas fa-sync-alt mr-1"></i>Réinitialiser
                    </button>
                </div>

                <form method="GET" action="{{ route('fiches-clients.index') }}" id="filterForm">
                    <div class="grid grid-cols-3 md:grid-cols-3 gap-4">
                        <!-- Sélection Client -->
                        <div class="space-y-1">
                            <label for="client_id" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-user mr-2 text-blue-500"></i>Client
                            </label>
                            <select name="client_id" id="client_id"
                                class="block w-full rounded-md border-gray-300 shadow-sm py-2 pl-3 pr-10
                           focus:border-blue-500 focus:outline-none focus:ring-blue-500 text-sm">
                                <option value="">Tous les clients</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}"
                                        {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sélection Période -->
                        <div class="space-y-1">
                            <label for="periode_paie_id" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>Période de Paie
                            </label>
                            <select name="periode_paie_id" id="periode_paie_id"
                                class="block w-full rounded-md border-gray-300 shadow-sm py-2 pl-3 pr-10
                           focus:border-blue-500 focus:outline-none focus:ring-blue-500 text-sm">
                                <option value="">Toutes les périodes</option>
                                @foreach ($periodesPaie as $periode)
                                    <option value="{{ $periode->id }}"
                                        {{ request('periode_paie_id') == $periode->id ? 'selected' : '' }}>
                                        {{ $periode->reference }}
                                        {{ $periode->validee ? '(Clôturée)' : '(Active)' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Bouton Filtrer -->
                        <div class="flex items-end">
                            <button type="submit"
                                class="flex items-center justify-center w-full h-10 px-4 py-2 bg-blue-600 text-white rounded-md
                           hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500
                           focus:ring-offset-2 transition-colors text-sm font-medium">
                                <i class="fas fa-search mr-2"></i>
                                Appliquer
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- //migration en masse % --}}
            <div class="bg-white p-1 rounded-lg shadow blink-animation">
                <h2 class="text-lg font-semibold mb-1">Migration manuelle des clients</h2>
                <form action="{{ route('fiches-clients.migrate') }}" method="POST"
                    onsubmit="return confirm('Êtes-vous sûr de vouloir migrer les clients vers cette période de paie ?');">
                    @csrf
                    <div class=" space-y-4 mx-auto">
                        <div>
                            <label for="periode_paie_id" class="block text-sm font-medium text-gray-700">
                                Période de Paie cible
                            </label>
                            <select name="periode_paie_id" id="periode_paie_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach ($periodesPaie->where('validee', false) as $periode)
                                    <option value="{{ $periode->id }}">
                                        {{ $periode->reference }}
                                        ({{ $periode->validee ? 'Clôturée' : 'Active' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent
                                   text-sm font-medium rounded-md text-white bg-yellow-600
                                   hover:bg-yellow-700 focus:outline-none focus:ring-2
                                   focus:ring-offset-2 focus:ring-yellow-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            Migrer les clients
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Client</th>
                        <th scope="col" class="px-6 py-3">Période de Paie</th>
                        <th scope="col" class="px-6 py-3">Réception variables</th>
                        <th scope="col" class="px-6 py-3">Préparation BP</th>
                        <th scope="col" class="px-6 py-3">Validation BP client</th>
                        <th scope="col" class="px-6 py-3">Préparation et envoie DSN</th>
                        <th scope="col" class="px-6 py-3">Accusés DSN</th>
                        <th scope="col" class="px-16 py-3">NOTES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fichesClients as $index => $fiche)
                        <tr
                            class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-100' }} border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-5 py-1">{{ $fiche->client->name }}</td>
                            <td class="px-5 py-1">{{ $fiche->periodePaie->reference }}</td>
                            <td class="px-5 py-1">
                                {{ $fiche->reception_variables ? \Carbon\Carbon::parse($fiche->reception_variables)->format('d/m') : 'N/A' }}
                            </td>
                            <td class="px-5 py-1">
                                {{ $fiche->preparation_bp ? \Carbon\Carbon::parse($fiche->preparation_bp)->format('d/m') : 'N/A' }}
                            </td>
                            <td class="px-5 py-1">
                                {{ $fiche->validation_bp_client ? \Carbon\Carbon::parse($fiche->validation_bp_client)->format('d/m') : 'N/A' }}
                            </td>
                            <td class="px-5 py-1">
                                {{ $fiche->preparation_envoie_dsn ? \Carbon\Carbon::parse($fiche->preparation_envoie_dsn)->format('d/m') : 'N/A' }}
                            </td>
                            <td class="px-5 py-1">
                                {{ $fiche->accuses_dsn ? \Carbon\Carbon::parse($fiche->accuses_dsn)->format('d/m') : 'N/A' }}
                            </td>
                            <td class="px-15 py-1">
                                <div class="notes-container">
                                    <span class="short-notes">{{ Str::limit($fiche->notes, 50) }}</span>
                                    @if (strlen($fiche->notes) > 50)
                                        <span class="full-notes hidden">{{ $fiche->notes }}</span>
                                        <button onclick="toggleNotes(this)" class="text-blue-500">Voir plus</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $fichesClients->links() }}
            </div>
        </div>

        <!-- Popup de mise à jour -->
        <div id="updatePopup"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center">
            <div class="relative p-6 border w-92 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Ajouter les variables</h3>
                    <livewire:update-fiche-client :ficheClient="$ficheClient" />
                </div>
            </div>
        </div>

    </div>
    {{-- @if (isset($breadcrumbs))
            <div class="breadcrumb-container bg-gray-100 p-4 shadow-sm">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <!-- Breadcrumb content -->
                    </ol>
                </nav>
            </div>
        @endif --}}


    <script>
        function openPopup(ficheClientId) {
            fetch(`/fiches-clients/${ficheClientId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('fiche_client_id').value = ficheClientId;
                    document.getElementById('updateForm').action = `/fiches-clients/${ficheClientId}`;
                    document.getElementById('reception_variables').value = data.reception_variables;
                    document.getElementById('preparation_bp').value = data.preparation_bp;
                    document.getElementById('validation_bp_client').value = data.validation_bp_client;
                    document.getElementById('preparation_envoie_dsn').value = data.preparation_envoie_dsn;
                    document.getElementById('accuses_dsn').value = data.accuses_dsn;
                    document.getElementById('notes').value = data.notes;
                    document.getElementById('updatePopup').classList.remove('hidden');
                });
        }

        function closePopup() {
            document.getElementById('updatePopup').classList.add('hidden');
        }

        function showFullNotes(notes) {
            alert(notes);
        }
    </script>
    <script>
        function toggleNotes(button) {
            const container = button.closest('.notes-container');
            container.classList.toggle('expanded');
            if (container.classList.contains('expanded')) {
                button.textContent = 'Voir moins';
            } else {
                button.textContent = 'Voir plus';
            }
        }
    </script>
@endsection
@push('scripts')
    <script>
        function resetFilters() {
            document.getElementById('client_id').value = '';
            document.getElementById('periode_paie_id').value = '';
            document.getElementById('filterForm').submit();
        }

        // Animation douce lors du changement de filtre
        document.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', function() {
                this.classList.add('transition-all', 'duration-300');
            });
        });
    </script>
@endpush
