<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed the application's categories.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Atasan', 'slug' => 'atasan', 'description' => 'Kemeja, kaos, sweater, dan atasan lainnya.'],
            ['name' => 'Bawahan', 'slug' => 'bawahan', 'description' => 'Celana, jeans, rok, dan bawahan lainnya.'],
            ['name' => 'Aksesoris', 'slug' => 'aksesoris', 'description' => 'Topi, sabuk, syal, dan pelengkap gaya.'],
            ['name' => 'Sepatu', 'slug' => 'sepatu', 'description' => 'Sneakers, sepatu formal, dan sandal.'],
            ['name' => 'Tas', 'slug' => 'tas', 'description' => 'Tas ransel, tote bag, dan dompet.'],
            ['name' => 'Outerwear', 'slug' => 'outerwear', 'description' => 'Jaket, blazer, dan pakaian luar.'],
        ];

        foreach ($categories as $category) {
            Category::create($category + ['is_active' => true]);
        }
    }
}
