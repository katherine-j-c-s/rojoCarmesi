<?php

class abmMenuRol{
   
    public function cargarObjeto($param){
        $obj = null;
        if (array_key_exists('idRol', $param) and array_key_exists('idMenu', $param)){
            $obj = new menuRol();
            $objRol = new abmRol();
            $objMenu = new abmMenu();
            $objRol = $objRol ->buscar($param);
            $objMenu = $objMenu->buscar($param);
            $obj->setear($objRol, $objMenu);
        }
        return $obj;
    }

    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */
    public function seteadosCamposClaves($param){
        $resp = false;
        if (isset($param)){
            $resp = true;
        }
        return $resp;
    }

    public function editarRolesMenu($roles, $idMenu){
        $idRolesMenu = [];
        $mensaje = "";
        $menuRol = new menuRol();

        // Buscar roles asociados al menú actual
        $sql = "idmenu = " . $idMenu;
        $objMenuRol = $menuRol->listar($sql);

        // Obtener los IDs de los roles asociados
        foreach ($objMenuRol as $menuRol) {
            $idRolesMenu[] = $menuRol->getObjRol()->getIdRol();
        }

        // Comparar la cantidad de roles
        if (count($roles) > count($idRolesMenu)) {
            foreach ($roles as $idRol) {
                // Verificar si $idRol está en $idRolesMenu
                if (!in_array($idRol, $idRolesMenu)) {
                   $menuRol->insertar($idMenu,$idRol);
                   $mensaje.= "lógica para insertar el nuevo rol ID $idRol  al menú";
                }
            }
        }elseif (count($roles) < count($idRolesMenu))  {
            foreach ($idRolesMenu as $idRolMenu) {
                // Verificar si $idRol está en $idRolesMenu
                if (!in_array($idRolMenu, $roles)) {
                    $menuRol->eliminar($idMenu,$idRolMenu);
                    $mensaje.= "se ha eliminado el idRolMenu $idRolMenu de los rol Menu";
                }
            }
        } else {
            // Si tienen la misma cantidad, verificar manualmente los elementos
            foreach ($idRolesMenu as $idRolMenu) {
                $menuRol->eliminar($idMenu,$idRolMenu);
            }
            foreach ($roles as $idRol) {
                $menuRol->insertar($idMenu,$idRol);
                $mensaje.= "lógica para Cambiar el rol ID $idRol  al menú";
            }
        }

        return $mensaje; // Retornar mensajes con las acciones realizadas
    }

    /**
     * 
     * @param array $param
     */
    public function alta($param){
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
            $abmMenuRol = new abmMenuRol();
            $arregloRoles = $abmMenuRol->buscar($param);
            foreach ($arregloRoles as $objRol) {
                $abmMenuRol->baja($param);
            }

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
    public function modificacion($param){
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
            if (isset($param['idRol']))
                $where .= ' and idRol = ' ."'". $param['idRol']."'";
            if (isset($param['idmenu']))
                $where .= ' and idmenu =' . $param['idmenu'] . "'";
        }
        $arreglo = menuRol::listar($where);
        return $arreglo;
    }
}
