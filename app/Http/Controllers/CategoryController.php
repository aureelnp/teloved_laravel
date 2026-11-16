<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use ApiResponse;

    /**
     * Display a paginated listing of categories.
     */
    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->withCount('products')
            ->latest('id')
            ->paginate(15);

        return $this->paginated($categories, 'Daftar kategori berhasil diambil.');
    }

    /**
     * Store a newly created category.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            $category = Category::create($request->validated());

            return $this->success($category->refresh()->loadCount('products'), 'Kategori berhasil dibuat.', 201);
        } catch (\Throwable $exception) {
            return $this->handleException($exception, 'Gagal membuat kategori.');
        }
    }

    /**
     * Display the specified category with its product count.
     */
    public function show(Category $category): JsonResponse
    {
        $category->loadCount('products');

        return $this->success($category, 'Detail kategori berhasil diambil.');
    }

    /**
     * Update the specified category.
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        try {
            $category->update($request->validated());

            return $this->success($category->refresh()->loadCount('products'), 'Kategori berhasil diperbarui.');
        } catch (\Throwable $exception) {
            return $this->handleException($exception, 'Gagal memperbarui kategori.');
        }
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category): JsonResponse
    {
        try {
            $category->delete();

            return $this->success(null, 'Kategori berhasil dihapus.');
        } catch (\Throwable $exception) {
            return $this->handleException($exception, 'Gagal menghapus kategori.');
        }
    }
}
