<?php
include_once '../../configuracion.php';
include_once '../../utiles/PHPMailer/enviaMail.php';

$datos = data_submitted();
//se busca el estado de la compra para modificarlo
$abmCompraEstado = new abmCompraEstado();
$listaCompraEstado = $abmCompraEstado->buscar(['idCompraEstado' => $datos['idCompraEstado']]);

$objCompraEstado = $listaCompraEstado[0];
$datosNuevos = $abmCompraEstado->editarEstadoCompra($datos , $objCompraEstado);
if($abmCompraEstado->modificacion($datosNuevos)){
    $idCompra = $datos['idCompra'];
    $idEstadoTipo = $datos['idCompraEstadoTipo'];
    $abmCompraEstado->enviarMail($idCompra, $idEstadoTipo);
    
    //creamos una compra para buscar su id >
    $abmCompra = new abmCompra();
    $idCompra = $datos['idCompra'];
    $compra = $abmCompra->buscar(['idCompra' => $idCompra]);
    $mailUsuario = $compra[0]->getObjUsuario()->getUsMail();
    $nombreUsuario = $compra[0]->getObjUsuario()->getUsNombre();
    

    $abmCompraEstadoTipo = new abmCompraEstadoTipo();
    $idEstadoTipo = $datos['idCompraEstadoTipo'];
    $compraEstadoTipo = $abmCompraEstadoTipo->buscar(['idCompraEstadoTipo' => $idEstadoTipo]);
    $estadoTipo = $compraEstadoTipo[0]->getCompraEstadoTipoDescripcion();
    $coleccionItems = $compra[0]->getColeccionItems();
    foreach ($coleccionItems as $product) {
        $item = $product->getObjProducto();
        $cantidadComprada = $product->getCompraItemCantidad();
        $objabmProducto = new abmProducto();
        $datosProductoBusqueda = ['idProducto' => $item->getIdProducto()];
        $listaProductos = $objabmProducto->buscar($datosProductoBusqueda);
        $objProducto = $listaProductos[0];

        $nombreProducto = $objProducto->getProductoNombre();
        $productoDetalle = $objProducto->getProductoDetalle();
        $productoPrecio = $objProducto->getProductoPrecio();
        $cantidadActual = $objProducto->getProductoStock();

        // En vez de restar, sumamos la cantidad al stock actual
        $nuevoStock = $cantidadActual + $cantidadComprada;
        
        $datosProducto = [
            'idProducto' => $item->getIdProducto(),
            'productoNombre' => $nombreProducto,
            'productoPrecio' => $productoPrecio,
            'productoDetalle' => $productoDetalle,
            'productoStock' => $nuevoStock
        ];
        
        $objabmProducto->modificacion($datosProducto);
    }

    $mensaje = "Estado de la compra modificado con exito!";
    header("Location: ../ejercicios/editarEstadoCompra.php?Message=" . urlencode($mensaje));
}else{
    $mensaje = "El estado de la compra no se modifico, contacte al administrador!";
    header("Location: ../ejercicios/editarEstadoCompra.php?mensajeMessage=" . urlencode($mensaje));
}
?>