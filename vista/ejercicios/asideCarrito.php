<?php
// Verificar si el carrito existe en la sesión
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
?>

<style>
    /* Estilos para el aside del carrito */
    #cart-sidebar {
        position: fixed;
        top: 0;
        right: -400px; /* Oculto inicialmente */
        width: 400px;
        height: 100%;
        background-color: white;
        box-shadow: -2px 0 5px rgba(0,0,0,0.1);
        transition: right 0.3s ease-in-out;
        z-index: 1100;
        overflow-y: auto;
    }
    #cart-sidebar.open {
        right: 0;
    }
    .cart-item-image {
        max-width: 80px;
        max-height: 80px;
        object-fit: cover;
    }
</style>

<aside id="cart-sidebar" class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Carrito de Compras</h3>
        <button id="close-cart" class="btn btn-close"></button>
    </div>

    <div id="cart-items">
        <?php foreach ($carrito as $index => $item): ?>
            <div class="cart-item mb-3 border-bottom pb-2" data-id="<?= $item['idProducto'] ?>">
                <div class="d-flex align-items-center">
                    <img src="<?= $item['imagen'] ?>" class="cart-item-image me-3" alt="<?= $item['nombre'] ?>">
                    <div class="flex-grow-1">
                        <h5><?= $item['nombre'] ?></h5>
                        <p>$ <?= $item['precio'] ?></p>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-2">
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-secondary decrease-quantity" data-id="<?= $item['idProducto'] ?>">-</button>
                        <span class="btn btn-sm btn-outline-secondary quantity"><?= $item['cantidad'] ?></span>
                        <button class="btn btn-sm btn-outline-secondary increase-quantity" data-id="<?= $item['idProducto'] ?>">+</button>
                    </div>
                    <button class="btn btn-sm btn-danger remove-item" data-id="<?= $item['idProducto'] ?>">Eliminar</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php 
    $totalCarrito = array_reduce($carrito, function($carry, $item) {
        return $carry + ($item['precio'] * $item['cantidad']);
    }, 0);
    ?>

</aside>