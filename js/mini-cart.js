document.addEventListener('DOMContentLoaded', function() {
    const miniCartSidebar = document.getElementById('miniCartSidebar');
    const miniCartOverlay = document.getElementById('miniCartOverlay');
    const closeMiniCartBtn = miniCartSidebar.querySelector('.mini-cart-close-btn');
    const cartItemsContainer = miniCartSidebar.querySelector('.mini-cart-items');
    const addProductButtons = document.querySelectorAll('.btn-add-to-cart'); // Todos los botones "Agregar al Carrito"
    const cartSubtotalElem = document.getElementById('cartSubtotal');
    const cartISVElem = document.getElementById('cartISV');
    const cartDiscountElem = document.getElementById('cartDiscount');
    const cartTotalElem = document.getElementById('cartTotal');
    const checkoutBtn = miniCartSidebar.querySelector('.btn-checkout');

    let cart = JSON.parse(localStorage.getItem('byteStoreCart')) || [];
    const ISV_RATE = 0.15; // 15% de ISV

    // --- Funciones del Mini-Carrito ---

    function openMiniCart() {
        miniCartSidebar.classList.add('is-open');
        miniCartOverlay.classList.add('is-open');
        document.body.style.overflow = 'hidden'; // Previene el scroll del fondo
        renderCartItems(); // Renderiza los ítems cada vez que se abre
        updateCartSummary(); // Actualiza el resumen
    }

    function closeMiniCart() {
        miniCartSidebar.classList.remove('is-open');
        miniCartOverlay.classList.remove('is-open');
        document.body.style.overflow = ''; // Restaura el scroll del fondo
    }

    function saveCart() {
        localStorage.setItem('byteStoreCart', JSON.stringify(cart));
    }

    function calculateTotals() {
        let subtotal = 0;
        let totalDiscount = 0; // Para descuentos de producto, no de cupón global
        // Si tuvieras un sistema de cupones global, aquí iría la lógica.

        cart.forEach(item => {
            subtotal += item.price * item.quantity;
            // Si el producto tiene un precio anterior y un descuento fijo, calcular la diferencia.
            // Para el ejemplo de la imagen, el descuento es por producto, no global.
            if (item.oldPrice && item.discount) {
                totalDiscount += item.discount * item.quantity;
            }
        });

        // Simulación de un descuento global si existe un cupón (no implementado en esta versión)
        // const globalCouponDiscount = subtotal * 0.10; // Ejemplo: 10% de descuento global

        const isv = subtotal * ISV_RATE;
        const total = subtotal + isv - totalDiscount; // Restar los descuentos individuales

        return {
            subtotal: subtotal,
            isv: isv,
            discount: totalDiscount,
            total: total
        };
    }

    function updateCartSummary() {
        const totals = calculateTotals();
        cartSubtotalElem.textContent = `L${totals.subtotal.toFixed(2)}`;
        cartISVElem.textContent = `L${totals.isv.toFixed(2)}`;
        cartDiscountElem.textContent = `- L${totals.discount.toFixed(2)}`;
        cartTotalElem.textContent = `L${totals.total.toFixed(2)}`;

        // Habilitar/deshabilitar botón de checkout si el carrito está vacío
        if (cart.length === 0) {
            checkoutBtn.disabled = true;
            checkoutBtn.textContent = 'Carrito Vacío';
            checkoutBtn.style.backgroundColor = '#ccc';
            cartItemsContainer.innerHTML = '<p class="empty-cart-message" style="text-align: center; color: #888; padding: 20px;">Tu carrito está vacío.</p>';
        } else {
            checkoutBtn.disabled = false;
            checkoutBtn.textContent = 'PROCEDER A PAGAR';
            checkoutBtn.style.backgroundColor = '#007bff';
            // Quita el mensaje de carrito vacío si hay ítems
            const emptyMessage = cartItemsContainer.querySelector('.empty-cart-message');
            if (emptyMessage) emptyMessage.remove();
        }
    }

    function renderCartItems() {
        cartItemsContainer.innerHTML = ''; // Limpiar antes de renderizar
        if (cart.length === 0) {
            updateCartSummary();
            return;
        }

        cart.forEach(item => {
            const cartItemDiv = document.createElement('div');
            cartItemDiv.classList.add('cart-item');
            cartItemDiv.dataset.id = item.id; // Guarda el ID para manipularlo

            // Asegurarse de que model sea una cadena vacía si es null/undefined para evitar "undefined"
            const itemModelDisplay = item.model ? item.model : '';

            const originalPriceDisplay = item.oldPrice ? `<span class="item-price-original">L${item.oldPrice.toFixed(2)}</span>` : '';
            const discountCouponDisplay = item.discount ? `<div class="discount-coupon-tag">AHORRA L${item.discount.toFixed(2)}</div>` : ''; // O DESCUENTO DE CUPÓN: 10% si es porcentual

            cartItemDiv.innerHTML = `
                <img src="${item.image}" alt="${item.name}">
                <div class="item-details">
                    <span class="item-name">${item.name}</span>
                    <span class="item-model">${itemModelDisplay}</span>
                    <div class="item-actions">
                        <div class="quantity-control">
                            <button class="quantity-minus" data-id="${item.id}">-</button>
                            <span class="item-quantity">${item.quantity}</span>
                            <button class="quantity-plus" data-id="${item.id}">+</button>
                        </div>
                        ${originalPriceDisplay}
                        <span class="item-price-current">L${(item.price * item.quantity).toFixed(2)}</span>
                    </div>
                    ${discountCouponDisplay}
                </div>
                <button class="item-delete-btn" data-id="${item.id}"><i class="fas fa-trash"></i></button>
            `;
            cartItemsContainer.appendChild(cartItemDiv);
        });

        // Añadir event listeners a los nuevos botones
        cartItemsContainer.querySelectorAll('.quantity-minus').forEach(btn => {
            btn.addEventListener('click', updateQuantity);
        });
        cartItemsContainer.querySelectorAll('.quantity-plus').forEach(btn => {
            btn.addEventListener('click', updateQuantity);
        });
        cartItemsContainer.querySelectorAll('.item-delete-btn').forEach(btn => {
            btn.addEventListener('click', removeItem);
        });
        updateCartSummary(); // Actualiza el resumen después de renderizar ítems
    }

    function addToCart(productData) {
        const existingItem = cart.find(item => item.id === productData.id);

        if (existingItem) {
            existingItem.quantity++;
        } else {
            cart.push({ ...productData, quantity: 1 });
        }
        saveCart();
        openMiniCart(); // Abre el mini-carrito al añadir producto
    }

    function updateQuantity(event) {
        const id = event.target.dataset.id;
        const type = event.target.classList.contains('quantity-plus') ? 'plus' : 'minus';
        const itemIndex = cart.findIndex(item => item.id === id);

        if (itemIndex > -1) {
            if (type === 'plus') {
                cart[itemIndex].quantity++;
            } else if (type === 'minus') {
                cart[itemIndex].quantity--;
                if (cart[itemIndex].quantity <= 0) {
                    cart.splice(itemIndex, 1); // Eliminar si la cantidad es 0 o menos
                }
            }
            saveCart();
            renderCartItems(); // Re-renderiza para reflejar los cambios
        }
    }

    function removeItem(event) {
        const id = event.target.closest('button').dataset.id;
        cart = cart.filter(item => item.id !== id);
        saveCart();
        renderCartItems(); // Re-renderiza
    }

    // --- Event Listeners Globales ---

    // Abrir/Cerrar Mini-Carrito
    closeMiniCartBtn.addEventListener('click', closeMiniCart);
    miniCartOverlay.addEventListener('click', closeMiniCart);

    // Event listener para todos los botones "Agregar al Carrito"
    addProductButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productCard = this.closest('.product-card');
            const productData = {
                id: productCard.dataset.id,
                name: productCard.dataset.name,
                model: productCard.dataset.model || '', // Asegúrate de que el modelo esté en el HTML si lo necesitas
                price: parseFloat(productCard.dataset.price),
                oldPrice: productCard.dataset.oldPrice ? parseFloat(productCard.dataset.oldPrice) : null,
                discount: productCard.dataset.discount ? parseFloat(productCard.dataset.discount) : null,
                image: productCard.dataset.image
            };
            addToCart(productData);
        });
    });

    // Event listener para el botón "Proceder a Pagar"
    checkoutBtn.addEventListener('click', function() {
        // Aquí puedes redirigir a la página de pago
        // Podrías pasar los datos del carrito a través de localStorage o sessionStorage
        // antes de redirigir, o usar una llamada AJAX al servidor.
        alert('Redirigiendo a la página de pago...');
        // location.href = 'pago-cuotas.html'; // Asegúrate de que esta sea la página correcta
    });


    // Inicializar el carrito al cargar la página
    renderCartItems();
}); 