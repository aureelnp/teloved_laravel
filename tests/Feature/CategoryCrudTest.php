<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_categories_with_product_counts(): void
    {
        $category = Category::factory()->create();
        Product::factory()->count(2)->create(['category_id' => $category->id]);

        $this->getJson('/api/categories')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.products_count', 2);
    }

    public function test_category_can_be_created(): void
    {
        $this->postJson('/api/categories', [
            'name' => 'Aksesoris Komputer',
            'slug' => 'aksesoris-komputer',
            'description' => 'Keyboard, mouse, dan lainnya.',
        ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.slug', 'aksesoris-komputer')
            ->assertJsonPath('data.is_active', true);

        $this->assertDatabaseHas('categories', ['slug' => 'aksesoris-komputer']);
    }

    public function test_category_creation_fails_with_invalid_data(): void
    {
        $this->postJson('/api/categories', [
            'name' => 'X',
            'slug' => 'slug tidak valid!',
            'is_active' => 'bukan-boolean',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'slug', 'is_active']);
    }

    public function test_category_creation_fails_with_duplicate_slug(): void
    {
        Category::factory()->create(['slug' => 'duplikat']);

        $this->postJson('/api/categories', [
            'name' => 'Kategori Lain',
            'slug' => 'duplikat',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('slug');
    }

    public function test_category_can_be_shown(): void
    {
        $category = Category::factory()->create();

        $this->getJson("/api/categories/{$category->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $category->id);
    }

    public function test_showing_missing_category_returns_not_found(): void
    {
        $this->getJson('/api/categories/999')
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }

    public function test_category_can_be_updated(): void
    {
        $category = Category::factory()->create(['name' => 'Nama Lama', 'slug' => 'nama-lama']);

        $this->putJson("/api/categories/{$category->id}", [
            'name' => 'Nama Baru',
            'slug' => 'nama-baru',
            'is_active' => false,
        ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Nama Baru')
            ->assertJsonPath('data.slug', 'nama-baru')
            ->assertJsonPath('data.is_active', false);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Nama Baru',
            'is_active' => false,
        ]);
    }

    public function test_category_update_fails_with_duplicate_name(): void
    {
        Category::factory()->create(['name' => 'Sudah Ada']);
        $category = Category::factory()->create();

        $this->putJson("/api/categories/{$category->id}", [
            'name' => 'Sudah Ada',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name');
    }

    public function test_deleting_category_removes_its_products(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->deleteJson("/api/categories/{$category->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->getJson("/api/categories/{$category->id}")->assertNotFound();
    }
}
