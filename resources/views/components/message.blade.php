@props(['sender' => 'user'])

<div class="message-row {{ $sender === 'user' ? 'message-row-end' : 'message-row-start' }}">
    <div class="message-bubble {{ $sender === 'user' ? 'message-user' : 'message-other' }}">
        {{ $slot }}
    </div>
</div>
