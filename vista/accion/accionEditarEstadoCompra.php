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
    $mensaje = "Estado de la compra modificado con exito!";
    header("Location: ../ejercicios/editarEstadoCompra.php?Message=" . urlencode($mensaje));
}else{
    $mensaje = "El estado de la compra no se modifico, contacte al administrador!";
    header("Location: ../ejercicios/editarEstadoCompra.php?mensajeMessage=" . urlencode($mensaje));
}
?>