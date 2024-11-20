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
                // Comparar la URL actual con la descripción del menú
                if (strpos($paginaActual, $item->getMeDescripcion()) !== false) {
                    $tienePermiso = true;
                    break;
                }
            }
        }

        if (!$tienePermiso) {
            return [
                'permiso' => false,
                'redirect' => 'index.php',
                'mensaje' => 'No tiene permisos para acceder a esta página'
            ];
        }

        return ['permiso' => true];
    }
}

// Ejemplo de uso en una página segura:
function verificarAcceso() {
    $sesion = new session();
    $paginaActual = $_SERVER['PHP_SELF'];
    
    $resultado = Verificador::verificarPermiso($paginaActual, $sesion);
    
    if (!$resultado['permiso']) {
        if (isset($resultado['mensaje'])) {
            $mensaje = urlencode($resultado['mensaje']);
            header("Location: {$resultado['redirect']}?Message={$mensaje}");
        } else {
            header("Location: {$resultado['redirect']}");
        }
        exit;
    }
}
?>