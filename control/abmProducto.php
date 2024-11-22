<?php

class abmProducto
{
    private $success;
    private $mensaje;

    public function __construct()
    {
        $this->success = true;
        $this->mensaje = "";
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public function getMensaje()
    {
        return $this->mensaje;
    }

    public function setSuccess($success)
    {
        $this->success = $success;
    }

    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;
    }

    public function subirArchivo($datos)
    {
        $nombreArchivoImagen = $datos . ".jpg";
        $dir = '../assets/img/imagenesProductos/';

        $error = "";
        $todoOK = true;

        /*Primero subamos la imagen*/
        /*Veamos si se pudo subir a la carpeta temporal*/
        if ($_FILES["productoImagen"]["error"] <= 0) {
            $todoOK = true;
            $error = "";
        } else {
            $todoOK = false;
            $error = "ERROR: no se pudo cargar el archivo de imagen. No se pudo acceder al archivo Temporal";
        }

        //El control del tipo ya lo tengo en el formulario, asi que no lo voy a controlar acá.
        //Si, voy a controlar el tema del tamaño

        // if ($todoOK && $_FILES['productoImagen']["size"] / 1024 > 300) {
        //     $error = "ERROR: El archivo " . $nombreArchivoImagen . " supera los 300 KB.";
        //     $todoOK = false;
        // }

        if ($todoOK && !copy($_FILES['productoImagen']['tmp_name'], $dir . $nombreArchivoImagen)) {
            $texto = "ERROR: no se pudo cargar el archivo de imagen.";
            $todoOK = false;
        }
        if($error) echo $error;
        return $todoOK;
    }

    public function obtenerArchivos($idProducto)
    {
        $directorio = '../assets/img/imagenesProductos/' . $idProducto . ".jpg";

        return $directorio;
    }
    public function cargarObjeto($param)
    {
        $obj = null;

        if (
            array_key_exists('idProducto', $param) and
            array_key_exists('productoNombre', $param) and
            array_key_exists('productoDetalle', $param) and
            array_key_exists('productoStock', $param) and
            array_key_exists('productoPrecio', $param)
        ) {
            $obj = new producto();
            $obj->setear([
                'idProducto' => $param['idProducto'],
                'productoNombre' => $param['productoNombre'],
                'productoDetalle' => $param['productoDetalle'],
                'productoStock' => $param['productoStock'],
                'productoPrecio' => $param['productoPrecio']
            ]);
        }

        return $obj;
    }

    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */
    public function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param)) {
            $resp = true;
        }
        return $resp;
    }

    /**
     * 
     * @param array $param
     */
    public function alta($param)
    {
        $resp = false;
        $elObjtTabla = $this->cargarObjeto($param);
        if ($elObjtTabla != null and $elObjtTabla->insertar()) {
            $resp = true;
        }
        return $resp;
    }
    /**
     * permite eliminar un objeto 
     * @param array $param
     * @return boolean
     */
    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtTabla = $this->cargarObjeto($param);
            if ($elObjtTabla != null and $elObjtTabla->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }
// lo que hace es crear un directorio con el id del producto en la carpeta de imagenes
    // public function altaImagen($objProducto, $param)
    // {
    //     //creo el directorio 
    //     $directorio = md5($objProducto->getIdProducto());

    //     mkdir($GLOBALS['IMAGENES'] . $directorio, 0777);
    // }

    /**
     * permite modificar un objeto
     * @param array $param
     * @return boolean
     */
    public function modificacion($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtTabla = $this->cargarObjeto($param);
            if ($elObjtTabla != null and $elObjtTabla->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * permite buscar un objeto
     * @param array $param
     * @return boolean
     */
    public function buscar($param = '')
    {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idProducto']))
                $where .= ' and idProducto = ' . "'" . $param['idProducto'] . "'";
            if (isset($param['productoNombre']))
                $where .= ' and productoNombre = ' . "'" . $param['productoNombre'] . "'";
            if (isset($param['productoDetalle']))
                $where .= ' and productoDetalle =' . "'" . $param['productoDetalle'] . "'";
            if (isset($param['productoStock']))
                $where .= ' and productoStock =' . "'" . $param['productoStock'] . "'";
            if (isset($param['productoPrecio']))
                $where .= ' and productoPrecio =' . "'" . $param['productoPrecio'] . "'";
        }
        $arreglo = producto::listar($where);
        return $arreglo;
    }

    public function procesarStockProductos(){
        // Procesar cada producto en el carrito
        foreach ($_SESSION['carrito'] as $item) {
            // Buscar el producto en la base de datos
            $paramBusqueda = ['idProducto' => $item['idProducto']];
            $productos = self::buscar($paramBusqueda);
            
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
                    if (!self::modificacion($datosActualizacion)) {
                        $this->setSuccess(false);
                        $this->setMensaje("Error al actualizar el stock del producto: " . $producto->getProductoNombre());
                    }
                } else {
                    $this->setSuccess(false);
                    $this->setMensaje("Stock insuficiente para el producto: " . $producto->getProductoNombre());
                }
            } else {
                $this->setSuccess(false);
                $this->setMensaje("Producto no encontrado: " . $item['idProducto']);
            }
        }

        // Si todas las actualizaciones fueron exitosas, limpiar el carrito
        if ($this->getSuccess()) {
            $this->setSuccess(true);
            $this->setMensaje("Compra procesada exitosamente");
        } else {
            $this->setSuccess(false);
            $this->setMensaje("Error al procesar la compra");
        }
    }

    public function accionCrearProducto($datos){
        $busquedaNombreProducto = ['productoNombre' => $datos['productoNombre']];
        $respuesta1 = $this->buscar($busquedaNombreProducto);
        if (count($respuesta1) > 0) {
            echo  "El Producto no se ha podido crear porque ya existe ese nombre de producto.";
            $mensaje = "El Producto no se ha podido crear porque ya existe ese nombre de producto.";
            header("Location: ../ejercicios/crearProducto.php?Message=" . urlencode($mensaje));
        }else{
            $datos['idProducto']='';
            $producto = $this->alta($datos);
            $busqueda = [
                "productoNombre" => $datos['productoNombre']
            ];
            $objProducto = $this->buscar($busqueda);
            $idProductoImagen = md5($objProducto[0]->getIdProducto());
        if($this->subirArchivo($idProductoImagen)){
            if ($producto) {
                $mensaje = "El producto se creó con exito";
                header("Location: ../ejercicios/mostrarProductos.php?Message=" . urlencode($mensaje));
            } 
        }
        
    }
    }
}

