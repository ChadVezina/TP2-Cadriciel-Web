@props(['type' => 'button', 'variant' => 'primary', 'size' => '', 'outline' => false, 'href' => null, 'icon' => null])

@php
    // If the slot is plain text (no HTML tags) attempt to translate it.
    $slotText = trim((string) $slot);
    $renderedSlot = ($slotText && strip_tags($slotText) === $slotText) ? __($slotText) : $slot;
@endphp

@if($isLink())
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $buttonClass()]) }}>
        @if($icon)
            <i class="bi bi-{{ $icon }} me-1"></i>
        @endif
        {{ $renderedSlot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $buttonClass()]) }}>
        @if($icon)
            <i class="bi bi-{{ $icon }} me-1"></i>
        @endif
        {{ $renderedSlot }}
    </button>
@endif
