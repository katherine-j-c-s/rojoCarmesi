<?php
include_once '../../configuracion.php';
$datos = data_submitted();
$abmMenu = new abmMenu();
$listaMenu = $abmMenu->buscar($datos);

if (!empty($listaMenu)) {
    $objMenu = $listaMenu[0];
    $datos['idmenu'] = $objMenu->getIdmenu();
    $datos['menombre'] = $objMenu->getMenombre();
    $datos['medescripcion'] = $objMenu->getMedescripcion();

    // Verifica si el menú tiene un menú padre antes de llamar a getIdmenu()
    $objMenuPadre = $objMenu->getObjMenu();
    $datos['idpadre'] = $objMenuPadre ? $objMenuPadre->getIdmenu() : null;

    // Establece la fecha de deshabilitación o la habilita nuevamente
    if ($objMenu->getMedeshabilitado() == null) {
        $datos['medeshabilitado'] = date('Y-m-d h:i:s', time());
    } else {
        $datos['medeshabilitado'] = null;
    }

    $exito = $abmMenu->modificacion($datos);
}

// Redirige después de la modificación
header("Location: ../ejercicios/listarMenu.php");
include_once '../estructura/footer.php';
?>
