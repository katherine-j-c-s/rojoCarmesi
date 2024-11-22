<?php
include_once '../../configuracion.php';
error_reporting(0);
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
</head>
<nav class="navbar navbar-expand-lg bg-white navbar-ligth shadow-lg fixed-top">
  <div class="container">
    <a class="navbar-brand" href="../home/index.php">
      <img src="../assets/img/logos/logo2.png" alt="logo" width="50" height="50" />
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
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
            <a href="../ejercicios/crearUsuario.php" class="btn btn-outline-dark"><i class="fas fa-user-plus"></i> Crear Cuenta</a>
            <a href="../ejercicios/login.php" class="btn btn-outline-dark"><i class="fas fa-sign-in-alt"></i> Log In</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>

<body id="page-top">
