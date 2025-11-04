@props(['name', 'type' => 'text', 'label' => null, 'value' => null, 'placeholder' => null, 'required' => false, 'help' => null, 'class' => ''])

@php
    // Translate simple string props (label, placeholder, help) when they are plain text.
    $labelText = $label && is_string($label) ? (trim($label) ?: null) : $label;
    $placeholderText = $placeholder && is_string($placeholder) ? (trim($placeholder) ?: null) : $placeholder;
    $helpText = $help && is_string($help) ? (trim($help) ?: null) : $help;

    $renderLabel = $labelText && strip_tags($labelText) === $labelText ? __($labelText) : $label;
    $renderPlaceholder = $placeholderText && strip_tags($placeholderText) === $placeholderText ? __($placeholderText) : $placeholder;
    $renderHelp = $helpText && strip_tags($helpText) === $helpText ? __($helpText) : $help;
@endphp

<div class="mb-3">
    @if($label)
    <label for="{{ $name }}" class="form-label">
        {{ $renderLabel }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    @endif
    
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $name }}"
        value="{{ $oldValue() }}"
        {{ $attributes->merge(['class' => 'form-control ' . ($hasError() ? 'is-invalid' : '') . ' ' . $class]) }}
        @if($placeholder) placeholder="{{ $renderPlaceholder }}" @endif
        @if($required) required @endif
    >
    
    @if($hasError())
        <div class="invalid-feedback">
            {{ $errorMessage() }}
        </div>
    @endif
    
    @if($help)
        <div class="form-text">{{ $renderHelp }}</div>
    @endif
</div>
