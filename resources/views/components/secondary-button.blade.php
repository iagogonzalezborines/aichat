@props(['type' => 'button'])

<button type="{{ $type }}" class="secondary-btn">
    {{ $slot }}
</button>
