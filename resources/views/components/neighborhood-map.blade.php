@props(['properties'])
@php
    $zonePositions = ['Juriquilla' => [22, 20], 'Zibatá' => [76, 20], 'Zákia' => [88, 37], 'El Refugio' => [68, 38], 'Álamos' => [44, 53], 'El Campanario' => [75, 56], 'Centro Histórico' => [27, 69], 'Milénio III' => [61, 73], 'Centro Sur' => [43, 85]];
    $zoneSeen = [];
    $layers = ['schools' => ['Colegios', 'C', 'Escuela de ejemplo'], 'markets' => ['Supermercados', 'S', 'Supermercado de ejemplo'], 'plazas' => ['Plazas', 'P', 'Plaza comercial de ejemplo']];
@endphp
<div class="map-experience" x-data="{ selected: null, layers: ['schools', 'markets', 'plazas'], showRoute: false }" wire:key="map-{{ md5($properties->pluck('id')->implode(',')) }}">
    <div class="map-caption"><div><strong>El hogar también está en su entorno.</strong><p>Selecciona una propiedad para explorar lo que podría tener cerca.</p></div><span class="demo-pill">MAPA DEMO</span></div>
    <div class="map-layer-controls" aria-label="Servicios ilustrativos visibles">
        @foreach($layers as $key => [$label, $symbol, $title])<label><input type="checkbox" value="{{ $key }}" x-model="layers">{{ $label }}</label>@endforeach
        <label><input type="checkbox" x-model="showRoute">Ruta de ejemplo</label>
    </div>
    <div class="map-layout">
        <div class="zone-map" role="region" aria-label="Esquema de zonas, propiedades y servicios ilustrativos">
            <svg class="map-base" viewBox="0 0 1000 800" preserveAspectRatio="none" aria-hidden="true">
                <defs><pattern id="map-grid" width="65" height="60" patternUnits="userSpaceOnUse"><rect width="65" height="60" fill="#eff3ef"/><path d="M0 0H65V60" stroke="#dce5dc" fill="none" stroke-width="2"/></pattern></defs>
                <rect width="1000" height="800" fill="url(#map-grid)"/>
                <path d="M0 330C180 370 160 150 350 240S680 480 1000 350" fill="none" stroke="#d9e9f2" stroke-width="28"/>
                <path d="M150 0 350 200 380 440 450 800M0 620 300 530 650 550 1000 640M980 0 780 180 660 420 580 800" stroke="#d6dcd7" stroke-width="24" fill="none"/>
                <path d="M150 0 350 200 380 440 450 800M0 620 300 530 650 550 1000 640M980 0 780 180 660 420 580 800" stroke="#fff" stroke-width="17" fill="none"/>
            </svg>
            <span class="map-north">N ↑</span><span class="map-watermark">QUERÉTARO · ESQUEMA DEMO</span>
            @foreach($zonePositions as $zone => [$x, $y])<span class="zone-label" style="left:{{ $x }}%;top:{{ $y + 5 }}%">{{ $zone }}</span>@endforeach
            @foreach($properties as $property)
                @php
                    [$x, $y] = $zonePositions[$property->neighborhood] ?? [50, 50];
                    $zoneIndex = $zoneSeen[$property->neighborhood] ?? 0;
                    $zoneSeen[$property->neighborhood] = $zoneIndex + 1;
                    $offset = $zoneIndex === 0 ? -3 : 3;
                    if ($zoneIndex > 0) { $y += 10 * $zoneIndex; }
                @endphp
                <button class="map-property-pin" style="left:{{ $x + $offset }}%;top:{{ $y }}%" @click="selected = {{ $property->id }}" :class="{ 'selected': selected === {{ $property->id }} }" :aria-pressed="selected === {{ $property->id }}" aria-label="Explorar {{ $property->title }}, {{ $property->formatted_price }}"><span>{{ $property->operation_type === 'renta' ? '$'.number_format($property->price / 1000, 1).' mil' : '$'.number_format($property->price / 1000000, 2).' M' }}</span><span x-show="$store.favorites.has({{ $property->id }})" x-cloak aria-label="Favorita">♥</span></button>
                <svg x-cloak x-show="selected === {{ $property->id }} && showRoute" class="map-route" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true"><path d="M{{ $x + $offset }} {{ $y }} L{{ $x + $offset }} {{ $y + 9 }} L{{ max(4, $x - 12) }} {{ $y + 9 }} L{{ max(4, $x - 12) }} {{ max(5, $y - 7) }}" stroke="#2879da" stroke-width=".5" stroke-dasharray="1 1" fill="none"/></svg>
                @foreach($layers as $key => [$label, $symbol, $title])
                    @php($dx = [-12, 11, -9][$loop->index]) @php($dy = [-7, 9, 15][$loop->index])
                    <span x-cloak x-show="selected === {{ $property->id }} && layers.includes('{{ $key }}')" class="service-pin service-{{ $key }}" style="left:{{ max(4, min(95, $x + $dx)) }}%;top:{{ max(5, min(95, $y + $dy)) }}%" title="{{ $title }}"><span>{{ $symbol }}</span><small>{{ $label }}</small></span>
                @endforeach
            @endforeach
            <div class="map-scale-note">Esquema ilustrativo · Sin escala ni ubicaciones reales</div>
        </div>
        <aside class="map-selection">
            <div x-show="selected === null" class="map-select-empty"><p class="eyebrow">CONOCE EL ENTORNO</p><h3>{{ $properties->isEmpty() ? 'No hay propiedades con esta selección.' : 'Elige un precio en el mapa.' }}</h3><p>{{ $properties->isEmpty() ? 'Quita filtros o guarda tus primeras favoritas.' : 'Verás la propiedad y ejemplos de servicios para comparar cómo podría ser tu día a día.' }}</p><p class="map-disclaimer">Propiedades y servicios ficticios. Este mapa no sirve para navegar.</p></div>
            @foreach($properties as $property)
                <div x-cloak x-show="selected === {{ $property->id }}" class="map-selected-property">
                    <img src="{{ $property->images[0] }}" alt="Fotografía ilustrativa de {{ $property->title }}">
                    <p class="location">{{ $property->neighborhood }} · {{ ucfirst($property->operation_type) }}</p><h3>{{ $property->title }}</h3><p class="price">{{ $property->formatted_price }}</p><p class="facts">{{ $property->bedrooms }} rec. · {{ $property->bathrooms }} baños · {{ (int)$property->construction_area }} m²</p>
                    <x-favorite-button :property="$property" />
                    <h4>Tu día a día, cerca de casa</h4>
                    @foreach($layers as $key => [$label, $symbol, $title])<div class="nearby-item" x-show="layers.includes('{{ $key }}')"><span class="nearby-symbol service-{{ $key }}">{{ $symbol }}</span><div><strong>{{ $title }}</strong><small>{{ match($key) { 'schools' => 'Opciones educativas para explorar', 'markets' => 'Compras del día a día', default => 'Comercios y espacios de encuentro' } }}</small></div><span class="nearby-demo">Demo</span></div>@endforeach
                    <p class="route-note" x-cloak x-show="showRoute">La línea azul muestra un trayecto ilustrativo hacia una escuela. No representa calles, distancias ni tiempos reales.</p>
                    <a class="button" href="{{ route('properties.show', $property) }}">Conocer esta propiedad</a>
                    <p class="map-disclaimer">Servicios de ejemplo, sin verificación. La ubicación real se confirmará con la asesora.</p>
                </div>
            @endforeach
        </aside>
    </div>
</div>
