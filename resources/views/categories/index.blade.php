@extends('layouts.app')

@section('title', 'Kategori Produk Fashion — Teloved')

@section('content')
<div id="categories-view-root" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-8 border-b border-stone-200">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-stone-100 text-stone-700 tracking-wide uppercase">
                    E-Commerce Fashion
                </span>
                <span class="text-xs text-stone-400">•</span>
                <span class="text-xs text-stone-500 font-medium">Kurasi Kategori</span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-stone-900 font-serif sm:font-sans">
                Kategori Produk Fashion
            </h1>
            <p class="text-sm text-stone-500 mt-2 max-w-2xl leading-relaxed">
                Manajemen klasifikasi pakaian dan aksesoris preloved Teloved. Kategorisasi terstruktur memudahkan pencarian dan kurasi item fashion pilihan.
            </p>
        </div>

        <!-- Add Category Button -->
        <div class="shrink-0 flex items-center gap-3">
            <button type="button"
                    id="btn-add-category"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-stone-900 text-white text-xs font-medium hover:bg-stone-800 shadow-sm">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Tambah Kategori Baru</span>
            </button>
        </div>
    </div>

    <!-- Loading Skeleton -->
    <div id="categories-skeleton" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
        @for ($i = 0; $i < 6; $i++)
            <div class="bg-white rounded-2xl border border-stone-200/80 p-6 h-56 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-stone-100 rounded-xl mb-4"></div>
                    <div class="h-5 bg-stone-100 rounded-md w-3/4 mb-2"></div>
                    <div class="h-4 bg-stone-100 rounded-md w-full mb-1"></div>
                    <div class="h-4 bg-stone-100 rounded-md w-4/5"></div>
                </div>
                <div class="h-8 bg-stone-100 rounded-lg w-full mt-4"></div>
            </div>
        @endfor
    </div>

    <!-- Categories Grid (Rendered via JS from /api/categories) -->
    <div id="categories-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8"></div>

    <!-- Empty State -->
    <div id="categories-empty" class="hidden text-center py-20 mt-8 bg-white rounded-3xl border border-stone-200/80 p-8 shadow-xs">
        <div class="w-14 h-14 bg-stone-100 text-stone-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="m15 9-6 6"></path>
                <path d="m9 9 6 6"></path>
            </svg>
        </div>
        <h3 class="text-base font-semibold text-stone-900">Belum Ada Kategori</h3>
        <p class="text-xs text-stone-500 mt-1 max-w-sm mx-auto">
            Daftar kategori masih kosong. Silakan tambahkan kategori fashion pertama Anda.
        </p>
        <button type="button" onclick="document.getElementById('btn-add-category').click()" class="mt-4 px-4 py-2 bg-stone-900 text-white rounded-xl text-xs font-medium hover:bg-stone-800">
            + Tambah Kategori
        </button>
    </div>

    <!-- Modal Form (Create / Edit Category) -->
    <div id="category-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-stone-900/40 backdrop-blur-xs" data-close-category-modal></div>
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl sm:my-8 sm:w-full sm:max-w-lg border border-stone-200">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-stone-100">
                    <div>
                        <h3 class="text-base font-bold text-stone-900" id="category-modal-title">Tambah Kategori Fashion</h3>
                        <p class="text-xs text-stone-500 mt-0.5">Kelola data kategori untuk katalog preloved</p>
                    </div>
                    <button type="button" class="p-1 rounded-lg text-stone-400 hover:text-stone-700 hover:bg-stone-100" data-close-category-modal>
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <!-- Modal Form Body -->
                <form id="category-form" class="p-6 space-y-4">
                    <!-- Category Name -->
                    <div>
                        <label for="category-name" class="block text-xs font-semibold text-stone-800 mb-1.5">
                            Nama Kategori <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="category-name"
                               name="name"
                               placeholder="Contoh: Outerwear, Atasan, Sepatu"
                               required
                               class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs text-stone-900 placeholder:text-stone-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-stone-900/10 focus:border-stone-900">
                        <p class="hidden text-[11px] text-rose-500 mt-1" id="category-error-name" data-category-error></p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="category-description" class="block text-xs font-semibold text-stone-800 mb-1.5">
                            Deskripsi Kategori (Opsional)
                        </label>
                        <textarea id="category-description"
                                  name="description"
                                  rows="3"
                                  placeholder="Keterangan singkat mengenai produk yang termasuk kategori ini..."
                                  class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs text-stone-900 placeholder:text-stone-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-stone-900/10 focus:border-stone-900"></textarea>
                        <p class="hidden text-[11px] text-rose-500 mt-1" id="category-error-description" data-category-error></p>
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-5 border-t border-stone-100 flex items-center justify-end gap-2">
                        <button type="button"
                                class="px-4 py-2 text-xs font-semibold text-stone-600 hover:text-stone-900 bg-stone-100 hover:bg-stone-200 rounded-xl"
                                data-close-category-modal>
                            Batal
                        </button>
                        <button type="submit"
                                id="btn-save-category"
                                class="px-5 py-2 text-xs font-semibold text-white bg-stone-900 hover:bg-stone-800 rounded-xl shadow-xs">
                            Simpan Kategori
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection
