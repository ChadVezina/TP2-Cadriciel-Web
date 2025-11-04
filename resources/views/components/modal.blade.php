@props(['id', 'title' => null, 'size' => '', 'centered' => false, 'staticBackdrop' => false])

@php
    $titleText = $title && is_string($title) ? trim($title) : $title;
    $renderTitle = $titleText && strip_tags($titleText) === $titleText ? __($titleText) : $title;

    $slotText = trim((string) $slot);
    $renderedSlot = ($slotText && strip_tags($slotText) === $slotText) ? __($slotText) : $slot;

    $footerText = $footer && is_string($footer) ? trim($footer) : $footer;
    $renderFooter = $footerText && strip_tags($footerText) === $footerText ? __($footerText) : $footer;
@endphp

<div class="modal fade" id="{{ $id }}" @foreach($modalAttributes() as $key => $value) {{ $key }}="{{ $value }}" @endforeach>
    <div class="{{ $dialogClass() }}">
        <div class="modal-content">
            @if($title)
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $renderTitle }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
            </div>
            @endif
            
            <div class="modal-body">
                {{ $renderedSlot }}
            </div>
            
            @isset($footer)
            <div class="modal-footer">
                {{ $renderFooter }}
            </div>
            @endisset
        </div>
    </div>
</div>
