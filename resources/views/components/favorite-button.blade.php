@props(['property', 'compact' => false])
<button type="button" x-data class="favorite-button {{ $compact ? 'favorite-compact' : '' }}" :class="{ 'is-favorite': $store.favorites.has({{ $property->id }}) }" :aria-pressed="$store.favorites.has({{ $property->id }})" :aria-label="($store.favorites.has({{ $property->id }}) ? 'Quitar de favoritas: ' : 'Guardar en favoritas: ') + @js($property->title)" @click="$store.favorites.toggle({{ $property->id }})">
<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8Z"/></svg>
@if(!$compact)<span x-text="$store.favorites.has({{ $property->id }}) ? 'Guardada en favoritas' : 'Guardar en favoritas'">Guardar en favoritas</span>@endif
</button>