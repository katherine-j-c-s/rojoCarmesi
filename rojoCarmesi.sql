----------Base Datos Rojo carmesi ------------------


-- Crear la base de datos
CREATE DATABASE rojoCarmesi;

-- Tabla compra
CREATE TABLE compra (
    idCompra BIGINT(20) NOT NULL AUTO_INCREMENT,
    compraFecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    idUsuario BIGINT(20) NOT NULL,
    PRIMARY KEY (idcompra)
);

-- Tabla compraestado
CREATE TABLE compraestado (
    idCompraEstado BIGINT(20) NOT NULL AUTO_INCREMENT,
    idCompra BIGINT(11) NOT NULL,
    idCompraEstadoTipo INT(11) NOT NULL,
    compraEstadoFechaInicial TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    compraEstadoFechaFinal TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (idCompraEstado),
    FOREIGN KEY (idCompra) REFERENCES compra(idCompra),
    FOREIGN KEY (idCompraEstadoTipo) REFERENCES compraestadotipo(idCompraEstadoTipo)
);

-- Tabla compraestadotipo
CREATE TABLE compraestadotipo (
    idCompraEstadoTipo INT(11) NOT NULL AUTO_INCREMENT,
    compraEstadoTipoDescripcion VARCHAR(50) NOT NULL,
    compraEstadoTipoDetalle  VARCHAR(256) NOT NULL,
    PRIMARY KEY (idCompraEstadoTipo)
);

--
-- Volcado de datos para la tabla `compraestadotipo`
--
INSERT INTO `compraestadotipo` ( `compraEstadoTipoDescripcion`, `compraEstadoTipoDetalle`) VALUES
( 'iniciada', 'cuando el usuario : cliente inicia la compra de uno o mas productos del carrito'),
( 'aceptada', 'cuando el usuario administrador da ingreso a uno de las compras en estado = 1 '),
( 'enviada', 'cuando el usuario administrador envia a uno de las compras en estado =2 '),
( 'cancelada', 'un usuario administrador podra cancelar una compra en cualquier estado y un usuario cliente solo en estado=1 ');


-- Tabla compraitem
CREATE TABLE compraitem (
    idCompraItem BIGINT(20) NOT NULL AUTO_INCREMENT,
    idProducto BIGINT(20) NOT NULL,
    idCompra BIGINT(20) NOT NULL,
    compraItemCantidad INT(11) NOT NULL,
    PRIMARY KEY (idCompraItem),
    FOREIGN KEY (idCompra) REFERENCES compra(idCompra),
    FOREIGN KEY (idProducto) REFERENCES producto(idProducto)
);
	
-- Tabla rol
CREATE TABLE rol (
    idRol BIGINT(20) NOT NULL AUTO_INCREMENT,
    roDescripcion VARCHAR(50) NOT NULL,
    PRIMARY KEY (idRol)
);

----
-- Volcado de datos para la tabla `rol`
-----

INSERT INTO `rol` (`idRol`, `rolDescripcion`) VALUES
(1, 'Admin'),
(2, 'Deposito'),
(3, 'Cliente');

-- Tabla menu
CREATE TABLE menu (
    idmenu BIGINT(20) NOT NULL AUTO_INCREMENT,
    menombre VARCHAR(50) NOT NULL,
    medescripcion VARCHAR(124) NOT NULL,
    idpadre BIGINT(20) DEFAULT NULL,
    medeshabilitado TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (idmenu),
    FOREIGN KEY (idpadre) REFERENCES menu(idmenu)
);



--
-- Volcado de datos para la tabla `menu`
--
INSERT INTO menu (menombre, medescripcion, idpadre, medeshabilitado) VALUES
('Navegacion', 'padre', NULL, NULL),
('Productos', 'padre', NULL, NULL),
('Administracion', 'padre', NULL, NULL),
('Usuario', 'padre', NULL, NULL),
('Inicio Usuario', '../home/index.php#page-top', 1, NULL),
('Proximos Eventos', '../home/index.php#proximoseventos', 1, NULL),
('Quienes Somos', '../home/index.php#quienesSomos', 1, NULL),
('Contacto', '../home/index.php#contact', 1, NULL),
('Vista Productos', '../ejercicios/MostrarProductos.php', 2, NULL),
('Administrar Productos', '../ejercicios/listarProductos.php', 2, NULL),
('Crear Producto', '../ejercicios/crearProducto.php', 2, NULL),
('Editar Menu', '../ejercicios/listarMenu.php', 3, NULL),
('Agregar Permisos', '../ejercicios/listarUsuarios.php', 3, NULL),
('Carrito', '../ejercicios/carrito.php', 4, NULL),
('Modificar Usuario', '../ejercicios/cambiarDatosUsuario.php', 4, NULL),
('Cerrar Sesion', '../accion/cerrarSesion.php', 4, NULL),
('Compra','padre',NULL,NULL),
('Vista Compras', '../ejercicios/administrarCompras.php', 17, NULL);
('Permisos', 'padre', NULL, NULL),
('Editar Compra', '../ejercicios/editarEstadoCompra.php', 19, NULL),
('Editar Producto', '../ejercicios/editarProducto.php', 19, NULL),
('Editar Menu', '../ejercicios/editarMenu.php', 19, NULL),
('Editar Usuario', '../ejercicios/editarUsuario.php', 19, NULL);



