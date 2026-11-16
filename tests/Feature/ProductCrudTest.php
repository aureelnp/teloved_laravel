<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_products_with_relations(): void
    {
        Product::factory()->count(2)->create();

        $this->getJson('/api/products')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    ['id', 'name', 'slug', 'price', 'stock', 'is_active', 'category', 'user'],
                ],
            ]);
    }

    public function test_product_can_be_created(): void
    {
        $category = Category::factory()->create();
        $user = User::factory()->create();

        $response = $this->postJson('/api/products', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'name' => 'Laptop Teloved',
            'slug' => 'laptop-teloved',
            'description' => 'Laptop 14 inci.',
            'price' => 7500000,
            'stock' => 12,
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.slug', 'laptop-teloved')
            ->assertJsonPath('data.category.id', $category->id)
            ->assertJsonPath('data.user.id', $user->id)
            ->assertJsonPath('data.stock', 12)
            ->assertJsonPath('data.is_active', true);

        $this->assertDatabaseHas('products', [
            'slug' => 'laptop-teloved',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_product_creation_fails_with_invalid_data(): void
    {
        $this->postJson('/api/products', [
            'category_id' => 999,
            'name' => 'ab',
            'slug' => 'slug tidak valid!',
            'price' => -10,
            'stock' => -1,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['category_id', 'name', 'slug', 'price', 'stock']);
    }

    public function test_product_creation_fails_with_duplicate_slug(): void
    {
        $product = Product::factory()->create();
        $category = Category::factory()->create();

        $this->postJson('/api/products', [
            'category_id' => $category->id,
            'name' => 'Produk Duplikat',
            'slug' => $product->slug,
            'price' => 1000,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('slug');
    }

    public function test_product_can_be_shown(): void
    {
        $product = Product::factory()->create();

        $this->getJson("/api/products/{$product->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.category.id', $product->category_id);
    }

    public function test_showing_missing_product_returns_not_found(): void
    {
        $this->getJson('/api/products/999')
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }

    public function test_product_can_be_updated(): void
    {
        $product = Product::factory()->create(['price' => 1000, 'stock' => 5]);

        $this->patchJson("/api/products/{$product->id}", [
            'price' => 2500.5,
            'stock' => 0,
            'is_active' => false,
        ])
            ->assertOk()
            ->assertJsonPath('data.price', '2500.50')
            ->assertJsonPath('data.stock', 0)
            ->assertJsonPath('data.is_active', false);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 0,
            'is_active' => false,
        ]);
    }

    public function test_product_update_fails_with_invalid_category(): void
    {
        $product = Product::factory()->create();

        $this->putJson("/api/products/{$product->id}", [
            'category_id' => 999,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('category_id');
    }

    public function test_product_can_be_deleted(): void
    {
        $product = Product::factory()->create();

        $this->deleteJson("/api/products/{$product->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->getJson("/api/products/{$product->id}")->assertNotFound();
    }
}
