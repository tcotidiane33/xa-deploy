@extends('layouts.admin')

@section('title', 'Modifier la période de paie')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">
            Modifier la période de paie
        </h1>
        <a href="{{ route('periodes-paie.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg">
            Retour
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="/periodes-paie/{{ $periodePaie->id }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="reference" class="block text-sm font-medium text-gray-700">Référence</label>
                    <input type="text" name="reference" id="reference" 
                           value="{{ old('reference', $periodePaie->reference) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('reference')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="debut" class="block text-sm font-medium text-gray-700">Date de début</label>
                        <input type="date" name="debut" id="debut" 
                               value="{{ old('debut', optional($periodePaie->debut)->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('debut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="fin" class="block text-sm font-medium text-gray-700">Date de fin</label>
                        <input type="date" name="fin" id="fin" 
                               value="{{ old('fin', optional($periodePaie->fin)->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('fin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" 
                        onclick="window.location='{{ route('periodes-paie.index') }}'"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg">
                    Annuler
                </button>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
