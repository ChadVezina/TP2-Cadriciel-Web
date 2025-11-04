<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $type = 'button',
        public string $variant = 'primary',
        public string $size = '',
        public bool $outline = false,
        public ?string $href = null,
        public ?string $icon = null
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.button');
    }

    /**
     * Get the button class.
     */
    public function buttonClass(): string
    {
        $classes = ['btn'];
        $classes[] = $this->outline ? "btn-outline-{$this->variant}" : "btn-{$this->variant}";
        if ($this->size) {
            $classes[] = "btn-{$this->size}";
        }
        return implode(' ', $classes);
    }

    /**
     * Determine if this is a link button.
     */
    public function isLink(): bool
    {
        return $this->href !== null;
    }
}
