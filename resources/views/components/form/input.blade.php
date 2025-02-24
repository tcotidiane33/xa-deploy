<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if(isset($required) && $required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <input 
        type="{{ $type ?? 'text' }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $value ?? old($name) }}"
        @if(isset($required) && $required) required @endif
        @if(isset($min)) min="{{ $min }}" @endif
        @if(isset($max)) max="{{ $max }}" @endif
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error($name) border-red-300 @enderror"
    >
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div> 