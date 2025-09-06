@props(['name', 'type' => 'text', 'placeholder' => ''])

<input
    name="{{ $name }}"
    type="{{ $type }}"
    placeholder="{{ $placeholder }}"
    {{ $attributes->merge([
        'class' => 'custom-input'
    ]) }}
/>

<style>
.custom-input {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    background-color: #f5f5f5; /* Example for bg-background-light */
    color: #222; /* Example for text-text */
    border: 1px solid #f5f5f5; /* Example for border-background */
    outline: none;
    transition: box-shadow 0.2s, border-color 0.2s;
}
.custom-input:focus {
    border-color: #3b82f6; /* Example for primary color */
    box-shadow: 0 0 0 2px #3b82f633;
}
</style>
