@props([
    'label' => null,
    'name',
    'rows' => 3,
    'required' => false
])

<div class="space-y-2">
    @if($label)
        <label for="{{ $name }}" class="text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        @if($required) required @endif
        {{ $attributes->merge([
            'class' => 'w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200'
        ]) }}
    >{{ old($name, $slot) }}</textarea>

    @error($name)
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
