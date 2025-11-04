<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $type = 'info',
        public ?string $message = null,
        public bool $dismissible = true
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alert');
    }

    /**
     * Get the alert icon based on type.
     */
    public function icon(): string
    {
        return match($this->type) {
            'success' => 'check-circle',
            'danger', 'error' => 'x-circle',
            'warning' => 'exclamation-triangle',
            'info' => 'info-circle',
            default => 'info-circle',
        };
    }

    /**
     * Get the Bootstrap alert class.
     */
    public function alertClass(): string
    {
        $type = $this->type === 'error' ? 'danger' : $this->type;
        return "alert-{$type}";
    }
}
