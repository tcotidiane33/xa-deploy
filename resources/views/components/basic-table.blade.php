{{-- components/basic-table.blade.php --}}
<div class="container mx-auto p-4 pt-6 md:p-6">
    {{-- <h4 class="text-lg font-bold mb-4">{{ $title ?? 'Basic Table' }}</h4> --}}
    <div class="overflow-x-auto">
        <table class="table w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    @foreach ($headers as $header)
                        <th scope="col" class="py-3 px-6">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        @foreach ($row as $key => $cell)
                            @if (isset($rawColumns) && in_array($key, $rawColumns))
                                <td class="py-4 px-6"> <span
                                        class="bg-pink-100 text-pink-800 text-xxl font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-pink-400 border border-pink-400">
                                        {!! $cell !!}</span></td>
                            @else
                                <td class="py-4 px-6"> <span
                                        class="bg-gray-100 text-gray-800 text-xxl font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-red-400 border border-gray-400">
                                        {{ $cell }}</span></td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
