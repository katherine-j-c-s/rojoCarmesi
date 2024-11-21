<?php
class Verificador {
    /**
     * Verifica si un usuario tiene permiso para acceder a una página específica
     * @param string $paginaActual - URL de la página actual
     * @param Session $sesion - Objeto de sesión actual
     * @return bool|array - true si tiene permiso, array con redirección y mensaje si no tiene permiso
     */
    public static function verificarPermiso($paginaActual, $sesion) {
        if (!$sesion->activa()) {
            return [
                'permiso' => false,
                'redirect' => 'login.php',
                'mensaje' => 'No hay sesión activa'
            ];
        }

        $rolActivo = $sesion->getRolActivo();
        if ($rolActivo == null) {
            return [
                'permiso' => false,
                'redirect' => 'login.php',
                'mensaje' => 'No hay rol activo'
            ];
        }

        // Obtener los menús permitidos para el rol actual
        $menurol = new AbmMenuRol();
        $menu = new AbmMenu();
        
        $datosMR = ['idRol' => $rolActivo->getIdRol()];
        $menuRoles = $menurol->buscar($datosMR);

        // Verificar si la página actual está en los menús permitidos
        $tienePermiso = false;
        foreach ($menuRoles as $menuRole) {
            $menuItem = $menu->buscar(['idmenu' => $menuRole->getObjMenu()->getIdMenu()]);
            if (!empty($menuItem)) {
                $item = $menuItem[0];
                $link = self::modificarLink($item->getMeDescripcion());
                $pag = self::modificarLink($paginaActual);

                // Comparar la URL actual con la descripción del menú procesada
                if (strpos($pag, $link) !== false) {
                    $tienePermiso = true;
                    break;
                }
            }
        }

        if (!$tienePermiso) {
            return [
                'permiso' => false,
                'redirect' => '/rojocarmesi/vista/home/index.php',
                'mensaje' => 'No tiene permisos para acceder a esta página'
            ];
        }

        return ['permiso' => true];
    }

    /**
     * Modifica un enlace eliminando prefijos específicos.
     *
     * @param string $link - El enlace a modificar.
     * @return string - El enlace modificado.
     */
    private static function modificarLink($link) {
        // Si el enlace comienza con "/rojocarmesi/vista/"
        if (strpos($link, '/rojocarmesi/vista/') === 0) {
            return substr($link, strlen('/rojocarmesi/vista'));
        }
    
        // Si el enlace comienza con "../"
        if (strpos($link, '../') === 0) {
            return substr($link, 2);
        }
    
        // Si no coincide con ninguno de los casos, devolver el enlace tal cual
        return $link;
    }
}


?>