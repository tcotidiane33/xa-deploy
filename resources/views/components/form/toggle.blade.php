<div class="flex items-center">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mr-3">
        {{ $label }}
    </label>
    <button 
        type="button"
        role="switch"
        aria-checked="{{ $checked ? 'true' : 'false' }}"
        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $checked ? 'bg-indigo-600' : 'bg-gray-200' }}"
        x-data="{ on: {{ $checked ? 'true' : 'false' }} }"
        @click="on = !on"
    >
        <input 
            type="hidden" 
            name="{{ $name }}" 
            :value="on ? 1 : 0"
        >
        <span 
            aria-hidden="true"
            :class="on ? 'translate-x-5' : 'translate-x-0'"
            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
        ></span>
    </button>
</div> 