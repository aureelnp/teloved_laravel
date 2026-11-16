/**
 * Teloved - Curated Preloved Fashion
 * Master Application Scripts
 */

import { Toast } from './toast.js';
import { Api } from './api.js';
import { CategoriesPage } from './categories.js';
import { ProductsPage } from './products.js';
import { UsersPage } from './users.js';

// Confirmation Modal Helper
export const ConfirmModal = {
    element: null,
    onConfirmCallback: null,

    init() {
        this.element = document.getElementById('global-confirm-modal');
        if (!this.element) return;

        const btnCancel = document.getElementById('confirm-modal-cancel');
        const btnConfirm = document.getElementById('confirm-modal-submit');
        const closeX = document.getElementById('confirm-modal-close');

        if (btnCancel) {
            btnCancel.addEventListener('click', () => this.hide());
        }
        if (closeX) {
            closeX.addEventListener('click', () => this.hide());
        }
        if (btnConfirm) {
            btnConfirm.addEventListener('click', () => {
                if (typeof this.onConfirmCallback === 'function') {
                    this.onConfirmCallback();
                }
                this.hide();
            });
        }
    },

    show({ title = 'Konfirmasi Tindakan', message = 'Apakah Anda yakin?', confirmText = 'Ya, Lanjutkan', danger = false, onConfirm }) {
        this.init();
        if (!this.element) return;

        const titleEl = document.getElementById('confirm-modal-title');
        const msgEl = document.getElementById('confirm-modal-message');
        const submitBtn = document.getElementById('confirm-modal-submit');

        if (titleEl) titleEl.textContent = title;
        if (msgEl) msgEl.textContent = message;
        if (submitBtn) {
            submitBtn.textContent = confirmText;
            if (danger) {
                submitBtn.className = 'w-full sm:w-auto inline-flex justify-center rounded-xl bg-rose-600 px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-rose-500 transition-colors';
            } else {
                submitBtn.className = 'w-full sm:w-auto inline-flex justify-center rounded-xl bg-stone-900 px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-stone-800 transition-colors';
            }
        }

        this.onConfirmCallback = onConfirm;
        this.element.classList.remove('hidden');
    },

    hide() {
        if (this.element) {
            this.element.classList.add('hidden');
        }
        this.onConfirmCallback = null;
    },
};

window.ConfirmModal = ConfirmModal;
window.Toast = Toast;
window.Api = Api;

// Global setup on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    ConfirmModal.init();

    // Mobile navigation toggle
    const mobileMenuBtn = document.getElementById('btn-mobile-menu');
    const mobileMenu = document.getElementById('mobile-menu-drawer');
    const mobileMenuClose = document.getElementById('btn-close-mobile-menu');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
    if (mobileMenuClose && mobileMenu) {
        mobileMenuClose.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
        });
    }

    // Auto initialize components based on active page container
    if (document.getElementById('categories-view-root')) {
        CategoriesPage.init();
    }
    if (document.getElementById('products-view-root')) {
        ProductsPage.init();
    }
    if (document.getElementById('users-view-root')) {
        UsersPage.init();
    }
});
