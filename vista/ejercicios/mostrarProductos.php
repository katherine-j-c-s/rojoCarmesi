<?php
include_once '../../configuracion.php';

$sesion = new session();
if ($sesion->activa()) {
    include_once '../estructura/cabeceraSegura.php';
} else {
    include_once '../estructura/cabecera.php';
}

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<link rel="stylesheet" href="../css/stylesProducto.css">

<div class="container mt-5">
    <!-- Cart Sidebar Toggle Button -->
    <button id="cart-toggle" class="btn btn-primary position-fixed top-0 end-0 m-3" style="z-index: 1000;">
        🛒 Carrito 
        <span id="cart-count" class="badge bg-danger">
            <?= isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0 ?>
        </span>
    </button>

    <!-- Include Cart Sidebar -->
    <?php include_once './asideCarrito.php'; ?>

    <section class="py-2">
        <h4 class="mt-5" style='text-align: center'>Adquiri nuestros productos</h4>
        <h5 style='text-align: center'>
            <?php
            if (!$sesion->activa()) {
                echo "Para realizar Compras debe ingresar como usuario o registrase";
            }
            ?>
        </h5>
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx-4 gx-lg-5 m-3 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                <?php
                $abmProducto = new abmProducto();
                $colObjProductos = $abmProducto->buscar();
                foreach ($colObjProductos as $producto) {
                    if ($producto->getProductoStock() == 0) {
                        continue;
                    }
                    $arregloArchivos = $abmProducto->obtenerArchivos(md5($producto->getIdProducto()));
                ?>
                    <div class="card m-3 shadow" style="width: 18rem; border-radius: 15px; overflow: hidden; height:550px;">
                        <img src='<?= $arregloArchivos ?>' style='max-width: 300px; width:300px; height:300px' class='img-fluid' alt='productos'>
                        <div class="card-body text-center">
                            <h2><?= $producto->getProductoNombre() ?></h2>
                            <h6><?= $producto->getProductoDetalle() ?></h6>
                            <p>Precio $ <?= $producto->getProductoPrecio() ?></p>

                            <?php if ($sesion->activa() && $producto->getProductoStock() > 0): ?>
                                <p>Stock: <?= $producto->getProductoStock() ?></p>
                                
                                <form class="add-to-cart-form" method="post">
                                    <input type="hidden" name="idProducto" value="<?= $producto->getIdProducto() ?>">
                                    <input type="number" name="compraItemCantidad" min="1" max="<?= $producto->getProductoStock() ?>" value="1">
                                    <button type="submit" class="btn btn-warning mt-3">Añadir al carrito</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>

<script src="../js/carrito.js"></script>

<?php
include_once '../estructura/footer.php';
?>