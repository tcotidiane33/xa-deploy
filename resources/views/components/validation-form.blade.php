<form method="{{ $method ?? 'POST' }}" action="{{ $action }}" class="space-y-6">
    @csrf
    @if (isset($method) && in_array($method, ['PUT', 'PATCH', 'DELETE']))
        @method($method)
    @endif

    @foreach ($fields as $field)
        <div>
            @if (isset($field['label']) && $field['type'] !== 'checkbox')
                <label for="{{ $field['name'] ?? '' }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{ $field['label'] }}
                </label>
            @endif

            @switch($field['type'] ?? 'text')
                @case('hidden')
                    <input type="hidden" name="{{ $field['name'] ?? '' }}" value="{{ $field['value'] ?? '' }}">
                @break

                @case('select')
                    <select name="{{ $field['name'] ?? '' }}" id="{{ $field['name'] ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>
                        @foreach ($field['options'] ?? [] as $value => $label)
                            <option value="{{ $value }}" {{ isset($field['value']) && $field['value'] == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                @break

                @case('checkbox')
                    <div class="flex items-center">
                        <input type="checkbox" name="{{ $field['name'] ?? '' }}" id="{{ $field['name'] ?? '' }}" class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:bg-gray-700 dark:border-gray-600" {{ isset($field['checked']) && $field['checked'] ? 'checked' : '' }}>
                        <label for="{{ $field['name'] ?? '' }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            {{ $field['label'] }}
                        </label>
                    </div>
                @break

                @case('textarea')
                    <textarea name="{{ $field['name'] ?? '' }}" id="{{ $field['name'] ?? '' }}" class="block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="{{ $field['placeholder'] ?? '' }}" {{ isset($field['required']) && $field['required'] ? 'required' : '' }} rows="{{ $field['rows'] ?? 4 }}">{{ $field['value'] ?? old($field['name'] ?? '') }}</textarea>
                @break

                @default
                    <input type="{{ $field['type'] ?? 'text' }}" name="{{ $field['name'] ?? '' }}" id="{{ $field['name'] ?? '' }}" value="{{ $field['value'] ?? old($field['name'] ?? '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="{{ $field['placeholder'] ?? '' }}" {{ isset($field['required']) && $field['required'] ? 'required' : '' }} {{ isset($field['autofocus']) && $field['autofocus'] ? 'autofocus' : '' }} {{ isset($field['autocomplete']) ? 'autocomplete=' . $field['autocomplete'] : '' }}>
            @endswitch

            @error($field['name'] ?? '')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>
    @endforeach

    <button type="submit" class="{{ $submit_class ?? 'bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2' }}">
        {{ $submit_text ?? 'Submit' }}
    </button>
</form>
