<?php header('Content-Type: text/html; charset=utf-8');
header ("Cache-Control: no-cache, must-revalidate ");

/////////////////////////////
// CONFIGURACION APP//
/////////////////////////////
//PARA GABY 
// $PROYECTO ='TPFINALPWD/rojoCarmesi/';
$PROYECTO ='TPFINALPWD/rojoCarmesi/'; //variable que almacena el nombre del proyecto

//variable que almacena el directorio del proyecto
$ROOT =$_SERVER['DOCUMENT_ROOT']."/$PROYECTO";

include_once($ROOT.'utiles/funciones.php');


// Variable que define la pagina de autenticacion del proyecto
$INICIO = "Location:http://".$_SERVER['HTTP_HOST']."/$PROYECTO/vista/login/login.php";

// variable que define la pagina principal del proyecto (menu principal)
$PRINCIPAL = "Location:http://".$_SERVER['HTTP_HOST']."/$PROYECTO/principal.php";


$GLOBALS['ROOT']=$ROOT;
$GLOBALS['IMAGENES'] = $ROOT . 'vista/assets/img/imagenesProductos/';
$GLOBALS['ACCIONES'] =$ROOT.'vista/ejercicios/';


?>