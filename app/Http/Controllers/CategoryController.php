<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse;

    /**
     * Display a paginated listing of categories.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Category::query()->withCount('products');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 15);
        if ($request->boolean('all') || $request->input('all') === 'true') {
            $perPage = 100;
        }

        $categories = $query->latest('id')->paginate($perPage);

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
