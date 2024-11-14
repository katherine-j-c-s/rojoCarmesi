<?php

$sesion = new session();
include_once '../../configuracion.php';
error_reporting(0);
//Verificacion si tiene la sesion activa en caso negativo reenviamos al loguin
if (!$sesion->activa()) {
    header('location:../ejercicios/login.php');
} else {
    // treaemos el usuario de la sesion, la lista de menu y el rolActivo de la sesion
    $objUsuario = $sesion->getObjUsuario();
    $menu = new AbmMenu();
    $arregloMenu = $menu->buscar("");

    // accedos al rolActivo de la sesion
    $rolActivo = $sesion->getRolActivo();
    // consultamos si el rol no esta activo buscamos el primero y lo asignamos
    if ($rolActivo == null) {
        $listaRoles = $sesion->getColeccionRol();
        // print_r($listaRoles);
        $objRol = $listaRoles[0];
        $sesion->setRolActivo($objRol);
        $rolActivo = $sesion->getRolActivo();
    }
    // verificamos si el usuario con el rol y la ruta y asignamos si tiene permiso
    $ruta = $_SERVER['SCRIPT_FILENAME'];
    $menurol = new abmMenuRol();
    $datosMR = ['idRol' => $rolActivo->getIdRol()];
    $coleccionMenuRolActivo = $menurol->buscar($datosMR);
    $tienePermiso = false;
    foreach ($coleccionMenuRolActivo as $objMenurol) {
        $stringMenu = $objMenurol->getObjMenu()->getMedescripcion();
        $StrMenu = substr($stringMenu, 3);
        if (str_contains($ruta, $StrMenu)) {
            $tienePermiso = true;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rojo Carmesi </title>
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="../css/styles.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="../js/jquery-easyui-1.6.6/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="../js/jquery-easyui-1.6.6/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="../js/jquery-easyui-1.6.6/themes/color.css">
    <link rel="stylesheet" type="text/css" href="../js/jquery-easyui-1.6.6/demo/demo.css">
    <script type="text/javascript" src="../js/jquery-easyui-1.6.6/jquery.min.js"></script>
    <script type="text/javascript" src="../js/jquery-easyui-1.6.6/jquery.easyui.min.js"></script>
</head>

<nav class="navbar navbar-expand-lg bg-white navbar-ligth shadow-lg fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="../home/index.php#page-top">
            <img src="../assets/img/logos/logo2.png" alt="..." />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php
            $idRol = $rolActivo->getIdRol();
            if ($idRol !== 3) {
        ?>
        <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
            <ul class="navbar-nav  ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link text-black" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Navegacion
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                        <li><a class="dropdown-item" href="../home/index.php#page-top">Inicio Usuario</a>
                        <li><a class="dropdown-item" href="../home/index.php#proximoseventos">Proximos Eventos</a></li>
                        <li><a class="dropdown-item" href="../home/index.php#quienesSomos">¿Quienes Somos?</a></li>
                        <li><a class="dropdown-item" href="../home/index.php#contact">Contacto</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-black" href="../ejercicios/crearProducto.php">Crear Producto</a>
                </li>
                <?php
                    if ($idRol == 1) {
                ?>
                    <li class="nav-item">
                        <a class="nav-link text-black" href="../ejercicios/editarMenu.php">Editar Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-black" href="../ejercicios/editarMenu.php">Agregar Permisos</a>
                    </li>
                <?php
                    }
                ?>
                <li class="nav-item dropdown">
                    <div class="btn-group" role="group" aria-label="Basic outlined example">
                        <a href="../ejercicios/carrito.php" class="btn btn-outline-dark"><i class="fas fa-shopping-cart mr-3" style="color: black;"></i></a>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill">
                            <?php
                            if ($sesion->getCarrito() == null) {
                                echo "0";
                            } else {
                                echo count($sesion->getCarrito());
                            } ?>

                        </span>
                        <?php
                            if ($idRol == 1) {
                        ?>
                            <a class="nav-link text-black btn btn-outline-dark" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                                <li class="dropdown-item">
                                    Usurario:<?php echo $objUsuario->getUsNombre()?>
                                </li>
                                <li class="dropdown-item">
                                    Rol:<?php echo $sesion->getRolActivo()->getRolDescripcion()?>
                                </li>
                                <li><a class="dropdown-item" href="../ejercicios/cambiarDatosUsuario.php#">Modificar Usuario</a></li>
                            </ul>
                        <?php
                            }
                        ?>
                        <a href="../accion/cerrarSesion.php" class="btn btn-outline-dark"> <i class="fas fa-sign-in-alt"></i>Log Out </a>
                    </div>
                </li>
            </ul>
        </div>
        <?php } else { ?>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-black" href="../home/index.php#page-top">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-black" href="../home/index.php#proximosEventos">Próximos Eventos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-black" href="../ejercicios/mostrarProductos.php">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-black" href="../home/index.php#quienesSomos">¿Quienes somos ?</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-black" href="../home/index.php#contact">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <a href="../ejercicios/carrito.php" class="btn btn-outline-dark"><i class="fas fa-shopping-cart mr-3" style="color: black;"></i></a>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill">
                                <?php
                                if ($sesion->getCarrito() == null) {
                                    echo "0";
                                } else {
                                    echo count($sesion->getCarrito());
                                } ?>

                            </span>
                            <a href="../accion/cerrarSesion.php" class="btn btn-outline-dark"> <i class="fas fa-sign-in-alt"></i>Log Out </a>
                        </div>
                    </li>
                </ul>
            </div>
        <?php }?>
    </div>
</nav>
<body id="page-top">