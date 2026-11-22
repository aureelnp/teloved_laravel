@extends('layouts.app')

@section('title', 'Katalog Produk Preloved Fashion — Teloved')

@section('content')
<div id="products-view-root" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-8 border-b border-stone-200">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-stone-100 text-stone-700 tracking-wide uppercase">
                    Katalog Preloved
                </span>
                <span class="text-xs text-stone-400">•</span>
                <span class="text-xs text-stone-500 font-medium">Curated Wardrobe</span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-stone-900 font-serif sm:font-sans">
                Katalog & Manajemen Produk Preloved
            </h1>
            <p class="text-sm text-stone-500 mt-2 max-w-2xl leading-relaxed">
                Koleksi fashion preloved berkualitas mulai dari Atasan, Bawahan, Sepatu, hingga Tas. Setiap produk melalui kurasi kondisi dan harga transparan.
            </p>
        </div>

        <!-- Add Product Button -->
        <div class="shrink-0 flex items-center gap-3">
            <button type="button"
                    id="btn-add-product"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-stone-900 text-white text-xs font-medium hover:bg-stone-800 shadow-sm">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Tambah Produk Preloved</span>
            </button>
        </div>
    </div>

    <!-- Products Loading Skeleton -->
    <div id="products-skeleton" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-8">
        @for ($i = 0; $i < 8; $i++)
            <div class="bg-white rounded-2xl border border-stone-200/80 overflow-hidden flex flex-col justify-between">
                <div class="w-full aspect-[4/3] bg-stone-100"></div>
                <div class="p-5 space-y-3">
                    <div class="h-4 bg-stone-100 rounded-md w-3/4"></div>
                    <div class="h-3 bg-stone-100 rounded-md w-full"></div>
                    <div class="h-5 bg-stone-100 rounded-md w-1/2 pt-2"></div>
                </div>
            </div>
        @endfor
    </div>

    <!-- Products Grid (Rendered via JS from /api/products) -->
    <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-8"></div>

    <!-- Products Empty State -->
    <div id="products-empty" class="hidden text-center py-20 mt-8 bg-white rounded-3xl border border-stone-200/80 p-8 shadow-xs">
        <div class="w-14 h-14 bg-stone-100 text-stone-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
        </div>
        <h3 class="text-base font-semibold text-stone-900">Belum Ada Produk</h3>
        <p class="text-xs text-stone-500 mt-1 max-w-sm mx-auto">
            Katalog masih kosong. Tambahkan produk preloved pertama Anda melalui tombol di bawah ini.
        </p>
        <button type="button" onclick="document.getElementById('btn-add-product').click()" class="mt-4 px-4 py-2 bg-stone-900 text-white rounded-xl text-xs font-medium hover:bg-stone-800">
            + Tambah Produk
        </button>
    </div>

    <!-- Modal Form (Create / Edit Product) -->
    <div id="product-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-stone-900/40 backdrop-blur-xs" data-close-product-modal></div>
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl sm:my-8 sm:w-full sm:max-w-xl border border-stone-200">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-stone-100">
                    <div>
                        <h3 class="text-base font-bold text-stone-900" id="product-modal-title">Tambah Produk Preloved</h3>
                        <p class="text-xs text-stone-500 mt-0.5">Lengkapi informasi produk preloved fashion untuk katalog</p>
                    </div>
                    <button type="button" class="p-1 rounded-lg text-stone-400 hover:text-stone-700 hover:bg-stone-100" data-close-product-modal>
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <!-- Modal Form Body -->
                <form id="product-form" class="p-6 space-y-4">
                    <!-- Product Name -->
                    <div>
                        <label for="product-name" class="block text-xs font-semibold text-stone-800 mb-1.5">
                            Nama Produk Fashion <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="product-name"
                               name="name"
                               placeholder="Contoh: Kemeja Flanel Katun, Jeans Slim Fit"
                               required
                               class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs text-stone-900 placeholder:text-stone-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-stone-900/10 focus:border-stone-900">
                        <p class="hidden text-[11px] text-rose-500 mt-1" id="product-error-name" data-product-error></p>
                    </div>

                    <!-- Category & User Select (2 Columns) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Category Relation (Required) -->
                        <div>
                            <label for="product-category-id" class="block text-xs font-semibold text-stone-800 mb-1.5">
                                Kategori Fashion <span class="text-rose-500">*</span>
                            </label>
                            <select id="product-category-id"
                                    name="category_id"
                                    required
                                    class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs text-stone-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-stone-900/10 focus:border-stone-900">
                                <option value="">Memuat kategori...</option>
                            </select>
                            <p class="hidden text-[11px] text-rose-500 mt-1" id="product-error-category_id" data-product-error></p>
                        </div>

                        <!-- User Relation (Optional) -->
                        <div>
                            <label for="product-user-id" class="block text-xs font-semibold text-stone-800 mb-1.5">
                                Pemilik / Penjual
                            </label>
                            <select id="product-user-id"
                                    name="user_id"
                                    class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs text-stone-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-stone-900/10 focus:border-stone-900">
                                <option value="">Memuat user...</option>
                            </select>
                            <p class="hidden text-[11px] text-rose-500 mt-1" id="product-error-user_id" data-product-error></p>
                        </div>
                    </div>

                    <!-- Price & Stock (2 Columns) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Price (min: 0) -->
                        <div>
                            <label for="product-price" class="block text-xs font-semibold text-stone-800 mb-1.5">
                                Harga Produk (Rp) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-medium text-stone-400">Rp</span>
                                <input type="number"
                                       id="product-price"
                                       name="price"
                                       min="0"
                                       step="1000"
                                       placeholder="189000"
                                       required
                                       class="w-full pl-10 pr-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs text-stone-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-stone-900/10 focus:border-stone-900">
                            </div>
                            <p class="text-[11px] text-stone-400 mt-1">Minimal Rp 0 (tidak boleh negatif).</p>
                            <p class="hidden text-[11px] text-rose-500 mt-1" id="product-error-price" data-product-error></p>
                        </div>

                        <!-- Stock (min: 0) -->
                        <div>
                            <label for="product-stock" class="block text-xs font-semibold text-stone-800 mb-1.5">
                                Jumlah Stok (pcs) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number"
                                   id="product-stock"
                                   name="stock"
                                   min="0"
                                   step="1"
                                   placeholder="1"
                                   value="1"
                                   required
                                   class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs text-stone-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-stone-900/10 focus:border-stone-900">
                            <p class="text-[11px] text-stone-400 mt-1">Minimal 0 unit.</p>
                            <p class="hidden text-[11px] text-rose-500 mt-1" id="product-error-stock" data-product-error></p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="product-description" class="block text-xs font-semibold text-stone-800 mb-1.5">
                            Deskripsi & Kondisi Preloved
                        </label>
                        <textarea id="product-description"
                                  name="description"
                                  rows="3"
                                  placeholder="Contoh: Kemeja flanel katun lembut kondisi 9/10, warna pekat tanpa minus..."
                                  class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs text-stone-900 placeholder:text-stone-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-stone-900/10 focus:border-stone-900"></textarea>
                        <p class="hidden text-[11px] text-rose-500 mt-1" id="product-error-description" data-product-error></p>
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-5 border-t border-stone-100 flex items-center justify-end gap-2">
                        <button type="button"
                                class="px-4 py-2 text-xs font-semibold text-stone-600 hover:text-stone-900 bg-stone-100 hover:bg-stone-200 rounded-xl"
                                data-close-product-modal>
                            Batal
                        </button>
                        <button type="submit"
                                id="btn-save-product"
                                class="px-5 py-2 text-xs font-semibold text-white bg-stone-900 hover:bg-stone-800 rounded-xl shadow-xs">
                            Simpan Produk
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection
