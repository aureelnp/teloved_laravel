/**
 * Teloved Categories Module
 * Handles category listing, modals, CRUD operations, and fashion styling
 */

import { Api, ApiError } from './api.js';
import { Toast } from './toast.js';
import { getCategoryMeta, slugify, debounce } from './utils.js';

export const CategoriesPage = {
    categories: [],
    loading: false,
    searchQuery: '',
    modalMode: 'create', // 'create' | 'edit'
    activeCategoryId: null,

    init() {
        const root = document.getElementById('categories-view-root');
        if (!root) return;

        this.bindEvents();
        this.fetchCategories();
    },

    bindEvents() {
        // Open create modal button
        const btnAdd = document.getElementById('btn-add-category');
        if (btnAdd) {
            btnAdd.addEventListener('click', () => this.openModal('create'));
        }

        // Close modal buttons
        const closeButtons = document.querySelectorAll('[data-close-category-modal]');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', () => this.closeModal());
        });

        // Search input with debounce
        const searchInput = document.getElementById('category-search-input');
        if (searchInput) {
            searchInput.addEventListener('input', debounce((e) => {
                this.searchQuery = e.target.value.trim().toLowerCase();
                this.renderCategories();
            }, 250));
        }

        // Auto generate slug from category name
        const nameInput = document.getElementById('category-name');
        const slugInput = document.getElementById('category-slug');
        if (nameInput && slugInput) {
            nameInput.addEventListener('input', (e) => {
                if (this.modalMode === 'create' || !slugInput.dataset.manual) {
                    slugInput.value = slugify(e.target.value);
                }
            });
            slugInput.addEventListener('input', () => {
                slugInput.dataset.manual = 'true';
            });
        }

        // Form submission
        const form = document.getElementById('category-form');
        if (form) {
            form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }
    },

    async fetchCategories() {
        this.loading = true;
        this.renderLoading(true);

        try {
            // Get all categories with per_page=100
            const res = await Api.getCategories({ per_page: 100 });
            this.categories = res.data || [];
            this.renderCategories();
            this.updateStats();
        } catch (err) {
            Toast.error(err.message || 'Gagal mengambil data kategori.');
            this.renderError(err.message);
        } finally {
            this.loading = false;
            this.renderLoading(false);
        }
    },

    updateStats() {
        const totalEl = document.getElementById('stat-total-categories');
        const activeEl = document.getElementById('stat-active-categories');
        const productsCountEl = document.getElementById('stat-total-products-in-categories');

        if (totalEl) totalEl.textContent = this.categories.length;
        if (activeEl) {
            const activeCount = this.categories.filter(c => c.is_active !== false).length;
            activeEl.textContent = activeCount;
        }
        if (productsCountEl) {
            const totalProds = this.categories.reduce((acc, c) => acc + (c.products_count || 0), 0);
            productsCountEl.textContent = totalProds;
        }
    },

    renderLoading(show) {
        const skeleton = document.getElementById('categories-skeleton');
        const grid = document.getElementById('categories-grid');
        if (skeleton && grid) {
            if (show) {
                skeleton.classList.remove('hidden');
                grid.classList.add('hidden');
            } else {
                skeleton.classList.add('hidden');
                grid.classList.remove('hidden');
            }
        }
    },

    renderError(msg) {
        const grid = document.getElementById('categories-grid');
        if (!grid) return;
        grid.innerHTML = `
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-rose-100 shadow-sm p-8">
                <div class="w-12 h-12 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke-width="2"/>
                        <line x1="12" y1="8" x2="12" y2="12" stroke-width="2"/>
                        <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2"/>
                    </svg>
                </div>
                <h3 class="font-medium text-stone-900 text-lg">Gagal Memuat Kategori</h3>
                <p class="text-sm text-stone-500 mt-1 max-w-sm mx-auto">${msg || 'Terjadi gangguan saat mengambil data.'}</p>
                <button type="button" onclick="window.CategoriesPage.fetchCategories()" class="mt-4 px-4 py-2 bg-stone-900 text-white rounded-lg text-xs font-medium hover:bg-stone-800 transition">
                    Coba Lagi
                </button>
            </div>
        `;
    },

    renderCategories() {
        const grid = document.getElementById('categories-grid');
        const emptyState = document.getElementById('categories-empty');
        if (!grid) return;

        let filtered = this.categories;
        if (this.searchQuery) {
            filtered = this.categories.filter(c =>
                c.name.toLowerCase().includes(this.searchQuery) ||
                (c.slug && c.slug.toLowerCase().includes(this.searchQuery)) ||
                (c.description && c.description.toLowerCase().includes(this.searchQuery))
            );
        }

        if (filtered.length === 0) {
            grid.innerHTML = '';
            if (emptyState) emptyState.classList.remove('hidden');
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');

        grid.innerHTML = filtered.map(category => {
            const meta = getCategoryMeta(category.slug);
            const count = category.products_count || 0;
            const isActive = category.is_active !== false;

            return `
                <div class="group relative bg-white rounded-2xl border border-stone-200/80 p-6 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between overflow-hidden">
                    <!-- Subtle top accent bar -->
                    <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-stone-900/10 via-stone-900/40 to-stone-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div>
                        <!-- Header with Minimalist Icon & Status -->
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center ${meta.accentBg} transition-transform group-hover:scale-105 duration-300">
                                ${meta.iconSvg}
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border ${isActive ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-stone-100 text-stone-500 border-stone-200'}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 ${isActive ? 'bg-emerald-500' : 'bg-stone-400'}"></span>
                                    ${isActive ? 'Aktif' : 'Nonaktif'}
                                </span>
                            </div>
                        </div>

                        <!-- Name & Slug -->
                        <h3 class="text-lg font-semibold text-stone-900 group-hover:text-stone-700 transition-colors flex items-center gap-2">
                            ${category.name}
                        </h3>
                        <p class="text-xs font-mono text-stone-400 mt-0.5">slug: ${category.slug}</p>

                        <!-- Description -->
                        <p class="text-sm text-stone-600 mt-2.5 line-clamp-2 leading-relaxed">
                            ${category.description || 'Kategori pilihan preloved fashion berkualitas.'}
                        </p>
                    </div>

                    <!-- Footer Info & Actions -->
                    <div class="mt-6 pt-4 border-t border-stone-100 flex items-center justify-between">
                        <!-- Product Count Pill -->
                        <a href="/products?category_id=${category.id}" class="inline-flex items-center gap-1 text-xs font-medium text-stone-700 hover:text-stone-900 bg-stone-50 hover:bg-stone-100 px-2.5 py-1.5 rounded-lg transition-colors" title="Lihat produk dalam kategori ini">
                            <svg class="w-3.5 h-3.5 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                            <span>${count} Produk</span>
                        </a>

                        <!-- Action Buttons (Edit / Delete) -->
                        <div class="flex items-center gap-1">
                            <button type="button" 
                                onclick="window.CategoriesPage.openModal('edit', ${category.id})" 
                                class="p-2 text-stone-500 hover:text-stone-900 hover:bg-stone-100 rounded-lg transition-colors"
                                title="Edit Kategori">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                            <button type="button" 
                                onclick="window.CategoriesPage.confirmDelete(${category.id}, '${category.name.replace(/'/g, "\\'")}', ${count})" 
                                class="p-2 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                title="Hapus Kategori">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    },

    openModal(mode = 'create', categoryId = null) {
        this.modalMode = mode;
        this.activeCategoryId = categoryId;

        const modal = document.getElementById('category-modal');
        const titleEl = document.getElementById('category-modal-title');
        const nameInput = document.getElementById('category-name');
        const slugInput = document.getElementById('category-slug');
        const descInput = document.getElementById('category-description');
        const activeCheck = document.getElementById('category-is-active');

        this.clearValidationErrors();

        if (mode === 'edit' && categoryId) {
            const category = this.categories.find(c => c.id === categoryId);
            if (!category) return;

            if (titleEl) titleEl.textContent = 'Edit Kategori Fashion';
            if (nameInput) nameInput.value = category.name || '';
            if (slugInput) {
                slugInput.value = category.slug || '';
                slugInput.dataset.manual = 'true';
            }
            if (descInput) descInput.value = category.description || '';
            if (activeCheck) activeCheck.checked = category.is_active !== false;
        } else {
            if (titleEl) titleEl.textContent = 'Tambah Kategori Fashion';
            if (nameInput) nameInput.value = '';
            if (slugInput) {
                slugInput.value = '';
                delete slugInput.dataset.manual;
            }
            if (descInput) descInput.value = '';
            if (activeCheck) activeCheck.checked = true;
        }

        if (modal) {
            modal.classList.remove('hidden');
            setTimeout(() => nameInput?.focus(), 100);
        }
    },

    closeModal() {
        const modal = document.getElementById('category-modal');
        if (modal) modal.classList.add('hidden');
        this.clearValidationErrors();
    },

    clearValidationErrors() {
        const errorEls = document.querySelectorAll('[data-category-error]');
        errorEls.forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
        const inputs = document.querySelectorAll('#category-form input, #category-form textarea');
        inputs.forEach(input => input.classList.remove('border-rose-400', 'focus:ring-rose-200'));
    },

    showValidationError(field, message) {
        const errEl = document.getElementById(`category-error-${field}`);
        const inputEl = document.getElementById(`category-${field}`);
        if (errEl) {
            errEl.textContent = message;
            errEl.classList.remove('hidden');
        }
        if (inputEl) {
            inputEl.classList.add('border-rose-400', 'focus:ring-rose-200');
        }
    },

    async handleFormSubmit(e) {
        e.preventDefault();
        this.clearValidationErrors();

        const name = document.getElementById('category-name')?.value.trim();
        const slug = document.getElementById('category-slug')?.value.trim();
        const description = document.getElementById('category-description')?.value.trim();
        const is_active = document.getElementById('category-is-active')?.checked ?? true;

        // Client-side quick validation
        let hasError = false;
        if (!name || name.length < 2) {
            this.showValidationError('name', 'Nama kategori minimal 2 karakter.');
            hasError = true;
        }
        if (!slug) {
            this.showValidationError('slug', 'Slug kategori wajib diisi.');
            hasError = true;
        } else if (!/^[a-zA-Z0-9_-]+$/.test(slug)) {
            this.showValidationError('slug', 'Slug hanya boleh berisi huruf, angka, strip, dan garis bawah.');
            hasError = true;
        }

        if (hasError) return;

        const payload = {
            name,
            slug,
            description: description || null,
            is_active: Boolean(is_active),
        };

        const submitBtn = document.getElementById('btn-save-category');
        const originalText = submitBtn?.innerHTML || 'Simpan';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg> Menyimpan...
            `;
        }

        try {
            if (this.modalMode === 'edit') {
                const res = await Api.updateCategory(this.activeCategoryId, payload);
                Toast.success(res.message || 'Kategori berhasil diperbarui.');
            } else {
                const res = await Api.createCategory(payload);
                Toast.success(res.message || 'Kategori berhasil ditambahkan.');
            }

            this.closeModal();
            await this.fetchCategories();
        } catch (err) {
            if (err.errors) {
                Object.keys(err.errors).forEach(f => {
                    this.showValidationError(f, err.errors[f][0]);
                });
            }
            Toast.error(err.message || 'Gagal menyimpan kategori.');
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }
    },

    confirmDelete(id, name, productCount = 0) {
        let warningText = `Kategori "${name}" akan dihapus secara permanen.`;
        if (productCount > 0) {
            warningText += ` Perhatian: Ada ${productCount} produk yang terhubung dengan kategori ini.`;
        }

        if (window.ConfirmModal) {
            window.ConfirmModal.show({
                title: 'Hapus Kategori Fashion',
                message: warningText,
                confirmText: 'Ya, Hapus Kategori',
                danger: true,
                onConfirm: async () => {
                    try {
                        const res = await Api.deleteCategory(id);
                        Toast.success(res.message || 'Kategori berhasil dihapus.');
                        await this.fetchCategories();
                    } catch (err) {
                        Toast.error(err.message || 'Gagal menghapus kategori.');
                    }
                },
            });
        } else if (confirm(`Hapus kategori "${name}"?`)) {
            Api.deleteCategory(id)
                .then(() => {
                    Toast.success('Kategori berhasil dihapus.');
                    this.fetchCategories();
                })
                .catch(err => Toast.error(err.message));
        }
    },
};

window.CategoriesPage = CategoriesPage;
