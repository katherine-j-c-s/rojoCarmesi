<?php
include_once '../../configuracion.php';

header('Content-Type: application/json');

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar inicio de sesión
$sesion = new session();
if (!$sesion->activa()) {
    echo json_encode(['success' => false, 'message' => 'Debe iniciar sesión para modificar el carrito']);
    exit();
}

// Validar datos recibidos
if (!isset($_POST['idProducto']) || !isset($_POST['cambio'])) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit();
}

$idProducto = $_POST['idProducto'];
$cambio = intval($_POST['cambio']);

// Instanciar controladores
$abmProducto = new abmProducto();

// Buscar productos en el carrito
if (!isset($_SESSION['carrito'])) {
    echo json_encode(['success' => false, 'message' => 'Carrito vacío']);
    exit();
}

// Buscar el producto en el carrito
$productFound = false;
foreach ($_SESSION['carrito'] as &$item) {
    if ($item['idProducto'] == $idProducto) {
        // Verificar stock y límites
        $nuevaCantidad = $item['cantidad'] + $cambio;
        
        if ($nuevaCantidad <= 0) {
            // Eliminar el producto si la cantidad es 0 o menos
            $item = null;
        } elseif ($nuevaCantidad > $item['stock']) {
            echo json_encode(['success' => false, 'message' => 'Stock insuficiente']);
            exit();
        } else {
            $item['cantidad'] = $nuevaCantidad;
        }
        
        $productFound = true;
        break;
    }
}

// Limpiar elementos nulos del carrito
$_SESSION['carrito'] = array_filter($_SESSION['carrito']);

if (!$productFound) {
    echo json_encode(['success' => false, 'message' => 'Producto no encontrado en el carrito']);
    exit();
}

echo json_encode([
    'success' => true, 
    'message' => 'Cantidad actualizada', 
    'carrito' => $_SESSION['carrito'],
    'cantidad' => count($_SESSION['carrito'])
]);
exit();
?>