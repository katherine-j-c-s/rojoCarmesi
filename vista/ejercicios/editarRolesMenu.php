<?php
include_once '../../configuracion.php';
$sesion = new session();
$objUsuario = $sesion->getObjUsuario();
$objRol = $sesion->getRolActivo();
$idRol = $objRol->getIdRol();
$datos = data_submitted();
$arrayRolesMenu = [];


echo "<br/><br/><br/><br/><br/><br/><br/>";
if ($sesion->activa()) {
   include_once '../estructura/cabeceraSegura.php';
}

// if ($idRol != 1) {
//    echo "</br></br></br></br></br></br>";
//    echo "<h4 class='alert alert-danger'>Usted no tiene Permisos para esta seccion</h4>";
// } else {
$descripcion = "";
$abmMenu = new abmMenu();
$listaMenu = $abmMenu->buscar(param: $datos);
$objMenu = $listaMenu[0];
$abmMenuRol = new abmMenuRol();
$listaMenuRol = $abmMenuRol->buscar(param:$datos["idmenu"]);

$abmRol = new abmRol();

$descripcioSelected = "";
foreach ($listaMenuRol as $menuRol) {
   $idMenuEnRol= $menuRol->getObjMenu()->getIdmenu();
   if($idMenuEnRol == $objMenu->getIdmenu()){
      $objRol = $menuRol->getObjRol();
      $descripcioSelected = $descripcioSelected .$objRol-> getRolDescripcion(). '</br>';
   }
}
// Lista de roles seleccionados desde $descripcioSelected
$roles = $abmRol->buscar(null); // Obtener todos los roles disponibles

// Lista de roles seleccionados desde $descripcioSelected
$descripcioSelectedArray = array_filter(explode('</br>', $descripcioSelected)); // Convertir en array, eliminando elementos vacíos

?>
<div class="container mt-5">
   <div class="card card-info">
      <h4>Datos Menu</h4>
      <form class="needs-validation" novalidate id="editarRolesMenu" name="editarRolesMenu" action="../accion/accionEditarRolesMenu.php" method="post">
         <div class="col-sm-5 m-2">
            <div class="mb-3">
               <label class="form-label">Nombre : <?php echo $objMenu->getMenombre(); ?></label>
            </div>
            <div class="mb-3">
               <label class="form-label">Redireccion : <?php echo $objMenu->getmedescripcion(); ?></label>
            </div>
            <div class="mb-3">
               <label class="form-label">Roles del usuario</label>
            </div>
            <div class="form-group">
               <?php foreach ($roles as $rol): 
                  // Obtener descripción del rol
                  $rolDescripcion = $rol->getRolDescripcion();
                  $idRol = $rol->getIdRol();
                  // Determinar si debe estar seleccionado
                  $isChecked = in_array($rolDescripcion, $descripcioSelectedArray);
               ?>
                  <div class="input-group">
                        <!-- Checkbox con el valor del rol y su descripción -->
                        <input class="form-check-input" hidden name="idMenu" value="<?php echo htmlspecialchars($datos["idmenu"]); ?>">
                        <input class="form-check-input" type="checkbox" id=<?php echo $idRol;?> name="roles[]" value="<?php echo htmlspecialchars($idRol); ?>" 
                              <?php echo $isChecked ? 'checked' : ''; ?>>
                        <label class="form-check-label ms-1 me-2 fw-light"><?php echo htmlspecialchars($rolDescripcion); ?></label>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
         <button type="submit" class="btn btn-primary">Guardar</button>
         <a class="btn btn-primary" href="./listarMenu.php">Volver</a>
      </form>
   </div>
</div>
<?php
// }
include_once '../estructura/footer.php';
?>