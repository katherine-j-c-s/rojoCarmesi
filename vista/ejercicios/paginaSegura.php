<?php
include_once '../../configuracion.php';
$sesion = new session();

if ($sesion->activa()) {
    include_once '../estructura/cabeceraSegura.php';
    
    $datos = data_submitted();
    $objUsuario = $sesion->getObjUsuario();
    $objRolActivo = $sesion->getRolActivo();

    if (isset($_GET['Message'])) {
        print '<script type="text/javascript">alert("' . $_GET['Message'] . '");</script>';
    }
    echo "</br></br></br></br></br></br>";
    
    echo "<h4 class='alert alert-success'>Bienvenido:  {$objUsuario->getUsNombre()} <br>
     Su Rol Actual: {$sesion->getRolActivo()->getRolDescripcion()}</h4>";
}else{
    echo "no hay sesion activa";
    header('Location: login.php');
    
}

?>
<?php
include_once '../estructura/footer.php';
?>