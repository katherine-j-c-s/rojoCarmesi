<?php
class abmCarrito
{
    private $mensaje;
    private $success;

    public function __construct() {
        $this->mensaje = "";
        $this->success = true;
    }
    public function getMensaje()
    {
        return $this->mensaje;
    }
    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;
        return $this;
    }
    public function getsuccess()
    {
        return $this->success;
    }
    public function setsuccess($success)
    {
        $this->success = $success;
        return $this;
    }
    public function validarDatosRecibidos()
    {
        // Validar datos recibidos
        if (!isset($_POST['idProducto']) || !isset($_POST['compraItemCantidad'])) {
            // echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            $this->setMensaje("datos Invalidos");
            $this->setsuccess(false);
        }
    }
    public function verificarProducto(){
        
        $idProducto = $_POST['idProducto'];

        $cantidad = max(1, intval($_POST['compraItemCantidad']));
            // Instanciar controladores
        $abmProducto = new abmProducto();

        // Buscar el producto
        $productos = $abmProducto->buscar(['idProducto' => $idProducto]);
        if (empty($productos)) {
            // echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
            $this->setsuccess(false);
            $this->setMensaje("Producto no encontrado");
        }

        $producto = $productos[0];

        // Verificar stock
        if ($producto->getProductoStock() < $cantidad) {
            // echo json_encode(['success' => false, 'message' => 'Stock insuficiente']);
            $this->setsuccess(false);
            $this->setMensaje("Stock insuficiente");
        }

    }

    public function verificarCarrito(){
        self::validarDatosRecibidos();
        self::verificarProducto();

        if($this->getsuccess()){
            // Buscar el producto
            $idProducto = $_POST['idProducto'];
            $abmProducto = new abmProducto();
            $productos = $abmProducto->buscar(['idProducto' => $idProducto]);
            $producto = $productos[0];

            $abmProducto = new abmProducto();
            if (!isset($_SESSION['carrito'])) {
                $_SESSION['carrito'] = [];
            }
            
            // Verificar si el producto ya está en el carrito
            $encontrado = false;
            $idProducto = $_POST['idProducto'];
            $cantidad = max(1, intval($_POST['compraItemCantidad']));
            
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
            
            $this->setsuccess(true);
            $this->setMensaje("Producto agregado al carrito");   
        }
    }

}
