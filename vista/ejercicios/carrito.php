<?php
//primero crear la compra recorremos el 
//arreglo carrito por cada posicion creo una compraitem 
//y armo la coleccion correspondiente a compra y se inicializa el estado de la compra
include_once '../../configuracion.php';
error_reporting(E_ERROR  | E_PARSE);
include_once '../../utiles/verificador.php';

$sesion = new session();
$paginaActual = $_SERVER['PHP_SELF'];

// Verificar permiso
$resultado = Verificador::verificarPermiso($paginaActual, $sesion);

$objRol=$sesion->getRolActivo();
$idRol=$objRol->getIdRol();
$datos = data_submitted();
if (!$sesion->activa()) {
  header('Location: index.php');
} else {
  include_once '../estructura/cabeceraSegura.php';
}
if (isset($_GET['Message'])) {
  print '<script type="text/javascript">alert("' . $_GET['Message'] . '");</script>';
}
if (!$resultado['permiso']) {
  $mensaje = $resultado['mensaje'];
  echo "</br></br></br></br></br></br>";
  echo "<h4 class='alert alert-danger'>$mensaje</h4>";
}else{
  $listaCarrito = $sesion->getCarrito();
  // print_r($listaCarrito);


//


?>
<?php
include_once "../../control/abmCarrito.php";
$abmCarrito= new abmCarrito();
$productos = $abmCarrito->obtenerProductos();
?>
<div class="container " style="margin-top: 90px;">
  <div class="columns">
    <div class="column">
        <h2 class="is-size-2">Productos existentes</h2>
        <a class="button is-success" href="crearProducto.php">Nuevo&nbsp;<i class="fa fa-plus"></i></a>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto) { ?>
                    <tr>
                        <td><?php echo $producto->nombre ?></td>
                        <td><?php echo $producto->descripcion ?></td>
                        <td>$<?php echo number_format($producto->precio, 2) ?></td>
                        <td>
                            <form action="./eliminar_producto.php" method="post">
                                <input type="hidden" name="id_producto" value="<?php echo $producto->id ?>">
                                <button class="button is-danger">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </form>
                        </td>
                    <?php } ?>
                    </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
<?php
}
include_once '../estructura/footer.php';

?>

