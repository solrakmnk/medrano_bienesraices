<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (json_decode(file_get_contents(database_path('seeders/properties.json')), true) as $data) {
            Property::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
