/**
 * Teloved Toast Notification System
 */

export const Toast = {
    container: null,

    init() {
        if (!this.container) {
            this.container = document.getElementById('toast-container');
            if (!this.container) {
                this.container = document.createElement('div');
                this.container.id = 'toast-container';
                this.container.className = 'fixed bottom-5 right-5 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none px-4';
                document.body.appendChild(this.container);
            }
        }
    },

    show(message, type = 'success', duration = 3500) {
        this.init();

        const toast = document.createElement('div');
        toast.className = `pointer-events-auto flex items-start gap-3 p-4 rounded-xl shadow-lg border text-sm backdrop-blur-md animate-toast transition-all duration-300 ${
            type === 'success'
                ? 'bg-white/95 text-stone-900 border-emerald-300 shadow-emerald-950/5'
                : type === 'error'
                ? 'bg-white/95 text-stone-900 border-rose-300 shadow-rose-950/5'
                : 'bg-white/95 text-stone-900 border-stone-300 shadow-stone-950/5'
        }`;

        let iconSvg = '';
        if (type === 'success') {
            iconSvg = `<div class="p-1 rounded-full bg-emerald-100 text-emerald-600 shrink-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>`;
        } else if (type === 'error') {
            iconSvg = `<div class="p-1 rounded-full bg-rose-100 text-rose-600 shrink-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
            </div>`;
        } else {
            iconSvg = `<div class="p-1 rounded-full bg-stone-100 text-stone-600 shrink-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
            </div>`;
        }

        toast.innerHTML = `
            ${iconSvg}
            <div class="flex-1 pt-0.5">
                <p class="font-medium text-stone-900">${type === 'success' ? 'Sukses' : type === 'error' ? 'Pemberitahuan' : 'Info'}</p>
                <p class="text-stone-600 text-xs mt-0.5">${message}</p>
            </div>
            <button type="button" class="text-stone-400 hover:text-stone-700 p-0.5 transition-colors" onclick="this.parentElement.remove()">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        `;

        this.container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(10px)';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    },

    success(message) {
        this.show(message, 'success');
    },

    error(message) {
        this.show(message, 'error', 4500);
    },

    info(message) {
        this.show(message, 'info');
    },
};

window.Toast = Toast;
