// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add animation class to elements when they come into view
    const animateOnScroll = () => {
        const elements = document.querySelectorAll('.card, .service-item');
        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementBottom = element.getBoundingClientRect().bottom;
            
            if (elementTop < window.innerHeight && elementBottom > 0) {
                element.classList.add('animate-fade-in');
            }
        });
    };

    // Initial check for elements in view
    animateOnScroll();

    // Check for elements in view on scroll
    window.addEventListener('scroll', animateOnScroll);

    // Product filter functionality
    const filterProducts = () => {
        const category = document.getElementById('categoria').value;
        const marca = document.getElementById('marca').value;
        const precioMax = document.querySelector('input[type="number"]').value;

        // Here you would typically make an API call to filter products
        console.log('Filtering products:', { category, marca, precioMax });
    };

    // Add event listeners to filter inputs
    document.getElementById('categoria').addEventListener('change', filterProducts);
    document.getElementById('marca').addEventListener('change', filterProducts);
    document.querySelector('input[type="number"]').addEventListener('input', filterProducts);

    // Shopping cart functionality
    let cart = [];

    const addToCart = (productId, productName, price) => {
        cart.push({ id: productId, name: productName, price: price });
        updateCartUI();
    };

    const updateCartUI = () => {
        // Update cart count in navbar
        const cartCount = document.querySelector('.cart-count');
        if (cartCount) {
            cartCount.textContent = cart.length;
        }

        // Here you would typically update the cart modal or sidebar
        console.log('Cart updated:', cart);
    };

    // Add to cart button click handlers
    document.querySelectorAll('.btn-add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.card');
            const productId = card.dataset.productId;
            const productName = card.querySelector('.card-title').textContent;
            const price = parseFloat(card.querySelector('.price').textContent.replace('$', ''));
            
            addToCart(productId, productName, price);
        });
    });

    // Form validation
    const contactForm = document.querySelector('#contacto form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = this.querySelector('input[type="text"]').value;
            const email = this.querySelector('input[type="email"]').value;
            const message = this.querySelector('textarea').value;

            if (!name || !email || !message) {
                alert('Por favor complete todos los campos');
                return;
            }

            // Here you would typically send the form data to your backend
            console.log('Form submitted:', { name, email, message });
            alert('Mensaje enviado con éxito');
            this.reset();
        });
    }

    // Mobile menu toggle
    const navbarToggler = document.querySelector('.navbar-toggler');
    if (navbarToggler) {
        navbarToggler.addEventListener('click', function() {
            document.querySelector('.navbar-collapse').classList.toggle('show');
        });
    }
}); 