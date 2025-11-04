@props(['type' => 'info', 'message' => null, 'dismissible' => true])

@php
    // Translate simple string message or slot if they are plain text
    $messageText = $message && is_string($message) ? trim($message) : $message;
    $renderMessage = $messageText && strip_tags($messageText) === $messageText ? __($messageText) : $message;

    $slotText = trim((string) $slot);
    $renderedSlot = ($slotText && strip_tags($slotText) === $slotText) ? __($slotText) : $slot;
@endphp

@if($message || $slot->isNotEmpty())
<div {{ $attributes->merge(['class' => 'alert ' . $alertClass() . ($dismissible ? ' alert-dismissible fade show' : '')]) }} role="alert">
    <i class="bi bi-{{ $icon() }} me-2"></i>
    {{ $renderMessage ?? $renderedSlot }}
    
    @if($dismissible)
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('common.close') }}"></button>
    @endif
</div>
@endif
