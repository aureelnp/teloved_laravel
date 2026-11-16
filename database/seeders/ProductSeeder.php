<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Seed the application's products.
     */
    public function run(): void
    {
        $products = [
            'atasan' => [
                ['name' => 'Kemeja Flanel', 'description' => 'Kemeja flanel lengan panjang berbahan katun.', 'price' => 189000, 'stock' => 45],
                ['name' => 'Kaos Basic', 'description' => 'Kaos katun polos dengan potongan reguler.', 'price' => 79000, 'stock' => 120],
                ['name' => 'Sweater Rajut', 'description' => 'Sweater rajut hangat untuk cuaca dingin.', 'price' => 229000, 'stock' => 38],
                ['name' => 'Kemeja Putih Formal', 'description' => 'Kemeja putih lengan panjang untuk acara formal.', 'price' => 249000, 'stock' => 30],
                ['name' => 'Polo Shirt', 'description' => 'Polo shirt berkerah dengan bahan nyaman.', 'price' => 149000, 'stock' => 55],
                ['name' => 'Blouse Wanita', 'description' => 'Blouse longgar dengan bahan adem.', 'price' => 179000, 'stock' => 42],
                ['name' => 'Hoodie Zipper', 'description' => 'Hoodie beritsleting dengan kantong depan.', 'price' => 259000, 'stock' => 27],
                ['name' => 'Cardigan Rajut', 'description' => 'Cardigan rajut tipis untuk gaya santai.', 'price' => 199000, 'stock' => 33],
            ],
            'bawahan' => [
                ['name' => 'Jeans Slim Fit', 'description' => 'Celana jeans biru dengan potongan slim.', 'price' => 289000, 'stock' => 50],
                ['name' => 'Kulot Linen', 'description' => 'Kulot berbahan linen yang ringan dan adem.', 'price' => 169000, 'stock' => 40],
                ['name' => 'Celana Chino', 'description' => 'Celana chino polos untuk gaya kasual.', 'price' => 219000, 'stock' => 46],
                ['name' => 'Rok Plisket', 'description' => 'Rok plisket panjang dengan bahan jatuh.', 'price' => 179000, 'stock' => 35],
                ['name' => 'Jogger Pants', 'description' => 'Celana jogger santai dengan karet di ujung.', 'price' => 159000, 'stock' => 60],
                ['name' => 'Celana Pendek Katun', 'description' => 'Celana pendek katun untuk aktivitas harian.', 'price' => 119000, 'stock' => 75],
                ['name' => 'Rok Midi', 'description' => 'Rok midi sederhana untuk gaya sehari-hari.', 'price' => 189000, 'stock' => 28],
            ],
            'aksesoris' => [
                ['name' => 'Topi Baseball', 'description' => 'Topi baseball dengan tali penyesuai.', 'price' => 89000, 'stock' => 80],
                ['name' => 'Sabuk Kulit', 'description' => 'Sabuk kulit dengan gesper logam sederhana.', 'price' => 139000, 'stock' => 44],
                ['name' => 'Kaus Kaki', 'description' => 'Kaus kaki katun satu pasang.', 'price' => 39000, 'stock' => 150],
                ['name' => 'Syal Rajut', 'description' => 'Syal rajut lembut untuk cuaca dingin.', 'price' => 99000, 'stock' => 52],
                ['name' => 'Sarung Tangan', 'description' => 'Sarung tangan rajut tanpa jari.', 'price' => 69000, 'stock' => 48],
                ['name' => 'Ikat Rambut', 'description' => 'Ikat rambut elastis isi tiga buah.', 'price' => 29000, 'stock' => 100],
                ['name' => 'Kacamata Hitam', 'description' => 'Kacamata hitam dengan lensa UV protection.', 'price' => 159000, 'stock' => 36],
            ],
            'sepatu' => [
                ['name' => 'Sneakers Putih', 'description' => 'Sepatu sneakers putih untuk gaya kasual.', 'price' => 429000, 'stock' => 32],
                ['name' => 'Sepatu Formal Kulit', 'description' => 'Sepatu formal berbahan kulit dengan sol tipis.', 'price' => 549000, 'stock' => 22],
                ['name' => 'Sandal Slide', 'description' => 'Sandal slide ringan untuk pemakaian harian.', 'price' => 129000, 'stock' => 65],
                ['name' => 'Boots Kulit', 'description' => 'Boots kulit setinggi mata kaki.', 'price' => 679000, 'stock' => 18],
                ['name' => 'Sepatu Running', 'description' => 'Sepatu lari dengan sol empuk dan ringan.', 'price' => 489000, 'stock' => 25],
                ['name' => 'Slip On Kanvas', 'description' => 'Sepatu slip on kanvas tanpa tali.', 'price' => 239000, 'stock' => 41],
            ],
            'tas' => [
                ['name' => 'Tote Bag Kanvas', 'description' => 'Tote bag kanvas besar untuk bawaan harian.', 'price' => 149000, 'stock' => 54],
                ['name' => 'Tas Ransel', 'description' => 'Tas ransel dengan kompartemen laptop.', 'price' => 329000, 'stock' => 30],
                ['name' => 'Sling Bag', 'description' => 'Tas selempang kecil dengan tali panjang.', 'price' => 189000, 'stock' => 43],
                ['name' => 'Dompet Kulit', 'description' => 'Dompet lipat berbahan kulit sintetis.', 'price' => 119000, 'stock' => 70],
                ['name' => 'Tas Selempang', 'description' => 'Tas selempang serbaguna untuk bepergian.', 'price' => 229000, 'stock' => 26],
                ['name' => 'Clutch Pesta', 'description' => 'Clutch kecil untuk acara pesta.', 'price' => 199000, 'stock' => 20],
            ],
            'outerwear' => [
                ['name' => 'Jaket Denim', 'description' => 'Jaket denim klasik dengan potongan reguler.', 'price' => 399000, 'stock' => 24],
                ['name' => 'Blazer Formal', 'description' => 'Blazer formal dengan satu kancing depan.', 'price' => 459000, 'stock' => 19],
                ['name' => 'Jaket Bomber', 'description' => 'Jaket bomber ringan dengan karet di pergelangan.', 'price' => 349000, 'stock' => 28],
                ['name' => 'Trench Coat', 'description' => 'Trench coat panjang dengan sabuk pinggang.', 'price' => 589000, 'stock' => 12],
                ['name' => 'Jaket Varsity', 'description' => 'Jaket varsity bergaya kasual dengan aksen warna.', 'price' => 379000, 'stock' => 21],
                ['name' => 'Vest Rajut', 'description' => 'Vest rajut tanpa lengan untuk lapisan tambahan.', 'price' => 179000, 'stock' => 34],
            ],
        ];

        $users = User::all();

        foreach ($products as $categorySlug => $items) {
            $category = Category::where('slug', $categorySlug)->firstOrFail();

            foreach ($items as $item) {
                Product::create([
                    'user_id' => $users->random()->id,
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'slug' => Str::slug($item['name']),
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
