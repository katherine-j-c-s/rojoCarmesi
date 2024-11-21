<?php
$sesion = new session();
// $sesion = new session();
include_once '../../configuracion.php';
include_once './asideCarrito.php';

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
        $objRol = $listaRoles[0];
        $sesion->setRolActivo($objRol);
        $rolActivo = $sesion->getRolActivo();
    }

    // optenemos los items del menu de la base de datos
    function getMenuItems($idRol)
    {
        $menurol = new AbmMenuRol();
        $menu = new AbmMenu();

        // optenemos todos los item al que este rol tiene permiso
        $datosMR = ['idRol' => $idRol];
        $menuRoles = $menurol->buscar($datosMR);

        $menuItems = [];
        foreach ($menuRoles as $menuRole) {
            $menuItem = $menu->buscar(['idmenu' => $menuRole->getObjMenu()->getIdMenu()]);
            if (!empty($menuItem)) {
                if ($menuItem[0]->getMeNombre() !== 'Permisos') {
                    $menuItems[] = $menuItem[0];
                }
            }
        }
        return $menuItems;
    }
    // organiza los items del menu en una estructura
    function menuStructure($menuItems)
    {
        $structure = [];

        // primero, encuentra todos los padres de menu items (donde el id del padre sea nulo)
        foreach ($menuItems as $item) {
            if ($item->getIdPadre() === null) {

                $structure[$item->getIdMenu()] = [
                    'item' => $item,
                    'children' => []
                ];
            }
        }

        // luego , asignamos a los hijos de los padres
        foreach ($menuItems as $item) {
            if ($item->getIdPadre() !== null && isset($structure[$item->getIdPadre()])) {
                $structure[$item->getIdPadre()]['children'][] = $item;
            }
        }

        return $structure;
    }

    $menuItems = getMenuItems($rolActivo->getIdRol());
    $menuHierarchy = menuStructure($menuItems);
}
//rol 
// 1 = administrador
// 2 = empleado
// 3 = usuario

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rojo Carmesi</title>
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

        <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
            <ul class="navbar-nav ms-auto">
                <?php
                // Verificar si es rol cliente (ID 3)
                if ($rolActivo->getIdRol() == 3) {
                    // Mostrar items individuales para cliente
                    foreach ($menuItems as $item) {
                        // Excluir items del menú Usuario ya que se manejan por separado
                        if ($item->getIdMenu() !== 4 && $item->getIdMenu() !== 2 && $item->getIdMenu() !== 9 && $item->getIdMenu() !== 24 && $item->getIdMenu() !== 14 && $item->getIdMenu() !== 16) {
                ?>
                            <li class="nav-item">
                                <a class="nav-link text-black" href="<?php echo $item->getMeDescripcion(); ?>">
                                    <?php echo $item->getMeNombre(); ?>
                                </a>
                            </li>
                        <?php
                        }
                    }
                } 
                    // Mantener la estructura original para otros roles
                foreach ($menuHierarchy as $parentId => $menuData) {
                    $parentMenu = $menuData['item'];
                    $children = $menuData['children'];

                    // Skip the Usuario menu as it will be handled separately
                    if ($parentMenu->getMeNombre() === 'Usuario') {
                        continue;
                    }

                    // Only show menu items if there are children
                    if (!empty($children)) {
                    ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link text-black" href="#" id="navbarDrop<?php echo $parentId; ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo $parentMenu->getMeNombre(); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDrop<?php echo $parentId; ?>">
                                <?php
                                foreach ($children as $child) {
                                ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo $child->getMeDescripcion(); ?>">
                                            <?php echo $child->getMeNombre(); ?>
                                        </a>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                        </li>
                <?php
                    }
                }

                // Handle Usuario menu separately for the special layout
                if (isset($menuHierarchy[4])) { // Assuming 4 is the ID for Usuario menu
                    $userMenu = $menuHierarchy[4];
                    ?>
                    <li class="nav-item">
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <?php
                            foreach ($userMenu['children'] as $child) {
                                if ($child->getMeNombre() === 'Carrito') {
                            ?>
                                    <div id="cart-toggle" class="btn btn-outline-dark">
                                        <i class="fas fa-shopping-cart mr-3" style="color: black;"></i>
                                    </div>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill">
                                        <?php
                                        echo ($sesion->getCarrito() == null) ? "0" : count($sesion->getCarrito());
                                        ?>
                                    </span>
                                <?php
                                }
                            }

                            if ($rolActivo->getIdRol() == 1) {
                                ?>
                                <a class="nav-link text-black btn btn-outline-dark" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="userDropdown">
                                    <li class="dropdown-item">
                                        Usuario: <?php echo $objUsuario->getUsNombre(); ?>
                                    </li>
                                    <li class="dropdown-item">
                                        Rol: <?php echo $rolActivo->getRolDescripcion(); ?>
                                    </li>
                                    <?php
                                    foreach ($userMenu['children'] as $child) {
                                        if ($child->getMeNombre() === 'Modificar Usuario') {
                                    ?>
                                            <li>
                                                <a class="dropdown-item" href="<?php echo $child->getMeDescripcion(); ?>">
                                                    <?php echo $child->getMeNombre(); ?>
                                                </a>
                                            </li>
                                    <?php
                                        }
                                    }
                                    ?>
                                </ul>
                                <?php
                            }

                            foreach ($userMenu['children'] as $child) {
                                if ($child->getMeNombre() === 'Cerrar Sesion') {
                                ?>
                                    <a href="<?php echo $child->getMeDescripcion(); ?>" class="btn btn-outline-dark">
                                        <i class="fas fa-sign-in-alt"></i>Log Out
                                    </a>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>

</nav>

<script src="../js/carrito.js"></script>

<body id="page-top">
<?php include_once '../ejercicios/asideCarrito.php'; ?>
