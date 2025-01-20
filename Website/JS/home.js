class ProductManager {
    constructor() {
        const rawProducts = JSON.parse(document.getElementById('products-container').dataset.products);

        this.wishlistItems = new Set();
        
        // Initialize filters from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        this.filters = {
            category: urlParams.get('category') || 'popular',
            brand: urlParams.get('brand') ? urlParams.get('brand').split(',') : [],
            genre: urlParams.get('genre') || '',
            type: urlParams.get('type') || '',
            sort: urlParams.get('sort') || 'price-low-to-high'
        };

        this.initializeProducts(rawProducts);
        this.loadWishlistItems();

    }

    async loadWishlistItems() {
        try {
            const response = await fetch('home_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'getWishlistItems'
                })
            });
            const data = await response.json();
    
            if (data.success) {
                // Popola i tuoi this.wishlistItems con gli ID dei prodotti
                // data.wishlistItems è un array di prodotti (con le colonne di PRODOTTO)
                const wishlistIds = data.wishlistItems.map(item => item.ID_Prodotto.toString());
                this.wishlistItems = new Set(wishlistIds);
                console.log(this.wishlistItems.size);
                // Eventualmente aggiorna il rendering dei prodotti 
                // per spuntare i checkbox corrispondenti
               // this.updateWishlistCheckboxes();

            } else {
                console.warn('Could not load wishlist items:', data.message);
            }
        } catch (error) {
            console.error('Error loading wishlist items:', error);
        }
        this.setupEventListeners();
        this.applyFilters();
    }

    initializeProducts(rawProducts) {
        // Group products by model
        const groupedProducts = new Map();
    /*    rawProducts.forEach(p=>{
            if(p.inWishlist==true)
            console.log("prodotto in wishlist:"+p.ID_Prodotto);
        });*/
        rawProducts.forEach(product => {
            const modelKey = product.Nome.toLowerCase();
            
            if (!groupedProducts.has(modelKey)) {
                // Create new product entry
                groupedProducts.set(modelKey, {
                    id: product.ID_Prodotto,
                    name: product.Nome,
                    brand: product.Marca,
                    price: parseFloat(product.Prezzo),
                    type: product.Tipo,
                    genre: product.Genere,
                    description: product.Descrizione,
                    state: product.Sta_Tipo,
                    //added for discounted logic v
                    isDiscounted: product.isDiscounted === 1,
                    //discounted logic           ^
                    created_at: product.Data_Aggiunta,
                    variants: [],
                    baseProduct: product
                });
            }
            
            // Add variant information
            groupedProducts.get(modelKey).variants.push({
                id: product.ID_Prodotto,
                size: product.Taglia,
                color: product.Colore,
                price: parseFloat(product.Prezzo),
                state: product.Sta_Tipo
            });
        });

        // Convert Map to array and store
        this.allProducts = Array.from(groupedProducts.values());
        this.filteredProducts = [...this.allProducts];
    }

    setupEventListeners() {
        // Category filters
        document.querySelectorAll('input[name="category"]').forEach(input => {
            input.checked = input.value === this.filters.category;
            input.addEventListener('change', () => {
                this.filters.category = input.value;
                this.applyFilters();
            });
        });

        // Designer filters
        document.querySelectorAll('input[name="designers[]"]').forEach(input => {
            input.checked = this.filters.brand.includes(input.value);
          //  console.log(input.value+" check nome del brand nel filtro");
            input.addEventListener('change', () => this.handleFilterChange('designers'));
        });

        // Sort options
        document.querySelectorAll('input[name="sort"]').forEach(input => {
            input.checked = input.value === this.filters.sort;
            input.addEventListener('change', () => {
                this.filters.sort = input.value;
                this.applyFilters();
            });
        });

        // Wishlist toggle
        document.addEventListener('change', (event) => {
            if (event.target.classList.contains('wishlist-checkbox')) {
                this.handleWishlistToggle(event.target);
            }
        });

        // Listen for URL changes
        window.addEventListener('popstate', () => {
            const urlParams = new URLSearchParams(window.location.search);
            this.filters = {
                category: urlParams.get('category') || 'popular',
                brand: urlParams.get('brand') ? urlParams.get('brand').split(',') : [],
                genre: urlParams.get('genre') || '',
                type: urlParams.get('type') || '',
                sort: urlParams.get('sort') || 'price-low-to-high'
            };
            this.applyFilters();
        });
    }

    handleFilterChange(filterType) {
        if (filterType === 'designers') {
            this.filters.brand = Array.from(
                document.querySelectorAll('input[name="designers[]"]:checked')
            ).map(cb => cb.value);
        }
        this.applyFilters();
    }

    applyFilters() {
        this.filteredProducts = this.allProducts.filter(product => {
            // Check if any variant matches the filters
            const hasMatchingVariant = product.variants.some(variant => {
                // Add variant-specific filters here if needed
                return true; // Default to true if no variant-specific filters
            });

            // Brand filter
            const brandMatch = this.filters.brand.length === 0 || 
                             this.filters.brand.includes(product.brand);

            // Genre filter
            const genreMatch = !this.filters.genre || 
                             (product.genre && 
                              product.genre.toLowerCase() === this.filters.genre.toLowerCase());

            // Type filter
            const typeMatch = !this.filters.type || 
                            (product.type && 
                             product.type.toLowerCase() === this.filters.type.toLowerCase());

            // Category filter
            let categoryMatch = true;
            switch(this.filters.category) {
                case 'discounted':
                    //categoryMatch = product.variants.some(v => v.discount > 0);
                    categoryMatch=product.isDiscounted;
                    
                    break;
                case 'novelties':
                    const oneMonthAgo = new Date();
                    oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 1);
                    categoryMatch = new Date(product.created_at) > oneMonthAgo;
                    break;
            }

            return brandMatch && genreMatch && typeMatch && categoryMatch && hasMatchingVariant;
        });

        this.sortProducts();
        this.renderProducts();
        this.updateURL();
        this.updateBreadcrumb();
    }

    renderProducts() {
        const container = document.getElementById('products-container');
        container.innerHTML = '';
        //debug to see if there's a filed name mismatch
        console.log(this.allProducts);
        if (this.filteredProducts.length === 0) {
            container.innerHTML = '<p>No products found matching your criteria.</p>';
            return;
        }

        this.filteredProducts.forEach(product => {
            const productElement = this.createProductElement(product);
            container.appendChild(productElement);
        });
    }

    createProductElement(product) {
        const template = document.getElementById('product-template');
        const productCard = template.content.cloneNode(true);
        const card = productCard.querySelector('.product-card');
        
        card.dataset.productId = product.id;
        const link = card.querySelector('.product-link');
        link.href = `product.php?id=${product.id}`;
        /**<img src="CSS/Images/Products/<?php echo htmlspecialchars($product['ID_Prodotto']. '_' . $product['Genere'] . $i); ?>.webp" 
                            alt="<?php echo htmlspecialchars($product['Nome']); ?> - View <?php echo $i; ?>"> */
        const img = card.querySelector('img');
        img.src = `CSS/Images/Products/${product.id}_${product.genre}1.webp`;
        //fallback image if image fetch fails.
        img.onerror = function() {
            img.src = `CSS/Images/Products/default_shoe.webp`;
        };
        console.log(`CSS/Images/Products/${product.id}_${product.genre}1.webp`);
        img.name = `${product.Nome} - View ${1}`;
        card.querySelector('.product-name').textContent = product.name;
        
        // Show price range if variants have different prices
        const prices = product.variants.map(v => v.price);
        const minPrice = Math.min(...prices);
        const maxPrice = Math.max(...prices);
        
        const priceElement = card.querySelector('.product-price');
        if (minPrice === maxPrice) {
            priceElement.textContent = `€${minPrice.toFixed(2)}`;
        } else {
            priceElement.textContent = `€${minPrice.toFixed(2)} - €${maxPrice.toFixed(2)}`;
        }
        
        const wishlistCheckbox = card.querySelector('.wishlist-checkbox');
        if (this.wishlistItems.has(product.id.toString())) {
            console.log("disabilita check");
            wishlistCheckbox.checked = true;
            wishlistCheckbox.nextElementSibling.textContent = 'Remove from Wishlist';
        }
        
        return card;
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

    



    async handleWishlistToggle(checkbox) {
        const productCard = checkbox.closest('.product-card');
        const productId = productCard.dataset.productId;
        const isAdd = checkbox.checked;
        const wishlistText = checkbox.nextElementSibling;

        try {
           /* const formData = new FormData();
            formData.append('action', 'toggleWishlist');
            formData.append('productId', productId);
            formData.append('isAdd', isAdd);*/

            const response = await fetch('home_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body:new URLSearchParams({
                    action: 'toggleWishlist',
                    productId: productId,
                    isAdd: isAdd 
                })
            });
          /*  const data= await response.text();
            console.log(data);*/
            const data = await response.json();

            if (data.success) {
                // Update UI
                wishlistText.textContent = isAdd ? 'Remove from Wishlist' : 'Add to Wishlist';
            } else {
                // Revert checkbox state on error
                checkbox.checked = !isAdd;
                wishlistText.textContent = !isAdd ? 'Remove from Wishlist' : 'Add to Wishlist';
                
                // Show error message
                if (data.message === 'Please login first') {
                    window.location.href = 'login.php';
                } else {
                    alert(data.message);
                }
            }
        } catch (error) {
            console.error('Error:', error);
            // Revert checkbox state on error
            checkbox.checked = !isAdd;
            wishlistText.textContent = !isAdd ? 'Remove from Wishlist' : 'Add to Wishlist';
            alert('An error occurred. Please try again.');
        }
    }

    updateURL() {
        const params = new URLSearchParams();
        Object.entries(this.filters).forEach(([key, value]) => {
            if (value && value.length !== 0) {
                params.set(key, Array.isArray(value) ? value.join(',') : value);
            }
        });
        window.history.pushState({}, '', `?${params.toString()}`);
    }

    capitalizeFirstLetter(string) {
        if (!string) return '';
        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    }

    updateBreadcrumb() {
        const breadcrumbNav = document.querySelector('.breadcrumb');
        if (!breadcrumbNav) return;

        if (!this.filters.genre && !this.filters.type) {
            breadcrumbNav.style.display = 'none';
            return;
        }

        breadcrumbNav.style.display = 'block';
        const ol = breadcrumbNav.querySelector('ol');
        ol.innerHTML = `
            <li><a href="home.php">Home</a></li>
            ${this.filters.genre ? `<li><a href="home.php?genre=${encodeURIComponent(this.filters.genre)}">${this.capitalizeFirstLetter(this.filters.genre)}</a></li>` : ''}
            ${this.filters.type ? `<li><span aria-current="page">${this.capitalizeFirstLetter(this.filters.type)}</span></li>` : ''}
        `;
    }
}




document.addEventListener('DOMContentLoaded', () => {
    new ProductManager();
});