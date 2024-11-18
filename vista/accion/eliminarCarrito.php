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
if (!isset($_POST['idProducto'])) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit();
}

$idProducto = $_POST['idProducto'];

// Buscar productos en el carrito
if (!isset($_SESSION['carrito'])) {
    echo json_encode(['success' => false, 'message' => 'Carrito vacío']);
    exit();
}

// Filtrar productos, eliminando el producto específico
$_SESSION['carrito'] = array_filter($_SESSION['carrito'], function($item) use ($idProducto) {
    return $item['idProducto'] != $idProducto;
});

echo json_encode([
    'success' => true, 
    'message' => 'Producto eliminado del carrito',  
    'carrito' => $_SESSION['carrito'],
    'cantidad' => count($_SESSION['carrito'])
]);
exit();
?>