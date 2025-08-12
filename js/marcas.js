document.addEventListener('DOMContentLoaded', function() {
    // Get all brand cards
    const marcaCards = document.querySelectorAll('.marca-card');

    // Add click event listener to each card
    marcaCards.forEach(card => {
        card.addEventListener('click', function() {
            const marca = this.getAttribute('data-marca');
            // Navigate to the products page with the brand as a parameter
            window.location.href = `productos.html?marca=${marca}`;
        });
    });

    // Add keyboard accessibility
    marcaCards.forEach(card => {
        card.setAttribute('tabindex', '0');
        card.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });
}); 