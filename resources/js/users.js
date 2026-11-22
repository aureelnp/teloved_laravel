/**
 * Teloved Users Module
 * Handles user listing and CRUD operations
 */

import { Api } from './api.js';
import { Toast } from './toast.js';
import { formatDate } from './utils.js';

export const UsersPage = {
    users: [],
    loading: false,
    modalMode: 'create', // 'create' | 'edit'
    activeUserId: null,

    init() {
        const root = document.getElementById('users-view-root');
        if (!root) return;

        this.bindEvents();
        this.fetchUsers();
    },

    bindEvents() {
        // Open create modal button
        const btnAdd = document.getElementById('btn-add-user');
        if (btnAdd) {
            btnAdd.addEventListener('click', () => this.openModal('create'));
        }

        // Close modal buttons
        const closeButtons = document.querySelectorAll('[data-close-user-modal]');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', () => this.closeModal());
        });

        // Form submit
        const form = document.getElementById('user-form');
        if (form) {
            form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }
    },

    async fetchUsers() {
        this.loading = true;
        this.renderLoading(true);

        try {
            const res = await Api.getUsers({ per_page: 50 });
            this.users = res.data || [];
            this.renderUsers();
        } catch (err) {
            Toast.error(err.message || 'Gagal memuat daftar pengguna.');
            this.renderError(err.message);
        } finally {
            this.loading = false;
            this.renderLoading(false);
        }
    },

    renderLoading(show) {
        const skeleton = document.getElementById('users-skeleton');
        const content = document.getElementById('users-content-container');
        if (skeleton && content) {
            if (show) {
                skeleton.classList.remove('hidden');
                content.classList.add('hidden');
            } else {
                skeleton.classList.add('hidden');
                content.classList.remove('hidden');
            }
        }
    },

    renderError(msg) {
        const container = document.getElementById('users-content-container');
        if (!container) return;
        container.innerHTML = `
            <div class="py-16 text-center bg-white rounded-2xl border border-rose-100 shadow-sm p-8">
                <div class="w-12 h-12 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke-width="2"/>
                        <line x1="12" y1="8" x2="12" y2="12" stroke-width="2"/>
                        <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2"/>
                    </svg>
                </div>
                <h3 class="font-medium text-stone-900 text-lg">Gagal Memuat Pengguna</h3>
                <p class="text-sm text-stone-500 mt-1 max-w-sm mx-auto">${msg || 'Silakan periksa koneksi server.'}</p>
                <button type="button" onclick="window.UsersPage.fetchUsers()" class="mt-4 px-4 py-2 bg-stone-900 text-white rounded-lg text-xs font-medium hover:bg-stone-800">
                    Coba Lagi
                </button>
            </div>
        `;
    },

    getUserAvatar(user) {
        const name = user.name || 'User';
        const initials = name
            .split(' ')
            .map(n => n[0])
            .slice(0, 2)
            .join('')
            .toUpperCase();

        // Colors hash
        const colors = [
            'bg-stone-100 text-stone-800 border-stone-300',
            'bg-amber-50 text-amber-800 border-amber-200',
            'bg-sky-50 text-sky-800 border-sky-200',
            'bg-emerald-50 text-emerald-800 border-emerald-200',
            'bg-rose-50 text-rose-800 border-rose-200',
            'bg-indigo-50 text-indigo-800 border-indigo-200',
        ];
        const colorClass = colors[(user.id || 0) % colors.length];

        return `<div class="w-9 h-9 rounded-full flex items-center justify-center font-semibold text-xs border ${colorClass} shrink-0">
            ${initials}
        </div>`;
    },

    getUserRoleBadge(user) {
        const isAdmin = user.id === 1 || (user.email && user.email.includes('admin'));
        if (isAdmin) {
            return `
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-stone-900 text-stone-100">
                    <svg class="w-3 h-3 text-amber-400" viewBox="0 0 24 24" fill="currentColor">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    Administrator
                </span>
            `;
        }
        return `
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-stone-100 text-stone-700 border border-stone-200/80">
                Member Teloved
            </span>
        `;
    },

    renderUsers() {
        const container = document.getElementById('users-content-container');
        const emptyState = document.getElementById('users-empty');
        if (!container) return;

        if (this.users.length === 0) {
            container.innerHTML = '';
            if (emptyState) emptyState.classList.remove('hidden');
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');

        const rows = this.users.map(user => {
            const avatar = this.getUserAvatar(user);
            const roleBadge = this.getUserRoleBadge(user);
            const joinedDate = formatDate(user.created_at);

            return `
                <tr class="hover:bg-stone-50/70">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            ${avatar}
                            <div>
                                <p class="text-sm font-semibold text-stone-900">${user.name}</p>
                                <p class="text-xs text-stone-400">ID: #${user.id}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-600">
                        <span class="font-mono text-xs text-stone-700">${user.email}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        ${roleBadge}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs text-stone-500">
                        ${joinedDate}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <div class="flex items-center justify-end gap-1.5">
                            <button type="button"
                                onclick="window.UsersPage.openModal('edit', ${user.id})"
                                class="p-1.5 text-stone-500 hover:text-stone-900 hover:bg-stone-100 rounded-lg"
                                title="Edit Profil Pengguna">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                            <button type="button"
                                onclick="window.UsersPage.confirmDelete(${user.id}, '${user.name.replace(/'/g, "\\'")}')"
                                class="p-1.5 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg"
                                title="Hapus Pengguna">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

        container.innerHTML = `
            <div class="bg-white rounded-2xl border border-stone-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-stone-50/60 border-b border-stone-200/80 text-[11px] font-semibold text-stone-500 uppercase tracking-wider">
                                <th class="px-6 py-3.5">Nama & Profil</th>
                                <th class="px-6 py-3.5">Email</th>
                                <th class="px-6 py-3.5">Role</th>
                                <th class="px-6 py-3.5">Tanggal Bergabung</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            ${rows}
                        </tbody>
                    </table>
                </div>
            </div>
        `;
    },

    openModal(mode = 'create', userId = null) {
        this.modalMode = mode;
        this.activeUserId = userId;

        const modal = document.getElementById('user-modal');
        const titleEl = document.getElementById('user-modal-title');
        const nameInput = document.getElementById('user-name');
        const emailInput = document.getElementById('user-email');
        const passwordInput = document.getElementById('user-password');
        const passwordHint = document.getElementById('user-password-hint');

        this.clearValidationErrors();

        if (mode === 'edit' && userId) {
            const user = this.users.find(u => u.id === userId);
            if (!user) return;

            if (titleEl) titleEl.textContent = 'Edit Profil Pengguna';
            if (nameInput) nameInput.value = user.name || '';
            if (emailInput) emailInput.value = user.email || '';
            if (passwordInput) {
                passwordInput.value = '';
                passwordInput.required = false;
            }
            if (passwordHint) {
                passwordHint.textContent = 'Kosongkan jika tidak ingin mengubah kata sandi.';
                passwordHint.classList.remove('hidden');
            }
        } else {
            if (titleEl) titleEl.textContent = 'Tambah Pengguna Baru';
            if (nameInput) nameInput.value = '';
            if (emailInput) emailInput.value = '';
            if (passwordInput) {
                passwordInput.value = '';
                passwordInput.required = true;
            }
            if (passwordHint) {
                passwordHint.textContent = 'Minimal 8 karakter untuk keamanan akun.';
                passwordHint.classList.remove('hidden');
            }
        }

        if (modal) {
            modal.classList.remove('hidden');
            setTimeout(() => nameInput?.focus(), 100);
        }
    },

    closeModal() {
        const modal = document.getElementById('user-modal');
        if (modal) modal.classList.add('hidden');
        this.clearValidationErrors();
    },

    clearValidationErrors() {
        const errorEls = document.querySelectorAll('[data-user-error]');
        errorEls.forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
        const inputs = document.querySelectorAll('#user-form input');
        inputs.forEach(input => input.classList.remove('border-rose-400', 'focus:ring-rose-200'));
    },

    showValidationError(field, message) {
        const errEl = document.getElementById(`user-error-${field}`);
        const inputEl = document.getElementById(`user-${field}`);
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

        const name = document.getElementById('user-name')?.value.trim();
        const email = document.getElementById('user-email')?.value.trim();
        const password = document.getElementById('user-password')?.value;

        // Strict email regex validation
        const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/;

        let hasError = false;

        if (!name || name.length < 3) {
            this.showValidationError('name', 'Nama pengguna minimal 3 karakter.');
            hasError = true;
        }

        if (!email) {
            this.showValidationError('email', 'Email wajib diisi.');
            hasError = true;
        } else if (!emailRegex.test(email)) {
            this.showValidationError('email', 'Format email tidak valid (contoh: user@domain.com).');
            hasError = true;
        }

        if (this.modalMode === 'create') {
            if (!password) {
                this.showValidationError('password', 'Kata sandi wajib diisi.');
                hasError = true;
            } else if (password.length < 8) {
                this.showValidationError('password', 'Kata sandi minimal 8 karakter.');
                hasError = true;
            }
        } else if (password && password.length < 8) {
            this.showValidationError('password', 'Kata sandi minimal 8 karakter jika ingin diubah.');
            hasError = true;
        }

        if (hasError) return;

        const payload = {
            name,
            email,
        };

        if (password) {
            payload.password = password;
        }

        const submitBtn = document.getElementById('btn-save-user');
        const originalText = submitBtn?.innerHTML || 'Simpan Pengguna';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
        }

        try {
            if (this.modalMode === 'edit') {
                const res = await Api.updateUser(this.activeUserId, payload);
                Toast.success(res.message || 'Profil pengguna berhasil diperbarui.');
            } else {
                const res = await Api.createUser(payload);
                Toast.success(res.message || 'Pengguna baru berhasil ditambahkan.');
            }

            this.closeModal();
            await this.fetchUsers();
        } catch (err) {
            if (err.errors) {
                Object.keys(err.errors).forEach(f => {
                    this.showValidationError(f, err.errors[f][0]);
                });
            }
            Toast.error(err.message || 'Gagal menyimpan pengguna.');
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }
    },

    confirmDelete(id, name) {
        if (id === 1) {
            Toast.error('Akun Administrator utama tidak boleh dihapus demi keamanan sistem.');
            return;
        }

        if (window.ConfirmModal) {
            window.ConfirmModal.show({
                title: 'Hapus Akun Pengguna',
                message: `Apakah Anda yakin ingin menghapus akun "${name}"? Seluruh data profil dan relasi barang akan ikut terhapus.`,
                confirmText: 'Ya, Hapus Pengguna',
                danger: true,
                onConfirm: async () => {
                    try {
                        const res = await Api.deleteUser(id);
                        Toast.success(res.message || 'Pengguna berhasil dihapus.');
                        await this.fetchUsers();
                    } catch (err) {
                        Toast.error(err.message || 'Gagal menghapus pengguna.');
                    }
                },
            });
        } else if (confirm(`Hapus pengguna "${name}"?`)) {
            Api.deleteUser(id)
                .then(() => {
                    Toast.success('Pengguna berhasil dihapus.');
                    this.fetchUsers();
                })
                .catch(err => Toast.error(err.message));
        }
    },
};

window.UsersPage = UsersPage;
