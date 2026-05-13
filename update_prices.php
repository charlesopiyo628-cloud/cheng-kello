<?php

require_once 'vendor/autoload.php';

use App\Models\FishCategory;

// Update fish categories with correct purchase costs and selling prices
$categories = [
    'M' => ['purchase_cost' => 10000, 'unit_price' => 13000],
    'L' => ['purchase_cost' => 10000, 'unit_price' => 14000],
    'XL' => ['purchase_cost' => 11000, 'unit_price' => 15000],
    'SXL' => ['purchase_cost' => 12000, 'unit_price' => 16500],
];

foreach ($categories as $name => $prices) {
    FishCategory::where('name', $name)->update($prices);
    echo "Updated {$name}: Purchase Cost = {$prices['purchase_cost']}, Selling Price = {$prices['unit_price']}\n";
}

echo "Price updates completed!\n";
