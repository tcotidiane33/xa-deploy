<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">Tableau de bord des relations Gestionnaire-Client</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Répartition des relations par type</h3>
            <div id="relationsTypeChart"></div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Top 5 des gestionnaires par nombre de clients</h3>
            <div id="topGestionnairesChart"></div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-semibold mb-4">Évolution du nombre de relations</h3>
        <div id="relationsEvolutionChart"></div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique de répartition des relations par type
    var relationsTypeOptions = {
        series: [{{ $principalCount }}, {{ $secondaryCount }}],
        chart: {
            type: 'pie',
            height: 350
        },
        labels: ['Principal', 'Secondaire'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };
    var relationsTypeChart = new ApexCharts(document.querySelector("#relationsTypeChart"), relationsTypeOptions);
    relationsTypeChart.render();

    // Graphique des top 5 gestionnaires
    var topGestionnairesOptions = {
        series: [{
            data: @json($topGestionnairesData)
        }],
        chart: {
            type: 'bar',
            height: 350
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: true,
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: @json($topGestionnairesLabels),
        }
    };
    var topGestionnairesChart = new ApexCharts(document.querySelector("#topGestionnairesChart"), topGestionnairesOptions);
    topGestionnairesChart.render();

    // Graphique d'évolution du nombre de relations
    var relationsEvolutionOptions = {
        series: [{
            name: 'Nombre de relations',
            data: @json($relationsEvolutionData)
        }],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'straight'
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'],
                opacity: 0.5
            },
        },
        xaxis: {
            categories: @json($relationsEvolutionLabels),
        }
    };
    var relationsEvolutionChart = new ApexCharts(document.querySelector("#relationsEvolutionChart"), relationsEvolutionOptions);
    relationsEvolutionChart.render();
});
</script>
@endpush