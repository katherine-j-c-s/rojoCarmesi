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
('Cerrar Sesion', '../accion/cerrarSesion.php', 4, NULL);



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
INSERT INTO menurol(idmenu, idRol) VALUES
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
(16, 3);
