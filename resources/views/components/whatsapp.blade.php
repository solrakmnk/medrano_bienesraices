@props(['property'=>null,'label'=>'Hablar por WhatsApp'])
@php($url = app(\App\Services\WhatsApp::class)->url($property))
@if($url)<a {{ $attributes }} href="{{ $url }}" target="_blank" rel="noopener noreferrer">{{ $label }}</a>@else<span {{ $attributes->class(['contact-disabled']) }} title="Configura WHATSAPP_NUMBER para activar el contacto">{{ $label }} <small>Próximamente</small></span>@endif