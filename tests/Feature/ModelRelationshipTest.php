<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_has_many_products(): void
    {
        $category = Category::factory()->create();
        $products = Product::factory()->count(3)->create(['category_id' => $category->id]);

        $this->assertCount(3, $category->products);
        $this->assertTrue($category->products->first()->is($products->first()));
    }

    public function test_product_belongs_to_category_and_user(): void
    {
        $product = Product::factory()->create();

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertInstanceOf(User::class, $product->user);
    }

    public function test_user_has_many_products(): void
    {
        $user = User::factory()->create();
        Product::factory()->count(2)->create(['user_id' => $user->id]);

        $this->assertCount(2, $user->products);
    }

    public function test_deleting_user_keeps_products_but_clears_owner(): void
    {
        $product = Product::factory()->create();

        $product->user->delete();

        $this->assertNull($product->fresh()->user_id);
    }
}
