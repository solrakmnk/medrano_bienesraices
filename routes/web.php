<?php

use App\Models\Property;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home', ['featured' => Property::published()->where('featured', true)->take(3)->get()]))->name('home');
Route::view('/propiedades', 'catalog')->name('properties.index');
Route::get('/propiedades/{property:slug}', function (Property $property) {
    abort_unless($property->published, 404);

    return view('property', ['property' => $property]);
})->name('properties.show');
