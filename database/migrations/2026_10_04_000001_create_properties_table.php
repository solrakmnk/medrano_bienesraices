<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->text('description');
            $t->string('operation_type')->index();
            $t->string('property_type');
            $t->decimal('price', 14, 2)->index();
            $t->char('currency', 3)->default('MXN');
            foreach (['address', 'neighborhood', 'city', 'state'] as $field) {
                $t->string($field);
            }
            $t->decimal('latitude', 10, 7)->nullable();
            $t->decimal('longitude', 10, 7)->nullable();
            foreach (['bedrooms', 'bathrooms', 'half_bathrooms', 'parking_spaces'] as $field) {
                $t->unsignedTinyInteger($field)->default(0);
            }
            $t->decimal('land_area', 10, 2);
            $t->decimal('construction_area', 10, 2);
            $t->boolean('garden')->default(false);
            $t->boolean('furnished')->default(false);
            $t->boolean('pets_allowed')->nullable();
            $t->boolean('featured')->default(false);
            $t->boolean('published')->default(false)->index();
            $t->json('amenities')->nullable();
            $t->json('images');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
