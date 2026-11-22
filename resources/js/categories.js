/**
 * Teloved Categories Module
 * Handles category listing, modals, and CRUD operations
 */

import { Api } from './api.js';
import { Toast } from './toast.js';
import { getCategoryMeta } from './utils.js';

export const CategoriesPage = {
    categories: [],
    loading: false,
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
            const res = await Api.getCategories({ per_page: 100 });
            this.categories = res.data || [];
            this.renderCategories();
        } catch (err) {
            Toast.error(err.message || 'Gagal mengambil data kategori.');
            this.renderError(err.message);
        } finally {
            this.loading = false;
            this.renderLoading(false);
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
                <button type="button" onclick="window.CategoriesPage.fetchCategories()" class="mt-4 px-4 py-2 bg-stone-900 text-white rounded-lg text-xs font-medium hover:bg-stone-800">
                    Coba Lagi
                </button>
            </div>
        `;
    },

    renderCategories() {
        const grid = document.getElementById('categories-grid');
        const emptyState = document.getElementById('categories-empty');
        if (!grid) return;

        if (this.categories.length === 0) {
            grid.innerHTML = '';
            if (emptyState) emptyState.classList.remove('hidden');
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');

        grid.innerHTML = this.categories.map(category => {
            const meta = getCategoryMeta(category.slug);

            return `
                <div class="bg-white rounded-2xl border border-stone-200/80 p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <!-- Icon -->
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center ${meta.accentBg} mb-4">
                            ${meta.iconSvg}
                        </div>

                        <!-- Name -->
                        <h3 class="text-lg font-semibold text-stone-900">
                            ${category.name}
                        </h3>

                        <!-- Description -->
                        <p class="text-sm text-stone-600 mt-2.5 line-clamp-2 leading-relaxed">
                            ${category.description || 'Kategori pilihan preloved fashion berkualitas.'}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 pt-4 border-t border-stone-100 flex items-center justify-end gap-1">
                        <button type="button"
                            onclick="window.CategoriesPage.openModal('edit', ${category.id})"
                            class="px-3 py-2 text-xs font-medium text-stone-700 bg-stone-50 hover:bg-stone-100 rounded-lg flex items-center gap-1"
                            title="Edit Kategori">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            Edit
                        </button>
                        <button type="button"
                            onclick="window.CategoriesPage.confirmDelete(${category.id}, '${category.name.replace(/'/g, "\\'")}')"
                            class="p-2 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg"
                            title="Hapus Kategori">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
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
        const descInput = document.getElementById('category-description');

        this.clearValidationErrors();

        if (mode === 'edit' && categoryId) {
            const category = this.categories.find(c => c.id === categoryId);
            if (!category) return;

            if (titleEl) titleEl.textContent = 'Edit Kategori Fashion';
            if (nameInput) nameInput.value = category.name || '';
            if (descInput) descInput.value = category.description || '';
        } else {
            if (titleEl) titleEl.textContent = 'Tambah Kategori Fashion';
            if (nameInput) nameInput.value = '';
            if (descInput) descInput.value = '';
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
        const description = document.getElementById('category-description')?.value.trim();

        // Client-side quick validation
        let hasError = false;
        if (!name || name.length < 2) {
            this.showValidationError('name', 'Nama kategori minimal 2 karakter.');
            hasError = true;
        }

        if (hasError) return;

        const payload = {
            name,
            description: description || null,
        };

        const submitBtn = document.getElementById('btn-save-category');
        const originalText = submitBtn?.innerHTML || 'Simpan';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
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

    confirmDelete(id, name) {
        if (window.ConfirmModal) {
            window.ConfirmModal.show({
                title: 'Hapus Kategori Fashion',
                message: `Kategori "${name}" akan dihapus secara permanen.`,
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
