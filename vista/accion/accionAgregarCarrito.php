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
    echo json_encode(['success' => false, 'message' => 'Debe iniciar sesión para agregar productos al carrito']);
    exit();
}
$abmCarrito = new abmCarrito();
$carrito=$abmCarrito->verificarCarrito();
echo json_encode([
    'success' => $abmCarrito->getsuccess(), 
    'message' => $abmCarrito->getMensaje(), 
    'carrito' => $_SESSION['carrito'],
    'cantidad' => count($_SESSION['carrito'])
]);
exit();
?>