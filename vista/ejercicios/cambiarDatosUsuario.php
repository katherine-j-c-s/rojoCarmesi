<?php
include_once '../../configuracion.php';
include_once '../estructura/cabeceraSegura.php';
include_once '../../utiles/verificador.php';

$sesion = new session();
$paginaActual = $_SERVER['PHP_SELF'];

// Verificar permiso
$resultado = Verificador::verificarPermiso($paginaActual, $sesion);

if ($sesion->activa()) {
    include_once '../estructura/cabeceraSegura.php';
} else {
    header('Location: ./login.php');
}

if (isset($_GET['Message'])) {
    print '<script type="text/javascript">alert("' . $_GET['Message'] . '");</script>';
}
if (!$resultado['permiso']) {
    $mensaje = $resultado['mensaje'];
    echo "</br></br></br></br></br></br>";
    echo "<h4 class='alert alert-danger'>$mensaje</h4>";
}else{
?>

<div class="container p-5 mt-5">
    <div class="p-3 mt-3 m-auto bg-secondary row" style="width: 390px; height:390px; overflow:hidden">
        <div class="card m-auto row  justify-content-center align-items-center shadow-lg rounded position-relative" style="width: 360px; height:360px;">
            <div class="col-12">
                <div class="row mb-4 text-center">
                    <h3 class="">Modificacion de Usuario</h3>
                </div>
                <div class="row card">
                    <form action='../accion/accionCambiarDatosUsuario.php' method="post" id="cambiarUsuarioFormulario" noovalidate>
                            <!-- PASSWORD VIEJO DEL USUARIO -->
                            <div class="row mb-3 form-group">
                                <div class="input-group mb-2 m-auto">
                                    <span class="input-group-text" id="iconPassword" style="background-color: #fff; border-right: none">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" name="usPass" id="usPass" placeholder="Antigua Contraseña" aria-label="Password" aria-describedby="iconPassword" style="border-left: none" />
                                    <div class="valid-feedback">Esta correcto!</div>
                                    <div class="invalid-feedback">Este campo no puede estar vacio!</div>
                                </div>
                                <!-- Validacion -->
                                <div class="invalid-feedback">
                                </div>
                            </div>
                            <!-- PASSWORD NUEVO DEL USUARIO -->
                            <div class="row mb-3 form-group">
                                <div class="input-group mb-2 m-auto">
                                    <span class="input-group-text" id="iconPassword" style="background-color: #fff; border-right: none">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" name="usPassNuevo" id="usPassNuevo" placeholder="Nueva Contraseña" aria-label="Password" aria-describedby="iconPassword" style="border-left: none" />
                                    <div class="valid-feedback">Esta correcto!</div>
                                    <div class="invalid-feedback">Este campo no puede estar vacio!</div>
                                </div>
                                <!-- Validacion -->
                                <div class="invalid-feedback">
                                </div>
                            </div>
                            <!-- MAIL DEL USUARIO -->
                            <div class="row mb-3 form-group">
                            <div class="input-group mb-2 m-auto">
                                <span class="input-group-text" id="icon" style="background-color: #fff; border-right: none">
                                    <i class="fas fa-user-alt"></i>
                                </span>
                                <input type="text" class="form-control" name="usMail" id="usMail" placeholder="Email" aria-label="usMail" aria-describedby="icon" style="border-left: none" />
                                <div class="valid-feedback">Esta correcto!</div>
                                <div class="invalid-feedback">Este campo no puede estar vacio!</div>
                            </div>
                            <!-- Validacion -->
                            <div id="invalido">
                            </div>
                        </div>
                        <div class="m-auto">
                            <input type="submit" class="btn btn-primary col-12" id="submitButton" value="Aceptar Cambios" style="background-color:#00ce81; border:none" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../js/validacionCambiarUsuario.js" ></script>

<?php
}
    include_once '../estructura/footer.php';
?>