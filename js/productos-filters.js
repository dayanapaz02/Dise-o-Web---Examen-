document.addEventListener('DOMContentLoaded', () => {
    const filterBar = document.getElementById('filter-bar');
    const toggleFilterBtn = document.getElementById('toggle-filter-btn');
    const applyFiltersBtn = document.getElementById('apply-filters-btn');
    const categoryFilter = document.getElementById('category-filter');
    const brandFilter = document.getElementById('brand-filter');
    const sortOrder = document.getElementById('sort-order');
    const productsGrid = document.querySelector('.products-grid');
    const allProducts = Array.from(productsGrid.children); // Guardar una copia de todos los productos

    // Función para obtener parámetros de la URL
    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }

    // TOGGLE de la barra de filtros
    if (toggleFilterBtn) { // Asegurarse de que el botón existe
        toggleFilterBtn.addEventListener('click', () => {
            filterBar.classList.toggle('expanded');
        });
    }

    // Aplicar filtros y ordenamiento
    function applyFiltersAndSort() {
        const selectedCategory = categoryFilter ? categoryFilter.value : '';
        const selectedBrand = brandFilter ? brandFilter.value : '';
        const selectedSort = sortOrder ? sortOrder.value : '';

        // 1. Filtrar productos
        let filteredProducts = allProducts.filter(product => {
            const productCategory = product.dataset.category;
            const productBrand = product.dataset.brand;

            const categoryMatch = selectedCategory === '' || productCategory === selectedCategory;
            const brandMatch = selectedBrand === '' || productBrand === selectedBrand;

            return categoryMatch && brandMatch;
        });

        // 2. Ordenar productos
        if (selectedSort === 'price-asc') {
            filteredProducts.sort((a, b) => parseFloat(a.dataset.price) - parseFloat(b.dataset.price));
        } else if (selectedSort === 'price-desc') {
            filteredProducts.sort((a, b) => parseFloat(b.dataset.price) - parseFloat(a.dataset.price));
        } else if (selectedSort === 'name-asc') {
            filteredProducts.sort((a, b) => {
                const nameA = a.querySelector('h3').textContent.toLowerCase();
                const nameB = b.querySelector('h3').textContent.toLowerCase();
                return nameA.localeCompare(nameB);
            });
        } else if (selectedSort === 'name-desc') {
            filteredProducts.sort((a, b) => {
                const nameA = a.querySelector('h3').textContent.toLowerCase();
                const nameB = b.querySelector('h3').textContent.toLowerCase();
                return nameB.localeCompare(nameA);
            });
        }

        // 3. Mostrar/ocultar productos y reinsertar en el DOM
        productsGrid.innerHTML = ''; // Limpiar el grid actual
        if (filteredProducts.length > 0) {
            filteredProducts.forEach(product => {
                productsGrid.appendChild(product);
            });
        } else {
            productsGrid.innerHTML = '<p class="no-results">No se encontraron productos con los filtros seleccionados.</p>';
        }
    }

    // Escuchadores de eventos para los cambios en los filtros y el botón
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', applyFiltersAndSort);
    }
    if (categoryFilter) {
        categoryFilter.addEventListener('change', applyFiltersAndSort);
    }
    if (brandFilter) {
        brandFilter.addEventListener('change', applyFiltersAndSort);
    }
    if (sortOrder) {
        sortOrder.addEventListener('change', applyFiltersAndSort);
    }

    // *** Lógica para leer el parámetro de la URL ***
    const urlCategory = getUrlParameter('category');
    if (urlCategory && categoryFilter) {
        categoryFilter.value = urlCategory; // Establece el filtro de categoría
        filterBar.classList.add('expanded'); // Opcional: abre la barra de filtros
    }

    applyFiltersAndSort(); // Llama a esta función para aplicar el filtro (ya sea el de la URL o el predeterminado)
}); 