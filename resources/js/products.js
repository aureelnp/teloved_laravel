/**
 * Teloved Products Module
 * Handles product catalog grid, category filtering, detail modal, and CRUD operations
 */

import { Api, ApiError } from './api.js';
import { Toast } from './toast.js';
import { formatRupiah, formatDate, getCategoryMeta, getProductImage, slugify, debounce } from './utils.js';

export const ProductsPage = {
    products: [],
    categories: [],
    users: [],
    loading: false,
    selectedCategory: 'all', // 'all' or category ID
    searchQuery: '',
    sortBy: 'newest',
    modalMode: 'create', // 'create' | 'edit'
    activeProductId: null,
    pagination: {
        currentPage: 1,
        lastPage: 1,
        total: 0,
    },

    async init() {
        const root = document.getElementById('products-view-root');
        if (!root) return;

        // Check if URL has ?category_id=...
        const urlParams = new URLSearchParams(window.location.search);
        const catParam = urlParams.get('category_id');
        if (catParam) {
            this.selectedCategory = catParam;
        }

        this.bindEvents();
        await Promise.all([
            this.loadFilterCategories(),
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

        // Close detail modal buttons
        const closeDetailButtons = document.querySelectorAll('[data-close-product-detail]');
        closeDetailButtons.forEach(btn => {
            btn.addEventListener('click', () => this.closeDetailModal());
        });

        // Search input
        const searchInput = document.getElementById('product-search-input');
        if (searchInput) {
            searchInput.addEventListener('input', debounce((e) => {
                this.searchQuery = e.target.value.trim().toLowerCase();
                this.fetchProducts(1);
            }, 300));
        }

        // Sort select
        const sortSelect = document.getElementById('product-sort-select');
        if (sortSelect) {
            sortSelect.addEventListener('change', (e) => {
                this.sortBy = e.target.value;
                this.renderProducts();
            });
        }

        // Auto generate slug from product name
        const nameInput = document.getElementById('product-name');
        const slugInput = document.getElementById('product-slug');
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
        const form = document.getElementById('product-form');
        if (form) {
            form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }
    },

    async loadFilterCategories() {
        try {
            const res = await Api.getCategories({ per_page: 100 });
            this.categories = res.data || [];
            this.renderCategoryFilterPills();
            this.populateCategorySelect();
        } catch (err) {
            console.error('Failed to load categories for filter', err);
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

    renderCategoryFilterPills() {
        const container = document.getElementById('category-filter-pills');
        if (!container) return;

        let pillsHtml = `
            <button type="button" 
                onclick="window.ProductsPage.filterByCategory('all')"
                class="category-pill shrink-0 px-4 py-2 rounded-xl text-xs font-medium transition-all ${
                    this.selectedCategory === 'all'
                        ? 'bg-stone-900 text-white shadow-sm'
                        : 'bg-white text-stone-600 border border-stone-200 hover:bg-stone-100 hover:text-stone-900'
                }">
                Semua Kategori
            </button>
        `;

        this.categories.forEach(cat => {
            const isSelected = String(this.selectedCategory) === String(cat.id);
            pillsHtml += `
                <button type="button" 
                    onclick="window.ProductsPage.filterByCategory('${cat.id}')"
                    class="category-pill shrink-0 px-4 py-2 rounded-xl text-xs font-medium transition-all ${
                        isSelected
                            ? 'bg-stone-900 text-white shadow-sm'
                            : 'bg-white text-stone-600 border border-stone-200 hover:bg-stone-100 hover:text-stone-900'
                    }">
                    ${cat.name}
                </button>
            `;
        });

        container.innerHTML = pillsHtml;
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

    filterByCategory(categoryId) {
        this.selectedCategory = categoryId;
        this.renderCategoryFilterPills();
        this.fetchProducts(1);
    },

    async fetchProducts(page = 1) {
        this.loading = true;
        this.renderLoading(true);

        const params = {
            per_page: 50,
            page,
        };

        if (this.selectedCategory !== 'all') {
            params.category_id = this.selectedCategory;
        }

        if (this.searchQuery) {
            params.search = this.searchQuery;
        }

        try {
            const res = await Api.getProducts(params);
            this.products = res.data || [];
            if (res.meta) {
                this.pagination = {
                    currentPage: res.meta.current_page || 1,
                    lastPage: res.meta.last_page || 1,
                    total: res.meta.total || this.products.length,
                };
            }
            this.renderProducts();
            this.updateStats();
        } catch (err) {
            Toast.error(err.message || 'Gagal memuat daftar produk.');
            this.renderError(err.message);
        } finally {
            this.loading = false;
            this.renderLoading(false);
        }
    },

    updateStats() {
        const countEl = document.getElementById('stat-total-products');
        if (countEl) {
            countEl.textContent = this.pagination.total || this.products.length;
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
                <button type="button" onclick="window.ProductsPage.fetchProducts()" class="mt-4 px-4 py-2 bg-stone-900 text-white rounded-lg text-xs font-medium hover:bg-stone-800 transition">
                    Coba Lagi
                </button>
            </div>
        `;
    },

    renderProducts() {
        const grid = document.getElementById('products-grid');
        const emptyState = document.getElementById('products-empty');
        if (!grid) return;

        let list = [...this.products];

        // Client sorting if requested
        if (this.sortBy === 'price-low') {
            list.sort((a, b) => Number(a.price) - Number(b.price));
        } else if (this.sortBy === 'price-high') {
            list.sort((a, b) => Number(b.price) - Number(a.price));
        } else if (this.sortBy === 'stock-high') {
            list.sort((a, b) => Number(b.stock) - Number(a.stock));
        }

        if (list.length === 0) {
            grid.innerHTML = '';
            if (emptyState) emptyState.classList.remove('hidden');
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');

        grid.innerHTML = list.map(product => {
            const meta = getCategoryMeta(product.category?.slug);
            const imageUrl = getProductImage(product);
            const isOutOfStock = Number(product.stock) <= 0;
            const isLowStock = Number(product.stock) > 0 && Number(product.stock) <= 10;

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
            } else if (isLowStock) {
                stockBadge = `
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                        Sisa ${product.stock}
                    </span>
                `;
            }

            return `
                <div class="group relative bg-white rounded-2xl border border-stone-200/80 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                    <!-- Photo Header & Badges -->
                    <div class="relative w-full aspect-[4/3] bg-stone-100 overflow-hidden cursor-pointer" onclick="window.ProductsPage.openDetailModal(${product.id})">
                        <img src="${imageUrl}" alt="${product.name}" 
                             class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                             loading="lazy"
                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1445205170230-053b83016050?w=600&auto=format&fit=crop&q=80';" />
                        
                        <!-- Floating Category Badge -->
                        <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-medium backdrop-blur-md shadow-xs border ${meta.badgeBg}">
                                ${product.category?.name || 'Preloved'}
                            </span>
                            <span class="px-2 py-1 rounded-full text-[10px] font-semibold tracking-wider uppercase bg-stone-900/80 text-white backdrop-blur-md">
                                Preloved
                            </span>
                        </div>

                        <!-- Quick view trigger overlay on hover -->
                        <div class="absolute inset-0 bg-stone-950/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="px-3.5 py-1.5 rounded-full bg-white/95 text-stone-900 text-xs font-medium shadow-md flex items-center gap-1.5 transform translate-y-2 group-hover:translate-y-0 transition-transform">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                Detail Produk
                            </span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-2 mb-1.5">
                                <h3 class="font-semibold text-stone-900 text-base group-hover:text-stone-700 transition-colors line-clamp-1 cursor-pointer" onclick="window.ProductsPage.openDetailModal(${product.id})">
                                    ${product.name}
                                </h3>
                            </div>
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
                            <div class="flex items-center gap-2">
                                <button type="button" 
                                    onclick="window.ProductsPage.openDetailModal(${product.id})"
                                    class="flex-1 py-2 px-3 text-xs font-medium text-stone-700 bg-stone-50 hover:bg-stone-100 rounded-xl transition-colors flex items-center justify-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-stone-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Detail
                                </button>
                                <button type="button" 
                                    onclick="window.ProductsPage.openModal('edit', ${product.id})"
                                    class="p-2 text-stone-500 hover:text-stone-900 hover:bg-stone-100 rounded-xl transition-colors"
                                    title="Edit Produk">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>
                                <button type="button" 
                                    onclick="window.ProductsPage.confirmDelete(${product.id}, '${product.name.replace(/'/g, "\\'")}')"
                                    class="p-2 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors"
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

    openDetailModal(productId) {
        const product = this.products.find(p => p.id === productId);
        if (!product) return;

        const modal = document.getElementById('product-detail-modal');
        if (!modal) return;

        const meta = getCategoryMeta(product.category?.slug);
        const imageUrl = getProductImage(product);

        document.getElementById('detail-product-img').src = imageUrl;
        document.getElementById('detail-product-name').textContent = product.name;
        document.getElementById('detail-product-category').textContent = product.category?.name || 'Preloved Fashion';
        document.getElementById('detail-product-category').className = `px-3 py-1 rounded-full text-xs font-medium border ${meta.badgeBg}`;
        document.getElementById('detail-product-price').textContent = formatRupiah(product.price);
        document.getElementById('detail-product-stock').textContent = `${product.stock} pcs`;
        document.getElementById('detail-product-slug').textContent = product.slug;
        document.getElementById('detail-product-seller').textContent = product.user ? `${product.user.name} (${product.user.email})` : 'Teloved Official Store';
        document.getElementById('detail-product-desc').textContent = product.description || 'Tidak ada deskripsi tambahan.';

        // Edit button inside detail modal
        const editBtn = document.getElementById('btn-detail-edit');
        if (editBtn) {
            editBtn.onclick = () => {
                this.closeDetailModal();
                this.openModal('edit', product.id);
            };
        }

        modal.classList.remove('hidden');
    },

    closeDetailModal() {
        const modal = document.getElementById('product-detail-modal');
        if (modal) modal.classList.add('hidden');
    },

    openModal(mode = 'create', productId = null) {
        this.modalMode = mode;
        this.activeProductId = productId;

        const modal = document.getElementById('product-modal');
        const titleEl = document.getElementById('product-modal-title');
        const nameInput = document.getElementById('product-name');
        const slugInput = document.getElementById('product-slug');
        const catSelect = document.getElementById('product-category-id');
        const userSelect = document.getElementById('product-user-id');
        const priceInput = document.getElementById('product-price');
        const stockInput = document.getElementById('product-stock');
        const descInput = document.getElementById('product-description');
        const activeCheck = document.getElementById('product-is-active');

        this.clearValidationErrors();

        if (mode === 'edit' && productId) {
            const product = this.products.find(p => p.id === productId);
            if (!product) return;

            if (titleEl) titleEl.textContent = 'Edit Produk Preloved';
            if (nameInput) nameInput.value = product.name || '';
            if (slugInput) {
                slugInput.value = product.slug || '';
                slugInput.dataset.manual = 'true';
            }
            if (catSelect) catSelect.value = product.category_id || product.category?.id || '';
            if (userSelect) userSelect.value = product.user_id || product.user?.id || '';
            if (priceInput) priceInput.value = product.price || '';
            if (stockInput) stockInput.value = product.stock ?? 0;
            if (descInput) descInput.value = product.description || '';
            if (activeCheck) activeCheck.checked = product.is_active !== false;
        } else {
            if (titleEl) titleEl.textContent = 'Tambah Produk Preloved Baru';
            if (nameInput) nameInput.value = '';
            if (slugInput) {
                slugInput.value = '';
                delete slugInput.dataset.manual;
            }
            if (catSelect) {
                // Default to selected category if filtered
                catSelect.value = this.selectedCategory !== 'all' ? this.selectedCategory : '';
            }
            if (userSelect) userSelect.value = '';
            if (priceInput) priceInput.value = '';
            if (stockInput) stockInput.value = '1';
            if (descInput) descInput.value = '';
            if (activeCheck) activeCheck.checked = true;
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
        const slug = document.getElementById('product-slug')?.value.trim();
        const category_id = document.getElementById('product-category-id')?.value;
        const user_id = document.getElementById('product-user-id')?.value || null;
        const price = document.getElementById('product-price')?.value;
        const stock = document.getElementById('product-stock')?.value;
        const description = document.getElementById('product-description')?.value.trim();
        const is_active = document.getElementById('product-is-active')?.checked ?? true;

        let hasError = false;

        // Strict client-side validation
        if (!name || name.length < 3) {
            this.showValidationError('name', 'Nama produk minimal 3 karakter.');
            hasError = true;
        }

        if (!slug) {
            this.showValidationError('slug', 'Slug wajib diisi.');
            hasError = true;
        } else if (!/^[a-zA-Z0-9_-]+$/.test(slug)) {
            this.showValidationError('slug', 'Slug hanya boleh huruf, angka, strip (-), dan garis bawah (_).');
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
            slug,
            category_id: Number(category_id),
            user_id: user_id ? Number(user_id) : null,
            price: numPrice,
            stock: numStock,
            description: description || null,
            is_active: Boolean(is_active),
        };

        const submitBtn = document.getElementById('btn-save-product');
        const originalText = submitBtn?.innerHTML || 'Simpan Produk';
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
