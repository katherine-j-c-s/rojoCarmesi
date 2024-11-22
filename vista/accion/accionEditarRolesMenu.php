<?php
include_once '../../configuracion.php';
include_once '../estructura/cabecera.php';
$datos = data_submitted();
$abmMenuRol = new abmMenuRol();
$mensaje = $abmMenuRol->editarRolesMenu($datos["roles"], $datos["idMenu"]);
header("Location: ../ejercicios/listarMenu.php?Message=" . urlencode($mensaje));
include_once '../estructura/footer.php';
?>