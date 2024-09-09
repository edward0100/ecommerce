document.addEventListener('DOMContentLoaded', function() {
 
    const addToCartButtons = document.querySelectorAll('form[action="add_to_cart.php"]');
    addToCartButtons.forEach(button => {
        button.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            const formData = new FormData(this);
            fetch('add_to_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Product added to cart!');
                    // Update cart display or quantity
                    document.getElementById('cart-count').textContent = data.cartCount;
                } else {
                    alert('Failed to add product to cart.');
                }
            });
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {

    const addToCartButtons = document.querySelectorAll('form[action="add_to_cart.php"]');
    addToCartButtons.forEach(button => {
        button.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            const formData = new FormData(this);
            fetch('add_to_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Product added to cart!');
                    // Update cart display or quantity
                    document.getElementById('cart-count').textContent = data.cartCount;
                } else {
                    alert('Failed to add product to cart.');
                }
            });
        });
    });
});
document.querySelectorAll('.btn-primary').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent page reload
        const productId = this.getAttribute('href').split('id=')[1];
        
        fetch(`product_details.php?id=${productId}`)
            .then(response => response.text())
            .then(html => {
                // Display the fetched product details inside a modal, for example
                document.getElementById('product-modal-content').innerHTML = html;
                $('#product-modal').modal('show');
            });
    });
});