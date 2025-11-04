<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Modal extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $id,
        public ?string $title = null,
        public string $size = '',
        public bool $centered = false,
        public bool $staticBackdrop = false
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modal');
    }

    /**
     * Get the modal dialog class.
     */
    public function dialogClass(): string
    {
        $classes = ['modal-dialog'];
        if ($this->size) {
            $classes[] = "modal-{$this->size}";
        }
        if ($this->centered) {
            $classes[] = 'modal-dialog-centered';
        }
        return implode(' ', $classes);
    }

    /**
     * Get the modal attributes.
     */
    public function modalAttributes(): array
    {
        $attrs = [
            'tabindex' => '-1',
            'aria-labelledby' => "{$this->id}Label",
            'aria-hidden' => 'true',
        ];
        if ($this->staticBackdrop) {
            $attrs['data-bs-backdrop'] = 'static';
            $attrs['data-bs-keyboard'] = 'false';
        }
        return $attrs;
    }
}
