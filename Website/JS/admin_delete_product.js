document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.fields-product-admin');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('admin_delete_product_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Product deleted successfully');
                window.location.href = 'admin_products.php';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error deleting product');
            console.error('Error:', error);
        });
    });
});