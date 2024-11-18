
<?php
# Es responsabilidad del programador hacer algo con los productos
include_once "../../control/abmCarrito.php";
$productos = obtenerProductosEnCarrito();
# Puede que solo quieras los ids, para ello invoca a obtenerIdsDeProductosEnCarrito();
var_dump($productos);
