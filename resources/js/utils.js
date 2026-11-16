/**
 * Teloved Fashion Frontend Utility Functions
 */

export function formatRupiah(number) {
    const num = Number(number) || 0;
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(num);
}

export function formatDate(dateString) {
    if (!dateString) return '-';
    try {
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        }).format(date);
    } catch (e) {
        return dateString;
    }
}

export function slugify(text) {
    return (text || '')
        .toString()
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s-]/g, '') // remove non-alphanumeric except hyphen and space
        .replace(/\s+/g, '-') // collapse whitespace and replace by -
        .replace(/-+/g, '-'); // collapse dashes
}

export function truncate(str, length = 80) {
    if (!str) return '';
    return str.length > length ? str.substring(0, length) + '...' : str;
}

export function debounce(func, wait = 300) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Fashion category minimalist icons & visual metadata
 */
export function getCategoryMeta(slug) {
    const key = (slug || '').toLowerCase();

    const metaMap = {
        atasan: {
            title: 'Atasan',
            subtitle: 'Tops, Shirts & Tees',
            badgeBg: 'bg-sky-50 text-sky-800 border-sky-200/80',
            accentBg: 'bg-sky-500/10 text-sky-700',
            gradient: 'from-sky-50/80 to-slate-50',
            borderColor: 'border-sky-200',
            iconSvg: `<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>
            </svg>`,
        },
        bawahan: {
            title: 'Bawahan',
            subtitle: 'Pants, Jeans & Skirts',
            badgeBg: 'bg-indigo-50 text-indigo-800 border-indigo-200/80',
            accentBg: 'bg-indigo-500/10 text-indigo-700',
            gradient: 'from-indigo-50/80 to-slate-50',
            borderColor: 'border-indigo-200',
            iconSvg: `<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16v3l-2 13h-4l-2-8-2 8H6L4 7V4z"/>
                <path d="M12 4v4"/>
            </svg>`,
        },
        aksesoris: {
            title: 'Aksesoris',
            subtitle: 'Hats, Belts & Jewelry',
            badgeBg: 'bg-amber-50 text-amber-800 border-amber-200/80',
            accentBg: 'bg-amber-500/10 text-amber-700',
            gradient: 'from-amber-50/80 to-stone-50',
            borderColor: 'border-amber-200',
            iconSvg: `<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="7"/>
                <polyline points="12 9 12 12 13.5 13.5"/>
                <path d="M16.51 17.35l-.35 3.83a2 2 0 0 1-2 1.82H9.83a2 2 0 0 1-2-1.82l-.35-3.83"/>
                <path d="M7.49 6.65l.35-3.83a2 2 0 0 1 2-1.82h4.34a2 2 0 0 1 2 1.82l.35 3.83"/>
            </svg>`,
        },
        sepatu: {
            title: 'Sepatu',
            subtitle: 'Sneakers, Loafers & Slides',
            badgeBg: 'bg-emerald-50 text-emerald-800 border-emerald-200/80',
            accentBg: 'bg-emerald-500/10 text-emerald-700',
            gradient: 'from-emerald-50/80 to-slate-50',
            borderColor: 'border-emerald-200',
            iconSvg: `<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 17l2.5-6.5A3 3 0 0 1 7.3 8.5H12l3 3.5h5a2 2 0 0 1 2 2V17a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2z"/>
                <path d="M2 17h20"/>
                <path d="M12 8.5v3.5"/>
            </svg>`,
        },
        tas: {
            title: 'Tas',
            subtitle: 'Backpacks, Totes & Wallets',
            badgeBg: 'bg-rose-50 text-rose-800 border-rose-200/80',
            accentBg: 'bg-rose-500/10 text-rose-700',
            gradient: 'from-rose-50/80 to-stone-50',
            borderColor: 'border-rose-200',
            iconSvg: `<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="8" width="18" height="13" rx="2"/>
                <path d="M8 8V6a4 4 0 0 1 8 0v2"/>
                <circle cx="12" cy="14" r="1.5"/>
            </svg>`,
        },
        outerwear: {
            title: 'Outerwear',
            subtitle: 'Jackets, Blazers & Coats',
            badgeBg: 'bg-purple-50 text-purple-800 border-purple-200/80',
            accentBg: 'bg-purple-500/10 text-purple-700',
            gradient: 'from-purple-50/80 to-stone-50',
            borderColor: 'border-purple-200',
            iconSvg: `<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
                <polyline points="10 9 9 9 8 9"/>
            </svg>`,
        },
    };

    return metaMap[key] || {
        title: slug || 'Fashion Item',
        subtitle: 'Kategori Preloved',
        badgeBg: 'bg-stone-100 text-stone-800 border-stone-200',
        accentBg: 'bg-stone-200/70 text-stone-700',
        gradient: 'from-stone-50 to-neutral-50',
        borderColor: 'border-stone-200',
        iconSvg: `<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
            <line x1="7" y1="7" x2="7.01" y2="7"/>
        </svg>`,
    };
}

/**
 * Curated high-res fashion photography for preloved products with elegant fallback
 */
export function getProductImage(product) {
    const name = (product?.name || '').toLowerCase();
    const categorySlug = (product?.category?.slug || '').toLowerCase();

    // Specific product images matching seeds
    if (name.includes('flanel')) {
        return 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('kaos basic')) {
        return 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('sweater')) {
        return 'https://images.unsplash.com/photo-1576871337622-98d48d1cf531?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('formal') && categorySlug === 'atasan') {
        return 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('polo')) {
        return 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('blouse')) {
        return 'https://images.unsplash.com/photo-1564257631407-4deb1f99d992?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('hoodie')) {
        return 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('cardigan')) {
        return 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('jeans')) {
        return 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('kulot') || name.includes('linen')) {
        return 'https://images.unsplash.com/photo-1509631179647-0177331693ae?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('chino')) {
        return 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('rok')) {
        return 'https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('jogger')) {
        return 'https://images.unsplash.com/photo-1552902865-b72c031ac5ea?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('topi')) {
        return 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('sabuk')) {
        return 'https://images.unsplash.com/photo-1624222247344-550fb60583dc?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('kacamata')) {
        return 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('sneakers')) {
        return 'https://images.unsplash.com/photo-1560769629-975ec94e6a86?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('sepatu formal')) {
        return 'https://images.unsplash.com/photo-1614252235316-8c857d38b5f4?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('sandal')) {
        return 'https://images.unsplash.com/photo-1603808033192-082d6919d3e1?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('tas') || name.includes('tote') || name.includes('ransel') || name.includes('dompet')) {
        return 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&auto=format&fit=crop&q=80';
    }
    if (name.includes('jaket') || name.includes('blazer') || categorySlug === 'outerwear') {
        return 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=600&auto=format&fit=crop&q=80';
    }

    // Category general fallbacks
    switch (categorySlug) {
        case 'atasan':
            return 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=600&auto=format&fit=crop&q=80';
        case 'bawahan':
            return 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=600&auto=format&fit=crop&q=80';
        case 'aksesoris':
            return 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80';
        case 'sepatu':
            return 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&auto=format&fit=crop&q=80';
        case 'tas':
            return 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=600&auto=format&fit=crop&q=80';
        case 'outerwear':
            return 'https://images.unsplash.com/photo-1544441893-675973e31985?w=600&auto=format&fit=crop&q=80';
        default:
            return 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=600&auto=format&fit=crop&q=80';
    }
}
