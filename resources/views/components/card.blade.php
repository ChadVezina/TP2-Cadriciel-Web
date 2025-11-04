@props(['title' => null, 'footer' => null, 'class' => ''])

@php
    // Translate plain-text title/footer when appropriate.
    $titleText = $title && is_string($title) ? trim($title) : $title;
    $footerText = $footer && is_string($footer) ? trim($footer) : $footer;

    $renderTitle = $titleText && strip_tags($titleText) === $titleText ? __($titleText) : $title;
    $renderFooter = $footerText && strip_tags($footerText) === $footerText ? __($footerText) : $footer;

    $slotText = trim((string) $slot);
    $renderedSlot = ($slotText && strip_tags($slotText) === $slotText) ? __($slotText) : $slot;
@endphp

<div {{ $attributes->merge(['class' => 'card ' . $class]) }}>
    @if($title)
    <div class="card-header">
        <h5 class="card-title mb-0">{{ $renderTitle }}</h5>
    </div>
    @endif
    
    <div class="card-body">
        {{ $renderedSlot }}
    </div>
    
    @if($footer)
    <div class="card-footer">
        {{ $renderFooter }}
    </div>
    @endif
</div>
