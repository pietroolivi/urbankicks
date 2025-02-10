document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const products = document.querySelectorAll('.product-admin');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();

        products.forEach(product => {
            const productInfo = product.querySelector('.product-textual-info-admin');
            const productId = product.querySelector('h3').textContent.toLowerCase();
            const productName = productInfo.querySelector('p').textContent.toLowerCase();
            
            if (productId.includes(searchTerm) || productName.includes(searchTerm)) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    });
});