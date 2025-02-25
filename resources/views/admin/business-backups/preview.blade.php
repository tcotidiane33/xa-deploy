@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Prévisualisation - {{ $fileName }}</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.business-backups.download', [$fileName, 'format' => 'json']) }}" 
               class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                JSON
            </a>
            <a href="{{ route('admin.business-backups.download', [$fileName, 'format' => 'csv']) }}" 
               class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                CSV
            </a>
            <a href="{{ route('admin.business-backups.download', [$fileName, 'format' => 'xlsx']) }}" 
               class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">
                Excel
            </a>
            <a href="{{ route('admin.business-backups.download', [$fileName, 'format' => 'pdf']) }}" 
               class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                PDF
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="space-y-6">
            @foreach($data as $type => $items)
                <div>
                    <h2 class="text-xl font-semibold mb-3">{{ ucfirst(str_replace('_', ' ', $type)) }}</h2>
                    <div class="overflow-x-auto">
                        <pre class="bg-gray-100 p-4 rounded">{{ json_encode($items, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection 