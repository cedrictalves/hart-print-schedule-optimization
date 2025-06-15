document.addEventListener('DOMContentLoaded', function () {

    const typeSelect = document.getElementById('type');
    const productsContainer = document.getElementById('products-container');

    if ( !typeSelect || !productsContainer ) {
        return;
    }


    // Trigger when a product type is selected
    typeSelect.addEventListener('change', function () {
        const selectedType = this.value;
        productsContainer.innerHTML = '';

        if (!selectedType) return;

        // Fetch products for selected type using AJAX
        fetch(`/product-type?type=${selectedType}`)
            .then(response => response.json())
            .then(products => {
                if (products.length === 0) {
                    productsContainer.innerHTML = '<p>No products available for this type.</p>';
                    return;
                }

                // Clear previous products
                productsContainer.innerHTML = '';

                // Create inputs for each product
                products.forEach(product => {
                    const div = document.createElement('div');

                    div.innerHTML = `
                        <label>
                            ${product.name} — Quantity (x 500 units):
                            <input type="number" name="products[${product.id}]" min="0" value="0">
                        </label>
                        <br>
                    `;

                    productsContainer.appendChild(div);
                });
            })
            .catch(error => {
                console.error('Error fetching products:', error);
                productsContainer.innerHTML = '<p style="color: red;">Error loading products.</p>';
            });
    });
});
