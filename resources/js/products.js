/**
 * Teloved Products Module
 * Handles product catalog grid and CRUD operations
 */

import { Api } from './api.js';
import { Toast } from './toast.js';
import { formatRupiah, getProductImage } from './utils.js';

export const ProductsPage = {
    products: [],
    categories: [],
    users: [],
    loading: false,
    modalMode: 'create', // 'create' | 'edit'
    activeProductId: null,

    async init() {
        const root = document.getElementById('products-view-root');
        if (!root) return;

        this.bindEvents();
        await Promise.all([
            this.loadCategories(),
            this.loadUsersForDropdown(),
        ]);
        await this.fetchProducts();
    },

    bindEvents() {
        // Open create modal button
        const btnAdd = document.getElementById('btn-add-product');
        if (btnAdd) {
            btnAdd.addEventListener('click', () => this.openModal('create'));
        }

        // Close modal buttons
        const closeButtons = document.querySelectorAll('[data-close-product-modal]');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', () => this.closeModal());
        });

        // Form submission
        const form = document.getElementById('product-form');
        if (form) {
            form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }
    },

    async loadCategories() {
        try {
            const res = await Api.getCategories({ per_page: 100 });
            this.categories = res.data || [];
            this.populateCategorySelect();
        } catch (err) {
            console.error('Failed to load categories', err);
        }
    },

    async loadUsersForDropdown() {
        try {
            const res = await Api.getUsers({ per_page: 50 });
            this.users = res.data || [];
            this.populateUserSelect();
        } catch (err) {
            console.error('Failed to load users for product owner', err);
        }
    },

    populateCategorySelect() {
        const select = document.getElementById('product-category-id');
        if (!select) return;

        select.innerHTML = '<option value="">-- Pilih Kategori Fashion --</option>' +
            this.categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
    },

    populateUserSelect() {
        const select = document.getElementById('product-user-id');
        if (!select) return;

        select.innerHTML = '<option value="">-- Tanpa Penjual Khusus (Opsional) --</option>' +
            this.users.map(u => `<option value="${u.id}">${u.name} (${u.email})</option>`).join('');
    },

    async fetchProducts() {
        this.loading = true;
        this.renderLoading(true);

        try {
            const res = await Api.getProducts({ per_page: 50 });
            this.products = res.data || [];
            this.renderProducts();
        } catch (err) {
            Toast.error(err.message || 'Gagal memuat daftar produk.');
            this.renderError(err.message);
        } finally {
            this.loading = false;
            this.renderLoading(false);
        }
    },

    renderLoading(show) {
        const skeleton = document.getElementById('products-skeleton');
        const grid = document.getElementById('products-grid');
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
        const grid = document.getElementById('products-grid');
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
                <h3 class="font-medium text-stone-900 text-lg">Gagal Mengambil Produk</h3>
                <p class="text-sm text-stone-500 mt-1 max-w-sm mx-auto">${msg || 'Silakan periksa koneksi server.'}</p>
                <button type="button" onclick="window.ProductsPage.fetchProducts()" class="mt-4 px-4 py-2 bg-stone-900 text-white rounded-lg text-xs font-medium hover:bg-stone-800">
                    Coba Lagi
                </button>
            </div>
        `;
    },

    renderProducts() {
        const grid = document.getElementById('products-grid');
        const emptyState = document.getElementById('products-empty');
        if (!grid) return;

        if (this.products.length === 0) {
            grid.innerHTML = '';
            if (emptyState) emptyState.classList.remove('hidden');
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');

        grid.innerHTML = this.products.map(product => {
            const imageUrl = getProductImage(product);
            const isOutOfStock = Number(product.stock) <= 0;

            let stockBadge = `
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-stone-100 text-stone-700">
                    Stok: ${product.stock}
                </span>
            `;
            if (isOutOfStock) {
                stockBadge = `
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-rose-50 text-rose-700 border border-rose-200">
                        Habis
                    </span>
                `;
            }

            return `
                <div class="bg-white rounded-2xl border border-stone-200/80 overflow-hidden shadow-sm flex flex-col justify-between">
                    <!-- Photo Header -->
                    <div class="relative w-full aspect-[4/3] bg-stone-100 overflow-hidden">
                        <img src="${imageUrl}" alt="${product.name}"
                             class="w-full h-full object-cover object-center"
                             loading="lazy"
                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1445205170230-053b83016050?w=600&auto=format&fit=crop&q=80';" />
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-semibold text-stone-900 text-base line-clamp-1">
                                ${product.name}
                            </h3>
                            <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed mb-3">
                                ${product.description || 'Barang preloved fashion terkurasi dengan kondisi prima.'}
                            </p>
                        </div>

                        <!-- Price & Stock Row -->
                        <div class="pt-3 border-t border-stone-100">
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <div>
                                    <span class="text-[11px] uppercase tracking-wider text-stone-400 font-medium block">Harga</span>
                                    <span class="text-base font-bold text-stone-900">
                                        ${formatRupiah(product.price)}
                                    </span>
                                </div>
                                <div>
                                    ${stockBadge}
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end gap-2">
                                <button type="button"
                                    onclick="window.ProductsPage.openModal('edit', ${product.id})"
                                    class="px-3 py-2 text-xs font-medium text-stone-700 bg-stone-50 hover:bg-stone-100 rounded-xl flex items-center gap-1"
                                    title="Edit Produk">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                    Edit
                                </button>
                                <button type="button"
                                    onclick="window.ProductsPage.confirmDelete(${product.id}, '${product.name.replace(/'/g, "\\'")}')"
                                    class="p-2 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl"
                                    title="Hapus Produk">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    },

    openModal(mode = 'create', productId = null) {
        this.modalMode = mode;
        this.activeProductId = productId;

        const modal = document.getElementById('product-modal');
        const titleEl = document.getElementById('product-modal-title');
        const nameInput = document.getElementById('product-name');
        const catSelect = document.getElementById('product-category-id');
        const userSelect = document.getElementById('product-user-id');
        const priceInput = document.getElementById('product-price');
        const stockInput = document.getElementById('product-stock');
        const descInput = document.getElementById('product-description');

        this.clearValidationErrors();

        if (mode === 'edit' && productId) {
            const product = this.products.find(p => p.id === productId);
            if (!product) return;

            if (titleEl) titleEl.textContent = 'Edit Produk Preloved';
            if (nameInput) nameInput.value = product.name || '';
            if (catSelect) catSelect.value = product.category_id || product.category?.id || '';
            if (userSelect) userSelect.value = product.user_id || product.user?.id || '';
            if (priceInput) priceInput.value = product.price || '';
            if (stockInput) stockInput.value = product.stock ?? 0;
            if (descInput) descInput.value = product.description || '';
        } else {
            if (titleEl) titleEl.textContent = 'Tambah Produk Preloved Baru';
            if (nameInput) nameInput.value = '';
            if (catSelect) catSelect.value = '';
            if (userSelect) userSelect.value = '';
            if (priceInput) priceInput.value = '';
            if (stockInput) stockInput.value = '1';
            if (descInput) descInput.value = '';
        }

        if (modal) {
            modal.classList.remove('hidden');
            setTimeout(() => nameInput?.focus(), 100);
        }
    },

    closeModal() {
        const modal = document.getElementById('product-modal');
        if (modal) modal.classList.add('hidden');
        this.clearValidationErrors();
    },

    clearValidationErrors() {
        const errorEls = document.querySelectorAll('[data-product-error]');
        errorEls.forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
        const inputs = document.querySelectorAll('#product-form input, #product-form select, #product-form textarea');
        inputs.forEach(input => input.classList.remove('border-rose-400', 'focus:ring-rose-200'));
    },

    showValidationError(field, message) {
        const errEl = document.getElementById(`product-error-${field}`);
        const inputEl = document.getElementById(`product-${field}`);
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

        const name = document.getElementById('product-name')?.value.trim();
        const category_id = document.getElementById('product-category-id')?.value;
        const user_id = document.getElementById('product-user-id')?.value || null;
        const price = document.getElementById('product-price')?.value;
        const stock = document.getElementById('product-stock')?.value;
        const description = document.getElementById('product-description')?.value.trim();

        let hasError = false;

        // Strict client-side validation
        if (!name || name.length < 3) {
            this.showValidationError('name', 'Nama produk minimal 3 karakter.');
            hasError = true;
        }

        if (!category_id) {
            this.showValidationError('category_id', 'Kategori produk fashion wajib dipilih.');
            hasError = true;
        }

        const numPrice = Number(price);
        if (price === '' || isNaN(numPrice)) {
            this.showValidationError('price', 'Harga wajib diisi dengan angka valid.');
            hasError = true;
        } else if (numPrice < 0) {
            this.showValidationError('price', 'Harga tidak boleh negatif (minimal 0).');
            hasError = true;
        }

        const numStock = Number(stock);
        if (stock === '' || isNaN(numStock)) {
            this.showValidationError('stock', 'Stok wajib diisi dengan angka valid.');
            hasError = true;
        } else if (numStock < 0) {
            this.showValidationError('stock', 'Stok tidak boleh negatif (minimal 0).');
            hasError = true;
        }

        if (hasError) return;

        const payload = {
            name,
            category_id: Number(category_id),
            user_id: user_id ? Number(user_id) : null,
            price: numPrice,
            stock: numStock,
            description: description || null,
        };

        const submitBtn = document.getElementById('btn-save-product');
        const originalText = submitBtn?.innerHTML || 'Simpan Produk';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
        }

        try {
            if (this.modalMode === 'edit') {
                const res = await Api.updateProduct(this.activeProductId, payload);
                Toast.success(res.message || 'Produk berhasil diperbarui.');
            } else {
                const res = await Api.createProduct(payload);
                Toast.success(res.message || 'Produk berhasil ditambahkan.');
            }

            this.closeModal();
            await this.fetchProducts();
        } catch (err) {
            if (err.errors) {
                Object.keys(err.errors).forEach(f => {
                    this.showValidationError(f, err.errors[f][0]);
                });
            }
            Toast.error(err.message || 'Gagal menyimpan produk.');
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
                title: 'Hapus Produk Preloved',
                message: `Apakah Anda yakin ingin menghapus produk "${name}" dari katalog? Tindakan ini tidak dapat dibatalkan.`,
                confirmText: 'Ya, Hapus Produk',
                danger: true,
                onConfirm: async () => {
                    try {
                        const res = await Api.deleteProduct(id);
                        Toast.success(res.message || 'Produk berhasil dihapus.');
                        await this.fetchProducts();
                    } catch (err) {
                        Toast.error(err.message || 'Gagal menghapus produk.');
                    }
                },
            });
        } else if (confirm(`Hapus produk "${name}"?`)) {
            Api.deleteProduct(id)
                .then(() => {
                    Toast.success('Produk berhasil dihapus.');
                    this.fetchProducts();
                })
                .catch(err => Toast.error(err.message));
        }
    },
};

window.ProductsPage = ProductsPage;
