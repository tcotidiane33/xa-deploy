<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Périodes de Paie</h1>
        
        @if($currentPeriode)
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded">
                <p class="font-bold">Période en cours : {{ $currentPeriode->reference }}</p>
                <p class="text-sm">Du {{ $currentPeriode->debut->format('d/m/Y') }} au {{ $currentPeriode->fin->format('d/m/Y') }}</p>
            </div>
        @endif
    </div>

    <!-- Actions -->
    @if(auth()->user()->hasRole(['Admin', 'Responsable', 'Gestionnaire']))
        <div class="flex gap-4 mb-6">
            <button wire:click="createNewPeriode"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Créer une Période
            </button>
        </div>
    @endif

    <!-- Liste des périodes -->
    <div class="bg-white shadow-md rounded my-6">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Référence</th>
                    <th class="py-3 px-6 text-left">Début</th>
                    <th class="py-3 px-6 text-left">Fin</th>
                    <th class="py-3 px-6 text-center">Statut</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($periodes as $periode)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left">{{ $periode->reference }}</td>
                        <td class="py-3 px-6 text-left">{{ $periode->debut->format('d/m/Y') }}</td>
                        <td class="py-3 px-6 text-left">{{ $periode->fin->format('d/m/Y') }}</td>
                        <td class="py-3 px-6 text-center">
                            <span class="bg-{{ $periode->validee ? 'green' : 'blue' }}-200 text-{{ $periode->validee ? 'green' : 'blue' }}-600 py-1 px-3 rounded-full text-xs">
                                {{ $periode->validee ? 'Clôturée' : 'En cours' }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <a href="{{ route('periodes-paie.show', $periode) }}" 
                               class="text-blue-600 hover:text-blue-900">Voir détails</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div> 