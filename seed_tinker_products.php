<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

$products = [
    ['name' => 'Smart Watch Ultra 2', 'price' => 24999, 'status' => 'active'],
    ['name' => 'Wireless Noise Cancelling Headphones', 'price' => 12999, 'status' => 'active'],
    ['name' => 'Mechanical Gaming Keyboard RGB', 'price' => 5499, 'status' => 'active'],
    ['name' => '4K Ultra HD Gaming Monitor 144Hz', 'price' => 38999, 'status' => 'active'],
    ['name' => 'Ergonomic Premium Office Chair', 'price' => 17499, 'status' => 'active'],
];

foreach ($products as $p) {
    Product::create($p);
    echo "Created Product: {$p['name']} (₹{$p['price']})\n";
}

echo "All products created and audited successfully!\n";
