<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport de Sauvegarde</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4a5568;
            padding-bottom: 20px;
        }
        .date {
            color: #666;
            font-size: 14px;
        }
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .section-title {
            background: #4a5568;
            color: white;
            padding: 10px;
            font-size: 18px;
            margin-bottom: 15px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-box {
            display: table-cell;
            width: 33%;
            padding: 10px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #2b6cb0;
        }
        .stat-label {
            font-size: 12px;
            color: #4a5568;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details-table th, .details-table td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: left;
        }
        .details-table th {
            background: #f7fafc;
        }
        .files-section {
            margin: 15px 0;
            padding: 10px;
            background: #f7fafc;
        }
        .file-status {
            padding: 3px 6px;
            border-radius: 3px;
        }
        .file-present {
            color: #046c4e;
            background: #def7ec;
        }
        .file-absent {
            color: #9b1c1c;
            background: #fde8e8;
        }
        .progress-bar {
            width: 100%;
            height: 20px;
            background: #e2e8f0;
            margin-top: 5px;
        }
        .progress-value {
            height: 100%;
            background: #4299e1;
        }
        .status-locked {
            color: #9B1C1C;
            background: #FDE8E8;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .status-open {
            color: #046C4E;
            background: #DEF7EC;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .info-box {
            margin-top: 20px;
            padding: 10px;
            background: #EBF5FF;
            border-left: 4px solid #3182CE;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport de Sauvegarde Détaillé</h1>
        <div class="date">Généré le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>

    @php
        $totalClients = isset($data['clients']) ? count($data['clients']) : 0;
        $totalTraitements = isset($data['traitements_paie']) ? count($data['traitements_paie']) : 0;
        $totalPeriodes = isset($data['periodes_paie']) ? count($data['periodes_paie']) : 0;
    @endphp

    <div class="section">
        <div class="section-title">Résumé Global</div>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-value">{{ $totalClients }}</div>
                <div class="stat-label">Clients</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $totalPeriodes }}</div>
                <div class="stat-label">Périodes de paie</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $totalTraitements }}</div>
                <div class="stat-label">Traitements</div>
            </div>
        </div>
    </div>

    @if(isset($data['traitements_paie']))
    <div class="section">
        <div class="section-title">Détails des Traitements de Paie</div>
        @foreach($data['traitements_paie'] as $traitement)
            <div style="margin-bottom: 20px;">
                <h3>{{ $traitement['client'] }}</h3>
                <p>Référence: {{ $traitement['reference'] }}</p>
                <p>Période: {{ \Carbon\Carbon::parse($traitement['periode'])->format('d/m/Y') }}</p>
                
                <div class="files-section">
                    <h4>État des Documents</h4>
                    <table class="details-table">
                        <tr>
                            <th>Document</th>
                            <th>Statut</th>
                        </tr>
                        @foreach($traitement['fichiers'] as $nom => $statut)
                            <tr>
                                <td>{{ ucfirst(str_replace('_', ' ', $nom)) }}</td>
                                <td>
                                    <span class="{{ $statut !== 'Non' ? 'file-present' : 'file-absent' }}">
                                        {{ $statut !== 'Non' ? '✓ Présent' : '✗ Absent' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </table>

                    @php
                        $completionRate = collect($traitement['fichiers'])
                            ->filter(fn($status) => $status !== 'Non')
                            ->count() / count($traitement['fichiers']) * 100;
                    @endphp

                    <p>Taux de complétion: {{ round($completionRate) }}%</p>
                    <div class="progress-bar">
                        <div class="progress-value" style="width: {{ $completionRate }}%"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif

    @if(isset($data['periodes_paie']))
    <div class="section">
        <div class="section-title">Périodes de Paie</div>
        <table class="details-table">
            <tr>
                <th>Période</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Statut</th>
                <th>Traitements</th>
            </tr>
            @foreach($data['periodes_paie'] as $periode)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($periode['debut'])->format('F Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($periode['debut'])->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($periode['fin'])->format('d/m/Y') }}</td>
                    <td>
                        @if(isset($periode['validee']) && $periode['validee'] == 1)
                            <span class="status-locked">
                                🔒 Verrouillée
                            </span>
                        @else
                            <span class="status-open">
                                🔓 En cours
                            </span>
                        @endif
                    </td>
                    <td>{{ $periode['traitements_count'] ?? 0 }}</td>
                </tr>
            @endforeach
        </table>
        
        <div class="info-box">
            <p><strong>Note:</strong> Les périodes verrouillées (🔒) ne peuvent être modifiées que par les administrateurs et les responsables.</p>
        </div>
    </div>
    @endif
</body>
</html> 