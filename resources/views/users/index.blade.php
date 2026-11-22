@extends('layouts.app')

@section('title', 'Manajemen User & Profil — Teloved')

@section('content')
<div id="users-view-root" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-8 border-b border-stone-200">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-stone-100 text-stone-700 tracking-wide uppercase">
                    Manajemen Akun
                </span>
                <span class="text-xs text-stone-400">•</span>
                <span class="text-xs text-stone-500 font-medium">Komunitas Preloved</span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-stone-900 font-serif sm:font-sans">
                Manajemen User & Profil Pengguna
            </h1>
            <p class="text-sm text-stone-500 mt-2 max-w-2xl leading-relaxed">
                Kelola daftar pengguna terdaftar, administrator sistem, dan anggota komunitas preloved fashion Teloved.
            </p>
        </div>

        <!-- Add User Button -->
        <div class="shrink-0 flex items-center gap-3">
            <button type="button"
                    id="btn-add-user"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-stone-900 text-white text-xs font-medium hover:bg-stone-800 shadow-sm">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <line x1="19" y1="8" x2="19" y2="14"></line>
                    <line x1="22" y1="11" x2="16" y2="11"></line>
                </svg>
                <span>Tambah Pengguna Baru</span>
            </button>
        </div>
    </div>

    <!-- Loading Skeleton -->
    <div id="users-skeleton" class="bg-white rounded-2xl border border-stone-200/80 p-6 space-y-4 mt-8">
        <div class="h-6 bg-stone-100 rounded-md w-1/4"></div>
        @for ($i = 0; $i < 5; $i++)
            <div class="h-12 bg-stone-50 rounded-xl w-full"></div>
        @endfor
    </div>

    <!-- Content Container (Table rendered via JS from /api/users) -->
    <div id="users-content-container" class="mt-8"></div>

    <!-- Empty State -->
    <div id="users-empty" class="hidden text-center py-20 mt-8 bg-white rounded-3xl border border-stone-200/80 p-8 shadow-xs">
        <div class="w-14 h-14 bg-stone-100 text-stone-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
            </svg>
        </div>
        <h3 class="text-base font-semibold text-stone-900">Belum Ada Pengguna</h3>
        <p class="text-xs text-stone-500 mt-1 max-w-sm mx-auto">
            Belum ada pengguna terdaftar. Silakan tambahkan pengguna pertama Anda.
        </p>
        <button type="button" onclick="document.getElementById('btn-add-user').click()" class="mt-4 px-4 py-2 bg-stone-900 text-white rounded-xl text-xs font-medium hover:bg-stone-800">
            + Tambah Pengguna
        </button>
    </div>

    <!-- Modal Form (Create / Edit User) -->
    <div id="user-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-stone-900/40 backdrop-blur-xs" data-close-user-modal></div>
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl sm:my-8 sm:w-full sm:max-w-lg border border-stone-200">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-stone-100">
                    <div>
                        <h3 class="text-base font-bold text-stone-900" id="user-modal-title">Tambah Pengguna Baru</h3>
                        <p class="text-xs text-stone-500 mt-0.5">Lengkapi profil dan otentikasi akun pengguna</p>
                    </div>
                    <button type="button" class="p-1 rounded-lg text-stone-400 hover:text-stone-700 hover:bg-stone-100" data-close-user-modal>
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <!-- Modal Form Body -->
                <form id="user-form" class="p-6 space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="user-name" class="block text-xs font-semibold text-stone-800 mb-1.5">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="user-name"
                               name="name"
                               placeholder="Contoh: Budi Santoso"
                               required
                               class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs text-stone-900 placeholder:text-stone-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-stone-900/10 focus:border-stone-900">
                        <p class="text-[11px] text-stone-400 mt-1">Minimal 3 karakter.</p>
                        <p class="hidden text-[11px] text-rose-500 mt-1" id="user-error-name" data-user-error></p>
                    </div>

                    <!-- Email (Strict validation) -->
                    <div>
                        <label for="user-email" class="block text-xs font-semibold text-stone-800 mb-1.5">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email"
                               id="user-email"
                               name="email"
                               placeholder="user@example.com"
                               required
                               class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs text-stone-900 placeholder:text-stone-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-stone-900/10 focus:border-stone-900">
                        <p class="text-[11px] text-stone-400 mt-1">Harus format email valid dan belum terdaftar.</p>
                        <p class="hidden text-[11px] text-rose-500 mt-1" id="user-error-email" data-user-error></p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="user-password" class="block text-xs font-semibold text-stone-800 mb-1.5">
                            Kata Sandi
                        </label>
                        <input type="password"
                               id="user-password"
                               name="password"
                               placeholder="••••••••"
                               class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs text-stone-900 placeholder:text-stone-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-stone-900/10 focus:border-stone-900">
                        <p class="text-[11px] text-stone-400 mt-1" id="user-password-hint">Minimal 8 karakter.</p>
                        <p class="hidden text-[11px] text-rose-500 mt-1" id="user-error-password" data-user-error></p>
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-5 border-t border-stone-100 flex items-center justify-end gap-2">
                        <button type="button"
                                class="px-4 py-2 text-xs font-semibold text-stone-600 hover:text-stone-900 bg-stone-100 hover:bg-stone-200 rounded-xl"
                                data-close-user-modal>
                            Batal
                        </button>
                        <button type="submit"
                                id="btn-save-user"
                                class="px-5 py-2 text-xs font-semibold text-white bg-stone-900 hover:bg-stone-800 rounded-xl shadow-xs">
                            Simpan Pengguna
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection
