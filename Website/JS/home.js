class ProductManager {
    constructor() {
        const rawProducts = JSON.parse(document.getElementById('products-container').dataset.products);
        this.initializeProducts(rawProducts);
        this.filters = {
            category: 'popular',
            brand: [],
            genre: '',
            type: '',
            sort: 'price-low-to-high'
        };
        this.setupEventListeners();
        this.applyFilters();
    }

    initializeProducts(rawProducts) {
        const groupedProducts = new Map();
        
        rawProducts.forEach(product => {
            const modelKey = `${product.ID_Prodotto}`;
            if (!groupedProducts.has(modelKey)) {
                groupedProducts.set(modelKey, {
                    id: product.ID_Prodotto,
                    name: product.Nome,
                    brand: product.Marca,
                    price: product.Prezzo,
                    type: product.Tipo,
                    genre: product.Genre,
                    image: product.Immagine,
                    description: product.Descrizione,
                    sta_tipo: product.Sta_Tipo
                });
            }
        });

        this.allProducts = Array.from(groupedProducts.values());
        this.filteredProducts = [...this.allProducts];
    }

    setupEventListeners() {
        // Category filters
        document.querySelectorAll('input[name="category"]').forEach(input => {
            input.addEventListener('change', (e) => {
                this.filters.category = e.target.value;
                this.applyFilters();
            });
        });

        // Designer filters
        this.setupCheckListFilter('designers');
        
        // Size filters
        this.setupCheckListFilter('size');
        
        // Color filters
        this.setupCheckListFilter('color');

        // Sort options
        document.querySelectorAll('input[name="sort"]').forEach(input => {
            input.addEventListener('change', () => this.handleFilterChange('sort', input));
        });
    }

    setupCheckListFilter(category) {
        document.querySelectorAll(`input[name="${category}[]"]`).forEach(input => {
            input.addEventListener('change', () => this.handleFilterChange(category, input));
        });
    }

    handleFilterChange(filterType, input) {
        switch(filterType) {
            case 'designers':
                this.filters.brand = Array.from(
                    document.querySelectorAll('input[name="designers[]"]:checked')
                ).map(cb => cb.value);
                break;
            case 'sort':
                this.filters.sort = input.value;
                break;
        }
        this.applyFilters();
    }

    applyFilters() {
        this.filteredProducts = this.allProducts.filter(product => {
            const brandMatch = this.filters.brand.length === 0 || 
                             this.filters.brand.includes(product.brand);
            const genreMatch = !this.filters.genre || 
                             product.genre === this.filters.genre;
            const typeMatch = !this.filters.type || 
                            product.type === this.filters.type;
            
            // Category filtering
            let categoryMatch = true;
            switch(this.filters.category) {
                case 'discounted':
                    categoryMatch = product.discount > 0;
                    break;
                case 'novelties':
                    const oneMonthAgo = new Date();
                    oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 1);
                    categoryMatch = new Date(product.created_at) > oneMonthAgo;
                    break;
                case 'popular':
                    // Use views for popular items
                    categoryMatch = true; // Default to show all if popular
                    break;
            }

            return brandMatch && genreMatch && typeMatch && categoryMatch;
        });

        this.sortProducts();
        this.renderProducts();
        this.updateURL();
    }

    updateBreadcrumb() {
        const { genre, type } = this.filters;
        const breadcrumb = document.querySelector('.breadcrumb ol');
        if (!breadcrumb) return;

        breadcrumb.innerHTML = '';

        if (!genre && !type) {
            document.querySelector('.breadcrumb').style.display = 'none';
            return;
        }

        document.querySelector('.breadcrumb').style.display = 'block';

        // Add Home
        const homeItem = document.createElement('li');
        homeItem.innerHTML = '<a href="home.php">Home</a>';
        breadcrumb.appendChild(homeItem);

        // Add Genre if present
        if (genre) {
            const genreItem = document.createElement('li');
            genreItem.innerHTML = `<a href="home.php?genre=${encodeURIComponent(genre)}">${genre}</a>`;
            breadcrumb.appendChild(genreItem);
        }

        // Add Type if present
        if (type) {
            const typeItem = document.createElement('li');
            typeItem.innerHTML = `<span aria-current="page">${type}</span>`;
            breadcrumb.appendChild(typeItem);
        }
    }

    sortProducts() {
        this.filteredProducts.sort((a, b) => {
            switch(this.filters.sort) {
                case 'price-low-to-high':
                    return a.price - b.price;
                case 'price-high-to-low':
                    return b.price - a.price;
                case 'alphabetical':
                    return a.name.localeCompare(b.name);
                default:
                    return 0;
            }
        });
    }

    renderProducts() {
        const container = document.getElementById('products-container');
        const template = document.getElementById('product-template');
        container.innerHTML = '';
        
        this.filteredProducts.forEach(product => {
            const clone = template.content.cloneNode(true);
            const card = clone.querySelector('.product-card');
            
            const productLink = card.querySelector('.product-link');
            productLink.href = `product.php?id=${product.id}`;
            
            card.querySelector('img').src = product.image;
            card.querySelector('img').alt = product.name;
            card.querySelector('.product-name').textContent = product.name;
            card.querySelector('.product-price').textContent = `€${product.price}`;
            
            container.appendChild(clone);
        });
    }

    updateURL() {
        const params = new URLSearchParams(window.location.search);
        Object.entries(this.filters).forEach(([key, value]) => {
            if (value && value.length !== 0) {
                params.set(key, Array.isArray(value) ? value.join(',') : value);
            } else {
                params.delete(key);
            }
        });
        window.history.pushState({}, '', `?${params.toString()}`);
    }
}

// Initialize ProductManager
document.addEventListener('DOMContentLoaded', () => {
    new ProductManager();
});