/**
 * Teloved API Client
 * Centralized fetch helper for /api/categories, /api/products, and /api/users
 */

function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

export class ApiError extends Error {
    constructor(message, status = 500, errors = {}) {
        super(message);
        this.name = 'ApiError';
        this.status = status;
        this.errors = errors;
    }
}

async function request(endpoint, options = {}) {
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken(),
        ...(options.headers || {}),
    };

    const config = {
        ...options,
        headers,
    };

    try {
        const response = await fetch(endpoint, config);
        const data = await response.json().catch(() => null);

        if (!response.ok) {
            let errorMsg = 'Terjadi kesalahan pada server.';
            let errorsObj = {};

            if (data) {
                if (data.message) {
                    errorMsg = data.message;
                }
                if (data.errors && typeof data.errors === 'object') {
                    errorsObj = data.errors;
                    // Prepend first validation error to message if generic
                    const firstField = Object.keys(data.errors)[0];
                    if (firstField && data.errors[firstField]?.length) {
                        errorMsg = data.errors[firstField][0];
                    }
                }
            }

            throw new ApiError(errorMsg, response.status, errorsObj);
        }

        return data;
    } catch (err) {
        if (err instanceof ApiError) {
            throw err;
        }
        throw new ApiError(err.message || 'Koneksi ke server gagal terhubung.', 0);
    }
}

export const Api = {
    // Categories
    getCategories(params = {}) {
        const qs = new URLSearchParams(params).toString();
        return request(`/api/categories${qs ? '?' + qs : ''}`);
    },
    getCategory(id) {
        return request(`/api/categories/${id}`);
    },
    createCategory(data) {
        return request('/api/categories', {
            method: 'POST',
            body: JSON.stringify(data),
        });
    },
    updateCategory(id, data) {
        return request(`/api/categories/${id}`, {
            method: 'PUT',
            body: JSON.stringify(data),
        });
    },
    deleteCategory(id) {
        return request(`/api/categories/${id}`, {
            method: 'DELETE',
        });
    },

    // Products
    getProducts(params = {}) {
        const qs = new URLSearchParams(params).toString();
        return request(`/api/products${qs ? '?' + qs : ''}`);
    },
    getProduct(id) {
        return request(`/api/products/${id}`);
    },
    createProduct(data) {
        return request('/api/products', {
            method: 'POST',
            body: JSON.stringify(data),
        });
    },
    updateProduct(id, data) {
        return request(`/api/products/${id}`, {
            method: 'PUT',
            body: JSON.stringify(data),
        });
    },
    deleteProduct(id) {
        return request(`/api/products/${id}`, {
            method: 'DELETE',
        });
    },

    // Users
    getUsers(params = {}) {
        const qs = new URLSearchParams(params).toString();
        return request(`/api/users${qs ? '?' + qs : ''}`);
    },
    getUser(id) {
        return request(`/api/users/${id}`);
    },
    createUser(data) {
        return request('/api/users', {
            method: 'POST',
            body: JSON.stringify(data),
        });
    },
    updateUser(id, data) {
        return request(`/api/users/${id}`, {
            method: 'PUT',
            body: JSON.stringify(data),
        });
    },
    deleteUser(id) {
        return request(`/api/users/${id}`, {
            method: 'DELETE',
        });
    },
};
