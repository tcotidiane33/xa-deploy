<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if(isset($required) && $required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        @if(isset($required) && $required) required @endif
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error($name) border-red-300 @enderror"
    >
        <option value="">Sélectionner une option</option>
        @foreach($options as $key => $option)
            <option value="{{ $key }}" {{ $value == $key ? 'selected' : '' }}>
                {{ $option }}
            </option>
        @endforeach
    </select>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div> 