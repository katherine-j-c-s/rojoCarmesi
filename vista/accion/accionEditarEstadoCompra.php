<?php
include_once '../../configuracion.php';
include_once '../../utiles/PHPMailer/enviaMail.php';

$datos = data_submitted();
$abmCompraEstado = new abmCompraEstado();
$listaCompraEstado = $abmCompraEstado->buscar(['idCompraEstado' => $datos['idCompraEstado']]);

$objCompraEstado = $listaCompraEstado[0];
$datosNuevos = $abmCompraEstado->editarEstadoCompra($datos , $objCompraEstado);
if($abmCompraEstado->modificacion($datosNuevos)){
    //creamos una compra para buscar su id >
    $abmCompra = new abmCompra;
    $idCompra = $datos['idCompra'];
    $compra = $abmCompra->buscar(['idCompra' => $idCompra]);
    $mailUsuario = $compra[0]->getObjUsuario()->getUsMail();
    $nombreUsuario = $compra[0]->getObjUsuario()->getUsNombre();

    $abmCompraEstadoTipo = new abmCompraEstadoTipo();
    $idEstadoTipo = $datos['idCompraEstadoTipo'];
    $compraEstadoTipo = $abmCompraEstadoTipo->buscar(['idCompraEstadoTipo' => $idEstadoTipo]);
    $estadoTipo = $compraEstadoTipo[0]->getCompraEstadoTipoDescripcion();

    $mail = new enviarMail();
    $mail->newEmail("katherine.contreras@est.fi.uncoma.edu.ar","",$mailUsuario,$nombreUsuario,"Estado de compra Cambiado","El estado de Compra nº $idCompra se ha cambiado a:  $estadoTipo");
    $mensaje = "Estado de la compra modificado con exito!";
    header("Location: ../ejercicios/editarEstadoCompra.php?Message=" . urlencode($mensaje));
}else{
    $mensaje = "El estado de la compra no se modifico, contacte al administrador!";
    header("Location: ../ejercicios/editarEstadoCompra.php?mensajeMessage=" . urlencode($mensaje));
}
?>