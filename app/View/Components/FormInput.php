<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormInput extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $name,
        public string $type = 'text',
        public ?string $label = null,
        public ?string $value = null,
        public ?string $placeholder = null,
        public bool $required = false,
        public ?string $help = null,
        public string $class = ''
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-input');
    }

    /**
     * Get the old input value.
     */
    public function oldValue(): mixed
    {
        return old($this->name, $this->value);
    }

    /**
     * Check if there's an error for this field.
     */
    public function hasError(): bool
    {
        return session('errors') && session('errors')->has($this->name);
    }

    /**
     * Get the error message for this field.
     */
    public function errorMessage(): ?string
    {
        return $this->hasError() ? session('errors')->first($this->name) : null;
    }
}
