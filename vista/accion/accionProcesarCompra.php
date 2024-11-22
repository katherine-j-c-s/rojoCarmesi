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
$verificarStock = $abmCompra->verificarStock();
if($abmCompra->getsuccess()){
    $datos= $abmCompra->prepararDatos();
    $usuario= $abmCompra->ObtenerDatosUsuario();
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
        if ($abmCompra->altaCompra($datos, $usuario)) {
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
}else{
    echo json_encode([
        'success' => $abmCompra->getsuccess(),
        'message' => $abmCompra->getMensaje()
    ]);
    exit;
}