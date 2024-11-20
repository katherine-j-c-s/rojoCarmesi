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
$actualizacionesExitosas = true;
$errores = [];

// Procesar cada producto en el carrito
foreach ($_SESSION['carrito'] as $item) {
    // Buscar el producto en la base de datos
    $paramBusqueda = ['idProducto' => $item['idProducto']];
    $productos = $abmProducto->buscar($paramBusqueda);
    
    if (!empty($productos)) {
        $producto = $productos[0];
        $stockActual = $producto->getProductoStock();
        $cantidadComprada = $item['cantidad'];
        $nuevoStock = $stockActual - $cantidadComprada;
        
        // Verificar que haya suficiente stock
        if ($nuevoStock >= 0) {
            // Preparar datos para la actualización
            $datosActualizacion = [
                'idProducto' => $item['idProducto'],
                'productoNombre' => $producto->getProductoNombre(),
                'productoDetalle' => $producto->getProductoDetalle(),
                'productoStock' => $nuevoStock,
                'productoPrecio' => $producto->getProductoPrecio()
            ];
            
            // Actualizar el stock en la base de datos
            if (!$abmProducto->modificacion($datosActualizacion)) {
                $actualizacionesExitosas = false;
                $errores[] = "Error al actualizar el stock del producto: " . $producto->getProductoNombre();
            }
        } else {
            $actualizacionesExitosas = false;
            $errores[] = "Stock insuficiente para el producto: " . $producto->getProductoNombre();
        }
    } else {
        $actualizacionesExitosas = false;
        $errores[] = "Producto no encontrado: " . $item['idProducto'];
    }
}

// Si todas las actualizaciones fueron exitosas, limpiar el carrito
if ($actualizacionesExitosas) {
    $_SESSION['carrito'] = [];
    echo json_encode([
        'success' => true,
        'message' => 'Compra procesada exitosamente'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error al procesar la compra: ' . implode(', ', $errores)
    ]);
}
?>