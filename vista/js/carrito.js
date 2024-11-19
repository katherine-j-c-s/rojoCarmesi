document.addEventListener('DOMContentLoaded', function () {
    const cartToggle = document.getElementById('cart-toggle');
    const cartSidebar = document.getElementById('cart-sidebar');
    const closeCart = document.getElementById('close-cart');
    const cartItems = document.getElementById('cart-items');
    const cartCount = document.getElementById('cart-count');
    // const cantidad = document.getElementsByClassName('compraItemCantidad').value;
    
    // Cart Sidebar Toggle
    cartToggle.addEventListener('click', () => {
        cartSidebar.classList.toggle('open');
    });

    closeCart.addEventListener('click', () => {
        cartSidebar.classList.remove('open');
    });

    // Add to Cart Forms
    const forms = document.querySelectorAll('.add-to-cart-form');
    console.log("forms: ", forms);
    
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            console.log("entra al submit: ", e);
            
            e.preventDefault();

            const formData = new FormData(this);

            fetch('../accion/accionAgregarCarrito.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart items
                        updateCartItems(data.carrito);

                        // Update cart count

                        cartCount.textContent = data.cantidad;

                        // Open sidebar
                        cartSidebar.classList.add('open');
                    } else {
                        alert(data.message || 'Error al agregar al carrito');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al agregar al carrito');
                });
        });
    });


    // Update Cart Items Function
    
    function updateCartItems(carrito) {
        cartItems.innerHTML = '';
        let total = 0;

        carrito.forEach((item) => {
            const cartItemHTML = `
                <div class="cart-item mb-3 border-bottom pb-2" data-id="${item.idProducto}">
                    <div class="d-flex align-items-center">
                        <img src="${item.imagen}" class="cart-item-image me-3" alt="${item.nombre}">
                        <div class="flex-grow-1">
                            <h5>${item.nombre}</h5>
                            <p>$ ${item.precio}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-secondary decrease-quantity" data-id="${item.idProducto}">-</button>
                            <span class="btn btn-sm btn-outline-secondary quantity">${item.cantidad}</span>
                            <button class="btn btn-sm btn-outline-secondary increase-quantity" data-id="${item.idProducto}">+</button>
                        </div>
                        <button class="btn btn-sm btn-danger remove-item" data-id="${item.idProducto}">Eliminar</button>
                    </div>
                </div>
            `;
            cartItems.innerHTML += cartItemHTML;
            total += item.precio * item.cantidad;
        });

        const summaryHTML = `
            <div class="cart-summary mt-4">
                <h4>Total: $ ${total.toFixed(2)}</h4>
                <button id="process-purchase" class="btn btn-primary w-100 mt-3">Procesar Compra</button>
            </div>
        `;
        cartItems.innerHTML += summaryHTML;

        setupCartControls();
        setupProcessPurchase();
    }
    // Setup Cart Controls (Decrease, Increase, Remove)
    function setupCartControls() {
        // Decrease Quantity
        document.querySelectorAll('.decrease-quantity').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.dataset.id;
                updateQuantity(productId, -1);
            });
        });

        // Increase Quantity
        document.querySelectorAll('.increase-quantity').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.dataset.id;
                updateQuantity(productId, 1);
            });
        });

        // Remove Item
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.dataset.id;
                removeFromCart(productId);
            });
        });
    }

    // Update Quantity
    function updateQuantity(productId, change) {
        fetch('../accion/actualizarCarrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `idProducto=${productId}&cambio=${change}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartItems(data.carrito);
                    cartCount.textContent = data.cantidad;
                } else {
                    alert(data.message || 'Error al actualizar cantidad');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al actualizar la cantidad');
            });
    }

    // Remove Item from Cart
    function removeFromCart(productId) {
        fetch('../accion/eliminarCarrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `idProducto=${productId}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartItems(data.carrito);
                    cartCount.textContent = data.cantidad;
                } else {
                    alert(data.message || 'Error al eliminar producto');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al eliminar el producto');
            });
    }

    // Initial setup of cart controls
    setupCartControls();

    function setupProcessPurchase() {
        const processButton = document.getElementById('process-purchase');
        if (processButton) {
            processButton.addEventListener('click', function() {
                if (confirm('¿Está seguro que desea finalizar la compra?')) {
                    fetch('../accion/accionProcesarCompra.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Compra procesada exitosamente');
                            cartSidebar.classList.remove('open');
                            cartCount.textContent = '0';
                            
                            // Recargar la página para actualizar el stock mostrado
                            window.location.reload();
                        } else {
                            alert(data.message || 'Error al procesar la compra');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Ocurrió un error al procesar la compra');
                    });
                }
            });
        }
    }
});