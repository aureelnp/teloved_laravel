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
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-stone-900 text-white text-xs font-medium hover:bg-stone-800 shadow-sm hover:shadow transition-all duration-200">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Tambah Produk Preloved</span>
            </button>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="my-8 space-y-4">
        <!-- Category Filter Pills (Horizontal scrollable) -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none" id="category-filter-pills">
            <button type="button" class="category-pill shrink-0 px-4 py-2 rounded-xl text-xs font-medium bg-stone-900 text-white shadow-xs">
                Semua Kategori
            </button>
            <div class="h-8 w-24 bg-stone-100 rounded-xl animate-pulse"></div>
            <div class="h-8 w-24 bg-stone-100 rounded-xl animate-pulse"></div>
            <div class="h-8 w-24 bg-stone-100 rounded-xl animate-pulse"></div>
        </div>

        <!-- Search Bar and Sort -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
            <!-- Search -->
            <div class="relative w-full sm:w-96">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-stone-400">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <input type="text" 
                       id="product-search-input"
                       placeholder="Cari produk (Kemeja, Jeans, Sneakers...)"
                       class="w-full pl-10 pr-4 py-2.5 bg-white border border-stone-200 rounded-xl text-xs text-stone-900 placeholder:text-stone-400 focus:outline-hidden focus:ring-2 focus:ring-stone-900/10 focus:border-stone-900 transition-all">
            </div>

            <!-- Total count and Sort Select -->
            <div class="w-full sm:w-auto flex items-center justify-between sm:justify-end gap-3">
                <span class="text-xs text-stone-500 font-medium">
                    Total: <strong class="text-stone-900" id="stat-total-products">-</strong> produk
                </span>
                <div class="flex items-center gap-2">
                    <label for="product-sort-select" class="text-xs text-stone-400">Urutkan:</label>
                    <select id="product-sort-select" class="bg-white border border-stone-200 text-xs rounded-xl px-3 py-2 text-stone-800 focus:outline-hidden focus:border-stone-900">
                        <option value="newest">Terbaru</option>
                        <option value="price-low">Harga Terendah</option>
                        <option value="price-high">Harga Tertinggi</option>
                        <option value="stock-high">Stok Terbanyak</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Loading Skeleton -->
    <div id="products-skeleton" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 animate-pulse">
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
    <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"></div>

    <!-- Products Empty State -->
    <div id="products-empty" class="hidden text-center py-20 bg-white rounded-3xl border border-stone-200/80 p-8 shadow-xs">
        <div class="w-14 h-14 bg-stone-100 text-stone-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
        </div>
        <h3 class="text-base font-semibold text-stone-900">Produk Tidak Ditemukan</h3>
        <p class="text-xs text-stone-500 mt-1 max-w-sm mx-auto">
            Tidak ada produk preloved yang sesuai dengan filter atau kata kunci pencarian saat ini.
        </p>
        <div class="mt-4 flex items-center justify-center gap-2">
            <button type="button" onclick="window.ProductsPage.filterByCategory('all'); document.getElementById('product-search-input').value = ''; window.ProductsPage.searchQuery = ''; window.ProductsPage.fetchProducts(1);" class="px-4 py-2 bg-stone-100 text-stone-700 rounded-xl text-xs font-medium hover:bg-stone-200 transition">
                Reset Filter
            </button>
            <button type="button" onclick="document.getElementById('btn-add-product').click()" class="px-4 py-2 bg-stone-900 text-white rounded-xl text-xs font-medium hover:bg-stone-800 transition">
                + Tambah Produk
            </button>
        </div>
    </div>

    <!-- Product Detail Modal -->
    <div id="product-detail-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-stone-900/50 backdrop-blur-xs transition-opacity" data-close-product-detail></div>
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-stone-200 animate-fade-in-scale">
                
                <div class="relative">
                    <!-- Detail Photo -->
                    <div class="w-full h-72 sm:h-80 bg-stone-100 overflow-hidden relative">
                        <img id="detail-product-img" src="" alt="Detail Produk" class="w-full h-full object-cover object-center">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>
                        
                        <!-- Close Button -->
                        <button type="button" class="absolute top-4 right-4 p-2 rounded-full bg-stone-900/60 text-white hover:bg-stone-900 backdrop-blur-md transition-colors" data-close-product-detail>
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>

                        <!-- Floating Badges -->
                        <div class="absolute bottom-4 left-6 right-6 flex items-center justify-between text-white">
                            <span id="detail-product-category" class="px-3 py-1 rounded-full text-xs font-medium backdrop-blur-md bg-white/20 text-white border border-white/30">
                                -
                            </span>
                            <span class="text-xs uppercase tracking-widest px-2.5 py-1 rounded-full bg-emerald-500/90 text-white font-semibold">
                                Preloved Terverifikasi
                            </span>
                        </div>
                    </div>

                    <!-- Detail Body -->
                    <div class="p-6 sm:p-8 space-y-6">
                        <div>
                            <h2 class="text-2xl font-bold text-stone-900" id="detail-product-name">-</h2>
                            <p class="text-xs font-mono text-stone-400 mt-1">Slug: <span id="detail-product-slug">-</span></p>
                        </div>

                        <!-- Price & Stock Row -->
                        <div class="grid grid-cols-2 gap-4 p-4 rounded-2xl bg-stone-50 border border-stone-100">
                            <div>
                                <span class="text-xs text-stone-400 uppercase tracking-wider block font-medium">Harga Preloved</span>
                                <span class="text-xl sm:text-2xl font-bold text-stone-900 mt-0.5 block" id="detail-product-price">Rp 0</span>
                            </div>
                            <div>
                                <span class="text-xs text-stone-400 uppercase tracking-wider block font-medium">Ketersediaan Stok</span>
                                <span class="text-xl sm:text-2xl font-bold text-stone-900 mt-0.5 block" id="detail-product-stock">0 pcs</span>
                            </div>
                        </div>

                        <!-- Seller Info -->
                        <div>
                            <h4 class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-2">Penjual / Pemilik Barang</h4>
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-stone-200/80">
                                <div class="w-8 h-8 rounded-full bg-stone-900 text-white flex items-center justify-center font-bold text-xs">
                                    P
                                </div>
                                <div class="text-xs">
                                    <p class="font-semibold text-stone-900" id="detail-product-seller">-</p>
                                    <p class="text-stone-400">Verified Seller Community</p>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <h4 class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-2">Deskripsi & Kondisi Barang</h4>
                            <p class="text-sm text-stone-600 leading-relaxed bg-stone-50/50 p-4 rounded-xl border border-stone-100" id="detail-product-desc">-</p>
                        </div>

                        <!-- Modal Actions -->
                        <div class="pt-4 border-t border-stone-100 flex items-center justify-end gap-2">
                            <button type="button" 
                                    class="px-4 py-2.5 text-xs font-semibold text-stone-600 hover:text-stone-900 bg-stone-100 hover:bg-stone-200 rounded-xl transition-colors"
                                    data-close-product-detail>
                                Tutup
                            </button>
                            <button type="button" 
                                    id="btn-detail-edit"
                                    class="px-5 py-2.5 text-xs font-semibold text-white bg-stone-900 hover:bg-stone-800 rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                <span>Edit Produk Ini</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Form (Create / Edit Product) -->
    <div id="product-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-stone-900/40 backdrop-blur-xs transition-opacity" data-close-product-modal></div>
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-stone-200 animate-fade-in-scale">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-stone-100">
                    <div>
                        <h3 class="text-base font-bold text-stone-900" id="product-modal-title">Tambah Produk Preloved</h3>
                        <p class="text-xs text-stone-500 mt-0.5">Lengkapi informasi produk preloved fashion untuk katalog</p>
                    </div>
                    <button type="button" class="p-1 rounded-lg text-stone-400 hover:text-stone-700 hover:bg-stone-100 transition-colors" data-close-product-modal>
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
                               class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs text-stone-900 placeholder:text-stone-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-stone-900/10 focus:border-stone-900 transition-all">
                        <p class="hidden text-[11px] text-rose-500 mt-1" id="product-error-name" data-product-error></p>
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="product-slug" class="block text-xs font-semibold text-stone-800 mb-1.5">
                            Slug Produk <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="product-slug" 
                               name="slug" 
                               placeholder="contoh: kemeja-flanel-katun"
                               required
                               class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs font-mono text-stone-800 placeholder:text-stone-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-stone-900/10 focus:border-stone-900 transition-all">
                        <p class="hidden text-[11px] text-rose-500 mt-1" id="product-error-slug" data-product-error></p>
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
                                    class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs text-stone-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-stone-900/10 focus:border-stone-900 transition-all">
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
                                    class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs text-stone-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-stone-900/10 focus:border-stone-900 transition-all">
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
                                       class="w-full pl-10 pr-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs text-stone-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-stone-900/10 focus:border-stone-900 transition-all">
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
                                   class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs text-stone-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-stone-900/10 focus:border-stone-900 transition-all">
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
                                  class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs text-stone-900 placeholder:text-stone-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-stone-900/10 focus:border-stone-900 transition-all"></textarea>
                        <p class="hidden text-[11px] text-rose-500 mt-1" id="product-error-description" data-product-error></p>
                    </div>

                    <!-- Is Active Switch -->
                    <div class="flex items-center justify-between pt-2">
                        <div>
                            <span class="text-xs font-semibold text-stone-800 block">Status Publikasi</span>
                            <span class="text-[11px] text-stone-500 block">Tampilkan produk ini di katalog pembeli</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="product-is-active" name="is_active" class="sr-only peer" checked>
                            <div class="w-10 h-5 bg-stone-200 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-stone-900"></div>
                        </label>
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-5 border-t border-stone-100 flex items-center justify-end gap-2">
                        <button type="button" 
                                class="px-4 py-2 text-xs font-semibold text-stone-600 hover:text-stone-900 bg-stone-100 hover:bg-stone-200 rounded-xl transition-colors"
                                data-close-product-modal>
                            Batal
                        </button>
                        <button type="submit" 
                                id="btn-save-product"
                                class="px-5 py-2 text-xs font-semibold text-white bg-stone-900 hover:bg-stone-800 rounded-xl shadow-xs transition-colors">
                            Simpan Produk
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection
