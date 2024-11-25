@props(['id' => 'chart', 'height' => '270px'])

<div class="charts-grids widget">
    <div class="card-header">
        <h3>{{ $title ?? 'Bar Chart' }}</h3>
    </div>

    <div id="{{ $id }}" style="width: 100%; height: {{ $height }};">
        <canvas id="canvas-{{ $id }}"></canvas>
    </div>
    {{ $slot }}
</div>

@push('scripts')
<script>
    // Ici, vous pouvez ajouter le code JavaScript pour initialiser le graphique
    // Vous pouvez utiliser une bibliothèque comme Chart.js
</script>
@endpush


{{-- used:
<x-bar-chart id="myBarChart" height="300px">
    <button id="randomizeData">Randomize Data</button>
    <button id="addDataset">Add Dataset</button>
    <button id="removeDataset">Remove Dataset</button>
</x-bar-chart> --}}
