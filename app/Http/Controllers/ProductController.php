<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    use ApiResponse;

    /**
     * Display a paginated listing of products.
     */
    public function index(): JsonResponse
    {
        $products = Product::query()
            ->with(['category', 'user'])
            ->latest('id')
            ->paginate(15);

        return $this->paginated($products, 'Daftar produk berhasil diambil.');
    }

    /**
     * Store a newly created product.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $product = Product::create($request->validated());

            return $this->success(
                $product->refresh()->load(['category', 'user']),
                'Produk berhasil dibuat.',
                201
            );
        } catch (\Throwable $exception) {
            return $this->handleException($exception, 'Gagal membuat produk.');
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): JsonResponse
    {
        return $this->success(
            $product->load(['category', 'user']),
            'Detail produk berhasil diambil.'
        );
    }

    /**
     * Update the specified product.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        try {
            $product->update($request->validated());

            return $this->success(
                $product->refresh()->load(['category', 'user']),
                'Produk berhasil diperbarui.'
            );
        } catch (\Throwable $exception) {
            return $this->handleException($exception, 'Gagal memperbarui produk.');
        }
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            $product->delete();

            return $this->success(null, 'Produk berhasil dihapus.');
        } catch (\Throwable $exception) {
            return $this->handleException($exception, 'Gagal menghapus produk.');
        }
    }
}
