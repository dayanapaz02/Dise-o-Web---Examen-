document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const exploreCategoriesBtn = document.querySelector('.explore-categories-btn');
    const sidebar = document.getElementById('categorySidebar');
    const closeBtn = sidebar.querySelector('.close-btn');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');

    const categoryLinks = document.querySelectorAll('.category-sidebar .category-link');
    const subcategoryContents = document.querySelectorAll('.category-sidebar .subcategory-content');

    // Función para abrir el sidebar
    function openSidebar() {
        sidebar.classList.add('is-open');
        sidebarOverlay.classList.add('is-open');
        document.body.style.overflow = 'hidden'; // Evita el scroll del fondo
    }

    // Función para cerrar el sidebar
    function closeSidebar() {
        sidebar.classList.remove('is-open');
        sidebarOverlay.classList.remove('is-open');
        document.body.style.overflow = ''; // Restaura el scroll del fondo
    }

    // Event listeners para abrir/cerrar el sidebar
    if (exploreCategoriesBtn) {
        exploreCategoriesBtn.addEventListener('click', openSidebar);
    }
    if (closeBtn) {
        closeBtn.addEventListener('click', closeSidebar);
    }
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar); // Cerrar al hacer clic fuera
    }

    // Lógica para cambiar el contenido de las subcategorías
    categoryLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Prevenir el comportamiento por defecto del enlace

            // Remover la clase 'active-category' de todos los enlaces de categoría
            categoryLinks.forEach(l => l.classList.remove('active-category'));

            // Añadir 'active-category' al enlace clickeado
            this.classList.add('active-category');

            // Obtener el valor del atributo data-category
            const targetCategory = this.dataset.category;

            // Ocultar todos los divs de contenido de subcategorías
            subcategoryContents.forEach(content => content.classList.remove('active-content'));

            // Mostrar el div de contenido correspondiente a la categoría clickeada
            const targetContent = document.getElementById(targetCategory + '-content');
            if (targetContent) {
                targetContent.classList.add('active-content');
            }
        });
    });
}); 