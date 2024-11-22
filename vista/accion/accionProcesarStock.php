<?php
// accionProcesarCompra.php

include_once '../../configuracion.php';

if (!isset($_SESSION)) {
    session_start();
}

// Verificar si hay productos en el carrito
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo json_encode([
        'success' => false,
        'message' => 'El carrito está vacío'
    ]);
    exit;
}

$abmProducto = new abmProducto();
$abmProducto->procesarStockProductos();
if($abmProducto->getSuccess()){
    $_SESSION['carrito'] = [];
    echo json_encode([
        'success' => $abmProducto->getSuccess(),
        'message' => 'Compra procesada exitosamente'
    ]);
} else {
    echo json_encode([
        'success' => $abmProducto->getSuccess(),
        'message' => 'Error al procesar la compra: ' . implode(', ', $errores)
    ]);
}
?>