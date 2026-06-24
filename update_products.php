<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

$updates = [
    6  => 'images/khmer_beef_soup.png',
    13 => 'images/fresh_young_coconut.png',
    14 => 'images/seafood_fried_rice.png',
    15 => 'images/pepper_fried_crab.png',
    16 => 'images/iced_lemon_tea.png',
    17 => 'images/passion_fruit_milk.png',
];

foreach ($updates as $id => $image) {
    $product = Product::find($id);
    if ($product) {
        $product->image = $image;
        $product->save();
        echo "Updated Product {$id} ({$product->name}) image path to: {$image}\n";
    } else {
        echo "Product ID {$id} not found!\n";
    }
}

echo "Database updates completed successfully!\n";
