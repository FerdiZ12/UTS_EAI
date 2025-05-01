<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'Cuci Sultan',
            'description' => 'Cuci Eksterior Menyeluruh, Detailing Interior, Polishing, Pengkilapan, Pembersihan dan Perawatan Mesin Ringan',
            'price' => 200000,
        ]);

        Product::create([
            'name' => 'Cuci Premium',
            'description' => 'Cuci Eksterior Menyeluruh, Detailing Interior, Polishing dan Pengkilapan',
            'price' => 150000,
        ]);

        Product::create([
            'name' => 'Cuci Biasa',
            'description' => 'Cuci Eksterior Menyeluruh dan Detailing Interior',
            'price' => 100000,
        ]);
    }
}
