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

// Validar datos recibidos
if (!isset($_POST['idProducto']) || !isset($_POST['compraItemCantidad'])) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit();
}

$idProducto = $_POST['idProducto'];
$cantidad = max(1, intval($_POST['compraItemCantidad']));

// Instanciar controladores
$abmProducto = new abmProducto();

// Buscar el producto
$productos = $abmProducto->buscar(['idProducto' => $idProducto]);
if (empty($productos)) {
    echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
    exit();
}

$producto = $productos[0];

// Verificar stock
if ($producto->getProductoStock() < $cantidad) {
    echo json_encode(['success' => false, 'message' => 'Stock insuficiente']);
    exit();
}

// Inicializar carrito en sesión si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Verificar si el producto ya está en el carrito
$encontrado = false;
foreach ($_SESSION['carrito'] as &$item) {
    if ($item['idProducto'] == $idProducto) {
        $item['cantidad'] += $cantidad;
        $encontrado = true;
        break;
    }
}

// Si no se encontró, agregar nuevo producto
if (!$encontrado) {
    $arregloArchivos = $abmProducto->obtenerArchivos(md5($producto->getIdProducto()));
    
    $_SESSION['carrito'][] = [
        'idProducto' => $producto->getIdProducto(),
        'nombre' => $producto->getProductoNombre(),
        'precio' => $producto->getProductoPrecio(),
        'cantidad' => $cantidad,
        'imagen' => $arregloArchivos,
        'stock' => $producto->getProductoStock()
    ];
}

echo json_encode([
    'success' => true, 
    'message' => 'Producto agregado al carrito', 
    'carrito' => $_SESSION['carrito'],
    'cantidad' => count($_SESSION['carrito'])
]);
exit();
?>