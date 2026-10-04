<?php

namespace App\Services;

use App\Models\Property;

class WhatsApp
{
    public function url(?Property $property = null): ?string
    {
        $number = preg_replace('/\D/', '', (string) config('advisor.whatsapp'));
        if (! $number || strlen($number) < 10 || strlen($number) > 15) {
            return null;
        }
        $message = $property ? "Hola, me interesa la propiedad {$property->title} que vi en tu página." : 'Hola, me gustaría que me ayudaras a encontrar mi próxima propiedad.';

        return 'https://wa.me/'.$number.'?text='.rawurlencode($message);
    }
}
