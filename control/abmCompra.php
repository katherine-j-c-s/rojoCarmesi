<?php

class abmCompra
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
    public function verificarStock(){
        $abmProducto = new abmProducto();
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
                    $this->setsuccess(false);
                    $this->setMensaje("Stock insuficiente para el producto: " . $producto->getProductoNombre());
                }
            } else {
                $this->setsuccess(false);
                $this->setMensaje("Producto no encontrado: " . $item['idProducto']);
            }
        }
        // Si no hay stock suficiente, mostrar mensaje de error
        if (!$this->getsuccess()) {
            $this->setMensaje("Error al verificar stock: " . implode(', ', $errores));
            echo json_encode([
                'success' => $this->getsuccess(),
                'message' => $this->getMensaje()
            ]);
            exit;
        }
    }
    public function altaCompra($param, $objUsuario)
    {
        //falta instancia de compra estado! y sumar el total de la compra!!
        $respuesta = false;
        $objCompra = null;
        $arregloProductos = $param;

        // echo 'ESTE HAY QUE PASAR A COLECCION ITEMS';
        // print_r($arregloProductos);

        $arregloCompraItem = array();
        $objCompra = new compra();
        $datosCompra = [
            'idCompra' => '',
            'compraFecha' => date('Y-m-d h:i:s', time()),
            'objUsuario' => $objUsuario,
            'coleccionItems' => $arregloCompraItem
        ];
        $objCompra->setear($datosCompra);
        $objCompra->insertar();
        $idCompra = $objCompra->getIdCompra();
        // echo idcompra

        $abmCompraEstadoTipo = new abmCompraEstadoTipo();
        $datos['idCompraEstadoTipo'] = 1;
        $listaCompraEstadoTipo = $abmCompraEstadoTipo->buscar($datos);
        $objCompraEstadoTipo = $listaCompraEstadoTipo[0];
        $idCompraEstadoTipo = $objCompraEstadoTipo->getIdCompraEstadoTipo();
        $abmCompraEstado = new abmCompraEstado();
        // echo "DATOS COMPRA ITEM";
        $datosCompraEstado = [
            'idCompraEstado' => '',
            'idCompra' => $idCompra,
            'idCompraEstadoTipo' => $idCompraEstadoTipo,
            'compraEstadoFechaInicial' => date("Y-m-d H:i:s"),
            'compraEstadoFechaFinal' => '0000-00-00 00:00:00'
        ];

        // print_r($datosCompraEstado);
        $abmCompraEstado->alta($datosCompraEstado);


        foreach ($arregloProductos as $producto) {
            $nuevoStock['productoStock'] = 0;
            $cantidadAdescontar = 0;
            $cantidadActual = 0;
            $abmCompraItem = new abmCompraItem();

            $datosCompraItem = [
                'idCompraItem' => '',
                'idProducto' => $producto['idProducto'],
                'idCompra' => $idCompra,
                'compraItemCantidad' => $producto['cantidadCompra']

            ];



            if ($abmCompraItem->alta($datosCompraItem)) {

                $objabmProducto = new abmProducto();

                $listaProductos = $objabmProducto->buscar($datosCompraItem);
                $objProducto = $listaProductos[0];

                // echo 'ABM COMPRA OBJ PRODUCTO  ';
                // print_r($objProducto);

                $nombreProducto = $objProducto->getProductoNombre();
                $productoDetalle = $objProducto->getProductoDetalle();
                $productoPrecio = $objProducto->getProductoPrecio();
                $cantidadActual = $objProducto->getProductoStock();

                $cantidadAdescontar = $datosCompraItem['compraItemCantidad'];

                //se resta la cantidad comprada al stock actual
                $nuevoStock['productoStock'] = $cantidadActual - $cantidadAdescontar;
                $datosProducto = [
                    'idProducto' => $producto['idProducto'],
                    'productoNombre' => $nombreProducto,
                    'productoPrecio' => $productoPrecio,
                    'productoDetalle' => $productoDetalle,
                    'productoStock' => $nuevoStock['productoStock']
                ];
                
                //se actualiza el stock del producto
                $objabmProducto->modificacion($datosProducto);

                $datosNuevaCompraItem = [
                    'idProducto' => $producto['idProducto'],
                    'idCompra' => $idCompra,
                    'compraItemCantidad' => $producto['cantidadCompra']
                ];

                $objCompraItem = $abmCompraItem->buscar($datosNuevaCompraItem);

                // echo 'ESTE ES EL OBJ COMPRA ITEM DE ABM COMPRA';
                // print_r($objCompraItem);

                array_push($arregloCompraItem, $objCompraItem);
                $respuesta = true;
            }
            $objCompra->setColeccionItems($arregloCompraItem);
        }
        return $respuesta;
    }
    public function cargarObjeto($param)
    {
        $obj = null;

        if (
            array_key_exists('idCompra', $param) and
            array_key_exists('compraFecha', $param) and
            array_key_exists('objUsuario', $param)
        ) {
            $objUsuario = new usuario();
            $objUsuario->setIdUsuario($param['idUsuario']);
            $objUsuario->cargar();
            $obj = new compra();
            $obj->setear($param['idCompra'], $param['compraFecha'], $objUsuario);
        }
        return $obj;
    }
    public function prepararDatos(){
        // Preparar datos para la compra
        $datosCompra = [];
        foreach ($_SESSION['carrito'] as $item) {
            $datosCompra[] = [
                'idProducto' => $item['idProducto'],
                'cantidadCompra' => $item['cantidad']
            ];
        }
        return $datosCompra;
    }

    public function ObtenerDatosUsuario(){
        // Obtener objeto usuario
        $abmUsuario = new abmUsuario();
        $paramUsuario = ['idUsuario' => $_SESSION['idUsuario']];
        $listaUsuarios = $abmUsuario->buscar($paramUsuario);
        if (empty($listaUsuarios)) {
            $this->setsuccess(false);
            $this->setMensaje("Error al obtener datos del usuario");
        }

        $objUsuario = $listaUsuarios[0];
        return $objUsuario;
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
        // print_r($param);
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
    public function buscar($param)
    {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idCompra']))
                $where .= ' and idCompra = ' . "'" . $param['idCompra'] . "'";
            if (isset($param['compraFecha']))
                $where .= ' and compraFecha =' . $param['compraFecha'] . "'";
            if (isset($param['idUsuario']))
                $where .= ' and idUsuario =' . "'" . $param['idUsuario'] . "'";
        }
        $arreglo = compra::listar($where);
        return $arreglo;
    }
}
