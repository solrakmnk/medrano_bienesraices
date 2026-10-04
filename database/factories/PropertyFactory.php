<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PropertyFactory extends Factory
{
    public function definition(): array
    {
        $title = 'Casa familiar '.fake()->unique()->numerify('###');

        return ['title' => $title, 'slug' => Str::slug($title), 'description' => 'Casa con espacios amplios, cocina abierta y un jardín para disfrutar en familia.', 'operation_type' => 'venta', 'property_type' => 'casa', 'price' => fake()->numberBetween(2500000, 6000000), 'currency' => 'MXN', 'address' => 'Ubicación aproximada', 'neighborhood' => 'Zibatá', 'city' => 'Querétaro', 'state' => 'Querétaro', 'bedrooms' => 3, 'bathrooms' => 2, 'half_bathrooms' => 1, 'parking_spaces' => 2, 'land_area' => 200, 'construction_area' => 180, 'garden' => true, 'furnished' => false, 'pets_allowed' => null, 'featured' => false, 'published' => true, 'amenities' => ['Estudio'], 'images' => ['/images/home.jpg']];
    }
}
