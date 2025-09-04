// JavaScript pour les filtres de marques côté boutique
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des filtres de marques
    const brandFilters = document.querySelectorAll('.brand-filter-checkbox');
    const productGrid = document.querySelector('.products-grid');
    
    if (brandFilters.length === 0 || !productGrid) {
        return;
    }
    
    brandFilters.forEach(filter => {
        filter.addEventListener('change', function() {
            const selectedBrands = Array.from(brandFilters)
                .filter(f => f.checked)
                .map(f => f.value);
            
            filterProductsByBrands(selectedBrands);
        });
    });
    
    function filterProductsByBrands(selectedBrands) {
        const products = productGrid.querySelectorAll('.product-item');
        
        products.forEach(product => {
            const productBrand = product.dataset.brand;
            
            if (selectedBrands.length === 0 || selectedBrands.includes(productBrand)) {
                product.style.display = '';
                product.classList.remove('filtered-out');
            } else {
                product.style.display = 'none';
                product.classList.add('filtered-out');
            }
        });
        
        // Mise à jour du compteur de produits
        updateProductCount();
    }
    
    function updateProductCount() {
        const visibleProducts = productGrid.querySelectorAll('.product-item:not(.filtered-out)');
        const counter = document.querySelector('.products-count');
        
        if (counter) {
            counter.textContent = `${visibleProducts.length} produit(s) trouvé(s)`;
        }
    }
});