<?php
include_once '../../configuracion.php';
if (!isset($_SESSION)) {
    session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo json_encode([
        'success' => false,
        'message' => 'El carrito está vacío'
    ]);
    exit;
}


// // Obtener el usuario actual de la sesión
// if (!isset($_SESSION['idusuario'])) {
//     echo json_encode([
//         'success' => false,
//         'message' => 'Usuario no autenticado'
//     ]);
//     exit;
// }

$abmCompra = new abmCompra();
$abmProducto = new abmProducto();
$actualizacionesExitosas = true;
$errores = [];

// Verificar stock disponible antes de procesar
foreach ($_SESSION['carrito'] as $item) {
    $paramBusqueda = ['idProducto' => $item['idProducto']];
    $productos = $abmProducto->buscar($paramBusqueda);
    
    if (!empty($productos)) {
        $producto = $productos[0];
        $stockActual = $producto->getProductoStock();
        $cantidadComprada = $item['cantidad'];
        
        if ($stockActual < $cantidadComprada) {
            $actualizacionesExitosas = false;
            $errores[] = "Stock insuficiente para el producto: " . $producto->getProductoNombre();
        }
    } else {
        $actualizacionesExitosas = false;
        $errores[] = "Producto no encontrado: " . $item['idProducto'];
    }
}

if (!$actualizacionesExitosas) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al verificar stock: ' . implode(', ', $errores)
    ]);
    exit;
}

// Preparar datos para la compra
$datosCompra = [];
foreach ($_SESSION['carrito'] as $item) {
    $datosCompra[] = [
        'idProducto' => $item['idProducto'],
        'cantidadCompra' => $item['cantidad']
    ];
}

// Obtener objeto usuario
$abmUsuario = new abmUsuario();
$paramUsuario = ['idUsuario' => $_SESSION['idUsuario']];
$listaUsuarios = $abmUsuario->buscar($paramUsuario);
if (empty($listaUsuarios)) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener datos del usuario'
    ]);
    exit;
}
$objUsuario = $listaUsuarios[0];

// Crear la compra y sus estados
try {
    // Iniciar transacción
    $db = new BaseDatos();
    $db->Iniciar();
    
    // Alta de la compra
    if ($abmCompra->altaCompra($datosCompra, $objUsuario)) {
        // La función altaCompra ya maneja la actualización del stock y la creación del estado inicial
        $_SESSION['carrito'] = [];
        $db->Commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Compra procesada exitosamente'
        ]);
    } else {
        $db->Rollback();
        echo json_encode([
            'success' => false,
            'message' => 'Error al procesar la compra'
        ]);
    }
}catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en la transacción: ' . $e->getMessage()
    ]);
    exit;
}
?>