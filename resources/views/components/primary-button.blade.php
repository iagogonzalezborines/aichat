@props(['type' => 'submit'])

<button type="{{ $type }}" class="primary-btn">
    {{ $slot }}
</button>
