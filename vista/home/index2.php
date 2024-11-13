<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rojo Carmesí</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <?php
        include_once '../../configuracion.php';
        $sesion = new session;
        $objUsuario = $sesion->getObjUsuario();
        if($sesion->activa()){
            include_once '../estructura/cabeceraSegura.php'; 
            
        }else{
            include_once '../estructura/cabecera.php'; 
        }
    ?>
    <header class="position-relative d-flex justify-content-center align-items-center vh-100 overflow-hidden">
            <img src="../assets/img/fondoInicio.png" alt="Fondo" class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover; z-index: -1;">
            <div class="container">
                <a type="button" class="btn btn-primary btn-xl text-uppercase">conoce nuestros productos</a>
            </div>
    </header>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>