-- Tabla menurol
CREATE TABLE menurol (
    idmenu BIGINT(20) NOT NULL,
    idRol BIGINT(20) NOT NULL,
    PRIMARY KEY (idmenu, idRol),
    FOREIGN KEY (idmenu) REFERENCES menu(idmenu),
    FOREIGN KEY (idRol) REFERENCES rol(idRol)
);


--
-- Volcado de datos para la tabla `menurol`
--
INSERT INTO menu_roles (idmenu, idRol) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(2, 3),
(3, 1),
(4, 1),
(4, 2),
(4, 3),
(5, 1),
(5, 2),
(5, 3),
(6, 1),
(6, 2),
(6, 3),
(7, 1),
(7, 2),
(7, 3),
(8, 1),
(8, 2),
(8, 3),
(9, 1),
(9, 2),
(9, 3),
(10, 2),
(11, 2),
(12, 1),
(13, 1),
(14, 1),
(14, 2),
(14, 3),
(15, 1),
(16, 1),
(16, 2),
(16, 3),
(17, 2),
(18, 2),
(19, 1),
(20, 2),
(21, 2),
(22, 1),
(23, 1);


-- Tabla producto
CREATE TABLE producto (
    idProducto BIGINT(20) NOT NULL AUTO_INCREMENT,
    productoNombre VARCHAR(50) NOT NULL,
    productoDetalle VARCHAR(512) NOT NULL,
    productostock INT(11) NOT NULL,
    productoPrecio INT NOT NULL,
    PRIMARY KEY (idProducto)
);

--
-- Volcado de datos para la tabla `producto`
--
INSERT INTO `producto` (`productoNombre`, `productoDetalle`,`productoStock`, `productoPrecio`) VALUES
('Perfume', 'Perfume Juana',200, 5000),
('Labial Rojo', 'Rojo carmesi',200, 2000),
('Aceite', 'Aceite natural de rosas',210, 2000),
('Esmalte', 'Esmalte nancy',100, 5000),
('kit biscus', 'kit biscus',200, 6000),
('Sombras', 'Sombras calidas',200, 6000),
('Oleos', 'Oleos Naturales',200, 5000);

-- Tabla usuario
CREATE TABLE usuario (
    idUsuario BIGINT(20) NOT NULL AUTO_INCREMENT,
    usNombre VARCHAR(50) NOT NULL,
    usPass VARCHAR(255) NOT NULL,
    usMail VARCHAR(50) NOT NULL,
    usDeshabilitado TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (idusuario)
);

--
-- Volcado de datos para la tabla `usuario`
--
INSERT INTO `usuario` (`usNombre`, `usPass`, `usMail`, `usDesabilitado`) VALUES
( 'admin', 'd396d55189db35d2cddc82ba7742b129', 'admin@rojoCarmesi.com.ar', NULL),
('Cliente', 'd396d55189db35d2cddc82ba7742b129', 'cliente@cliente.com.ar', NULL),
( 'Deposito', 'd396d55189db35d2cddc82ba7742b129', 'desposito@rojoCarmesi.com.ar', NULL),
;


-- Tabla usuariorol
CREATE TABLE usuariorol (
    idUsuario BIGINT(20) NOT NULL,
    idRol BIGINT(20) NOT NULL,
    PRIMARY KEY (idUsuario, idRol),
    FOREIGN KEY (idUsuario) REFERENCES usuario(idUsuario),
    FOREIGN KEY (idRol) REFERENCES rol(idRol)
);

--
-- Volcado de datos para la tabla `usuariorol`
--
INSERT INTO `usuariorol` (`idUsuario`, `idRol`) VALUES
(1, 1),
(2, 3),
(3, 2);

