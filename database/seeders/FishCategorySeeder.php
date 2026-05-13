<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FishCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'M', 'description' => 'Medium', 'unit_price' => 13000],
            ['name' => 'L', 'description' => 'Large', 'unit_price' => 14000],
            ['name' => 'XL', 'description' => 'Extra Large', 'unit_price' => 15000],
            ['name' => 'SXL', 'description' => 'Super Extra Large', 'unit_price' => 16500],
        ];

        foreach ($categories as $category) {
            \App\Models\FishCategory::create($category);
        }
    }
}
