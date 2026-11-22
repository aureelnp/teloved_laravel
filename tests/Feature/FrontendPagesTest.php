<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_redirects_to_categories(): void
    {
        $this->get('/')->assertRedirect('/categories');
    }

    public function test_categories_frontend_page_can_be_rendered(): void
    {
        $response = $this->get('/categories');
        $response->assertOk();
        $response->assertSee('Kategori Produk Fashion');
        $response->assertSee('categories-grid');
        $response->assertSee('btn-add-category');
    }

    public function test_products_frontend_page_can_be_rendered(): void
    {
        $response = $this->get('/products');
        $response->assertOk();
        $response->assertSee('Katalog');
        $response->assertSee('products-grid');
        $response->assertSee('btn-add-product');
    }

    public function test_users_frontend_page_can_be_rendered(): void
    {
        $response = $this->get('/users');
        $response->assertOk();
        $response->assertSee('Manajemen User');
        $response->assertSee('users-content-container');
        $response->assertSee('btn-add-user');
    }

    public function test_products_api_can_filter_by_category(): void
    {
        $cat1 = Category::factory()->create(['name' => 'Atasan', 'slug' => 'atasan']);
        $cat2 = Category::factory()->create(['name' => 'Bawahan', 'slug' => 'bawahan']);

        Product::factory()->create(['category_id' => $cat1->id, 'name' => 'Kemeja Flanel']);
        Product::factory()->create(['category_id' => $cat2->id, 'name' => 'Jeans Slim']);

        $response = $this->getJson("/api/products?category_id={$cat1->id}");
        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.name', 'Kemeja Flanel');
    }

    public function test_products_api_can_search(): void
    {
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id, 'name' => 'Sneakers Putih']);
        Product::factory()->create(['category_id' => $category->id, 'name' => 'Kaos Hitam']);

        $response = $this->getJson('/api/products?search=Sneakers');
        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.name', 'Sneakers Putih');
    }

    public function test_users_api_can_search_by_name_or_email(): void
    {
        User::factory()->create(['name' => 'Diana Prince', 'email' => 'diana@example.com']);
        User::factory()->create(['name' => 'Bruce Wayne', 'email' => 'bruce@example.com']);

        $response = $this->getJson('/api/users?search=Diana');
        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.name', 'Diana Prince');
    }
}
