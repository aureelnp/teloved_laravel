@extends('layouts.app')

@section('title', 'Teloved — Reimagining Curated Preloved Fashion')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">

    <!-- Hero Banner Section -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-stone-900 via-stone-900 to-stone-800 text-white p-8 sm:p-14 mb-12 shadow-xl border border-stone-800">
        <div class="absolute -right-20 -bottom-20 w-96 h-96 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -top-20 w-80 h-80 rounded-full bg-stone-700/20 blur-2xl pointer-events-none"></div>

        <div class="relative max-w-2xl">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-amber-200 border border-white/15 backdrop-blur-md mb-6">
                <span>✦</span>
                <span>Curated Fashion Platform</span>
            </span>

            <h1 class="text-3xl sm:text-5xl font-bold tracking-tight leading-tight sm:leading-tight font-serif sm:font-sans">
                Teloved: Reimagining Preloved Fashion.
            </h1>

            <p class="text-stone-300 text-sm sm:text-base mt-4 leading-relaxed">
                Platform manajemen dan etalase fashion preloved terkurasi. Mengedepankan gaya hidup berkelanjutan, kualitas terverifikasi, dan desain minimalis modern.
            </p>

            <div class="mt-8 flex flex-wrap items-center gap-3">
                <a href="{{ route('products.view') }}" 
                   class="px-6 py-3 rounded-xl bg-white text-stone-900 font-semibold text-xs sm:text-sm hover:bg-stone-100 shadow-md transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    <span>Katalog Produk</span>
                </a>
                <a href="{{ route('categories.view') }}" 
                   class="px-6 py-3 rounded-xl bg-stone-800/80 text-white font-medium text-xs sm:text-sm hover:bg-stone-700/80 border border-white/20 backdrop-blur-md transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span>Kelola Kategori</span>
                </a>
                <a href="{{ route('users.view') }}" 
                   class="px-6 py-3 rounded-xl bg-stone-800/80 text-white font-medium text-xs sm:text-sm hover:bg-stone-700/80 border border-white/20 backdrop-blur-md transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                    </svg>
                    <span>Pengguna & Profil</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 3 Core Management Modules Overview -->
    <div class="mb-12">
        <div class="flex items-end justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-stone-900">Modul Frontend Teloved</h2>
                <p class="text-xs text-stone-500 mt-1">Tiga modul utama yang terintegrasi langsung dengan REST API</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1: Categories -->
            <a href="{{ route('categories.view') }}" class="group bg-white rounded-3xl border border-stone-200/80 p-7 shadow-xs hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-700 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                    </div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-bold text-stone-900 group-hover:text-stone-700 transition-colors">1. Kategori Fashion</h3>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-sky-100 text-sky-800">API Active</span>
                    </div>
                    <p class="text-xs text-stone-500 mt-2 leading-relaxed">
                        Tampilan grid card terstruktur untuk Atasan, Bawahan, Aksesoris, Sepatu, Tas, dan Outerwear dengan ilustrasi minimalis dan modal manajemen CRUD.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-stone-100 flex items-center justify-between text-xs font-semibold text-stone-900">
                    <span>Buka Halaman Kategori</span>
                    <span class="group-hover:translate-x-1 transition-transform">→</span>
                </div>
            </a>

            <!-- Card 2: Products -->
            <a href="{{ route('products.view') }}" class="group bg-white rounded-3xl border border-stone-200/80 p-7 shadow-xs hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-bold text-stone-900 group-hover:text-stone-700 transition-colors">2. Katalog Produk</h3>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-800">API Active</span>
                    </div>
                    <p class="text-xs text-stone-500 mt-2 leading-relaxed">
                        Katalog produk preloved fashion dengan foto terkurasi, badge kategori, harga Rupiah, kontrol stok, filter kategori instan, dan form modal terintegrasi.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-stone-100 flex items-center justify-between text-xs font-semibold text-stone-900">
                    <span>Buka Katalog Produk</span>
                    <span class="group-hover:translate-x-1 transition-transform">→</span>
                </div>
            </a>

            <!-- Card 3: Users -->
            <a href="{{ route('users.view') }}" class="group bg-white rounded-3xl border border-stone-200/80 p-7 shadow-xs hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-bold text-stone-900 group-hover:text-stone-700 transition-colors">3. Manajemen User</h3>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">API Active</span>
                    </div>
                    <p class="text-xs text-stone-500 mt-2 leading-relaxed">
                        Tabel data dan kartu profil pengguna, email validasi ketat, manajemen role (Administrator / Member), dan aksi edit/hapus intuitif.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-stone-100 flex items-center justify-between text-xs font-semibold text-stone-900">
                    <span>Buka Manajemen User</span>
                    <span class="group-hover:translate-x-1 transition-transform">→</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Featured Categories Quick Preview -->
    <div class="bg-white rounded-3xl border border-stone-200/80 p-8 shadow-xs">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-base font-bold text-stone-900">Koleksi Kategori Fashion Teloved</h3>
                <p class="text-xs text-stone-500 mt-0.5">Enam kategori pilar busana preloved berkualitas</p>
            </div>
            <a href="{{ route('categories.view') }}" class="text-xs font-semibold text-stone-900 hover:underline">
                Lihat Semua Kategori →
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <a href="/products?category_id=1" class="p-4 rounded-2xl bg-stone-50 hover:bg-stone-100 text-center transition-colors group">
                <span class="text-2xl mb-1 block group-hover:scale-110 transition-transform">👕</span>
                <span class="text-xs font-semibold text-stone-900 block">Atasan</span>
                <span class="text-[10px] text-stone-400">Tops & Tees</span>
            </a>
            <a href="/products?category_id=2" class="p-4 rounded-2xl bg-stone-50 hover:bg-stone-100 text-center transition-colors group">
                <span class="text-2xl mb-1 block group-hover:scale-110 transition-transform">👖</span>
                <span class="text-xs font-semibold text-stone-900 block">Bawahan</span>
                <span class="text-[10px] text-stone-400">Pants & Jeans</span>
            </a>
            <a href="/products?category_id=3" class="p-4 rounded-2xl bg-stone-50 hover:bg-stone-100 text-center transition-colors group">
                <span class="text-2xl mb-1 block group-hover:scale-110 transition-transform">🕶️</span>
                <span class="text-xs font-semibold text-stone-900 block">Aksesoris</span>
                <span class="text-[10px] text-stone-400">Belts & Caps</span>
            </a>
            <a href="/products?category_id=4" class="p-4 rounded-2xl bg-stone-50 hover:bg-stone-100 text-center transition-colors group">
                <span class="text-2xl mb-1 block group-hover:scale-110 transition-transform">👟</span>
                <span class="text-xs font-semibold text-stone-900 block">Sepatu</span>
                <span class="text-[10px] text-stone-400">Sneakers</span>
            </a>
            <a href="/products?category_id=5" class="p-4 rounded-2xl bg-stone-50 hover:bg-stone-100 text-center transition-colors group">
                <span class="text-2xl mb-1 block group-hover:scale-110 transition-transform">👜</span>
                <span class="text-xs font-semibold text-stone-900 block">Tas</span>
                <span class="text-[10px] text-stone-400">Totes & Bags</span>
            </a>
            <a href="/products?category_id=6" class="p-4 rounded-2xl bg-stone-50 hover:bg-stone-100 text-center transition-colors group">
                <span class="text-2xl mb-1 block group-hover:scale-110 transition-transform">🧥</span>
                <span class="text-xs font-semibold text-stone-900 block">Outerwear</span>
                <span class="text-[10px] text-stone-400">Jackets & Coats</span>
            </a>
        </div>
    </div>

</div>
@endsection
