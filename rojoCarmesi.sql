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
INSERT INTO `menu` ( `menombre`, `medescripcion`, `idpadre`, `medeshabilitado`) VALUES
( 'Administrador', 'padre', NULL, '2021-11-22 13:26:04'),
( 'Deposito', 'padre', NULL, '2021-11-22 13:26:10'),
( 'Cliente', 'padre', NULL, '2021-11-22 13:26:15'),
( 'Administrar Productos', '../ejercicios/listarProductos.php', 2, NULL),
( 'Administrar Compras', '../ejercicios/administrarCompras.php', 3, NULL),
( 'Editar Menu', '../ejericicos/menu_list.php', 1, NULL),
( 'Editar Usuarios', '../ejercicios/listarUsuarios.php', 1, NULL),
( 'carrito', '../ejercicios/carrito.php', 3, NULL),
( 'Crear Producto', '../ejercicios/crearProducto.php', 2, '2021-11-22 13:09:36'),
( 'Editar Productos', '../ejericicios/editarProducto.php', 3, '2021-11-22 13:09:48'),
( 'Nuestro Productos', '../ejercicios/mostrarProductos.php', 3, NULL),
( 'Mis Compras', '../ejercicios/comprasUsuario.php', 3, NULL);




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
INSERT INTO `menurol` (`idmenu`, `idRol`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 2),
(5, 2),
(6, 1),
(7, 1),
(8, 3),
(9, 3);





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
( 'admin', 'd396d55189db35d2cddc82ba7742b129', 'admin@cristalshop.com.ar', NULL),
('Cliente', 'd396d55189db35d2cddc82ba7742b129', 'cliente@cliente.com.ar', NULL),
( 'Deposito', 'd396d55189db35d2cddc82ba7742b129', 'desposito@clistalshop.com.ar', NULL),
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

