@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
// Set the alignment classes based on the passed 'align' property
$alignmentClasses = match ($align) {
'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
'top' => 'origin-top',
default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

// Set width classes
$width = match ($width) {
'48' => 'w-48',
default => $width,
};
@endphp

<div class="relative" x-data="{ open: false, selected: [] }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        <!-- Display selected options or trigger label -->
        <span x-show="selected.length === 0">Select options</span>
        <span x-show="selected.length > 0" x-text="selected.map(option => option.label).join(', ')"></span>
    </div>

    <div x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 mt-2 {{ $width }} rounded-md shadow-lg {{ $alignmentClasses }}"
        style="display: none;"
        @click="open = false">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            @if($modelClass && isset($options))
            <!-- Render options if modelClass is provided -->
            <ul>
                @foreach($options as $value => $label)
                <li>
                    <a href="javascript:void(0);"
                        class="block px-4 py-2 text-sm text-gray-700"
                        :class="selected.some(option => option.value === '{{ $value }}') ? 'bg-blue-500 text-white' : ''"
                        @click.prevent="
                           let selectedOption = { value: '{{ $value }}', label: '{{ $label }}' };
                           if (selected.some(option => option.value === '{{ $value }}')) {
                               selected = selected.filter(option => option.value !== '{{ $value }}');
                           } else {
                               selected.push(selectedOption);
                           }
                       ">
                        {{ $label }}
                        <span x-show="selected.some(option => option.value === '{{ $value }}')" class="ml-2 text-xs text-green-500">Selected</span>
                    </a>
                </li>
                @endforeach
            </ul>
            @else
            <!-- Default case: If no modelClass, just render a basic dropdown -->
            <ul>
                <li>
                    <a href="javascript:void(0);" class="block px-4 py-2 text-sm text-gray-700">
                        No options available
                    </a>
                </li>
            </ul>
            @endif
        </div>
    </div>
</div>