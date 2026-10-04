<?php

namespace App\Livewire;

use App\Models\Property;
use Livewire\Component;

class AdvisorDemo extends Component
{
    public string $message = '';

    public bool $submitted = false;

    public string $submittedMessage = '';

    public function suggest(): void
    {
        $this->validate(['message' => 'required|string|min:10|max:1000']);
        $this->submittedMessage = $this->message;
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.advisor-demo', ['matches' => $this->submitted ? Property::published()->where('operation_type', 'venta')->where('garden', true)->where('bedrooms', '>=', 3)->where('price', '<=', 6000000)->take(3)->get() : collect()]);
    }
}
