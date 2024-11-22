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
//crea un objeto de la clase abmCompra y abmProducto 
$abmCompra = new abmCompra();
$abmProducto = new abmProducto();
$actualizacionesExitosas = true;
$errores = [];

// Verificar stock disponible antes de procesar
foreach ($_SESSION['carrito'] as $item) {
    $paramBusqueda = ['idProducto' => $item['idProducto']];
    $productos = $abmProducto->buscar($paramBusqueda);

    // Verificar si el producto existe
    if (!empty($productos)) {
        $producto = $productos[0];
        $stockActual = $producto->getProductoStock();
        $cantidadComprada = $item['cantidad'];

        // Verificar si hay stock suficiente
        if ($stockActual < $cantidadComprada) {
            $actualizacionesExitosas = false;
            $errores[] = "Stock insuficiente para el producto: " . $producto->getProductoNombre();
        }
    } else {
        $actualizacionesExitosas = false;
        $errores[] = "Producto no encontrado: " . $item['idProducto'];
    }
}
// Si no hay stock suficiente, mostrar mensaje de error
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
try {
    // Iniciar transacción
    $db = new BaseDatos();
    
    // Verificar la conexión
    if (!$db->Iniciar()) {
        throw new Exception("No se pudo iniciar la conexión con la base de datos");
    }
    
    // Iniciar transacción usando PDO
    $db->beginTransaction();
    
    // Alta de la compra
    if ($abmCompra->altaCompra($datosCompra, $objUsuario)) {
        // Limpiar carrito
        $_SESSION['carrito'] = [];
        
        // Commit transaction
        $db->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Compra procesada exitosamente'
        ]);
    } else {
        // Rollback transaction 
        $db->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'Error al procesar la compra'
        ]);
    }
} catch (Exception $e) {
    // se asegura que si algo falla se reviertan todas las operaciones
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    
    echo json_encode([
        'success' => false,
        'message' => 'Error en la transacción: ' . $e->getMessage()
    ]);
}
?>