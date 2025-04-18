CREATE DATABASE IF NOT EXISTS reparaciones_taller;
USE reparaciones_taller;

-- 1. Roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL
);

-- 2. Permisos
CREATE TABLE permisos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    descripcion TEXT
);

-- 3. Relación roles-permisos
CREATE TABLE rol_permiso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rol_id INT,
    permiso_id INT,
    FOREIGN KEY (rol_id) REFERENCES roles(id),
    FOREIGN KEY (permiso_id) REFERENCES permisos(id)
);

-- 4. Usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol_id INT,
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

-- 5. Clientes
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    identificacion VARCHAR(20) UNIQUE,
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 6. Productos / Inventario
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    marca VARCHAR(50),
    modelo VARCHAR(50),
    precio_compra DECIMAL(10,2) NOT NULL,
    precio_venta DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0
);

-- 7. Órdenes de reparación
CREATE TABLE ordenes_reparacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    usuario_tecnico_id INT,
    marca VARCHAR(100),
    modelo VARCHAR(100),
    imei_serial VARCHAR(100),
    falla_reportada TEXT,
    diagnostico TEXT,
    estado ENUM('pendiente','en_proceso','terminado','entregado') DEFAULT 'pendiente',
    prioridad ENUM('baja','media','alta') DEFAULT 'media',
    contraseña_equipo VARCHAR(100),
    imagen_url TEXT,
    fecha_ingreso DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_entrega_estimada DATETIME,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (usuario_tecnico_id) REFERENCES usuarios(id)
);

-- 8. Remisiones (venta o reparación)
CREATE TABLE remisiones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    orden_id INT NULL, -- si es por reparación, se puede vincular
    tipo ENUM('venta','reparacion') NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    usuario_id INT, -- quien generó la remisión
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (orden_id) REFERENCES ordenes_reparacion(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- 9. Detalle de remisión (productos o servicios incluidos)
CREATE TABLE remision_detalle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    remision_id INT,
    producto_id INT NULL, -- si es producto
    descripcion TEXT, -- si es servicio
    cantidad INT DEFAULT 1,
    precio_unitario DECIMAL(10,2),
    total DECIMAL(10,2),
    FOREIGN KEY (remision_id) REFERENCES remisiones(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- 10. Pagos (abonos o pagos totales a reparaciones o ventas)
CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    remision_id INT,
    monto DECIMAL(10,2) NOT NULL,
    metodo_pago VARCHAR(50), -- efectivo, nequi, daviplata, etc
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (remision_id) REFERENCES remisiones(id)
);

-- Insertar datos en la tabla roles
INSERT INTO roles (nombre) VALUES ('Administrador'), ('Técnico'), ('Cliente');

-- Insertar datos en la tabla permisos
INSERT INTO permisos (nombre, descripcion) VALUES 
('Gestionar usuarios', 'Permite gestionar usuarios del sistema'),
('Gestionar inventario', 'Permite gestionar productos en el inventario'),
('Gestionar órdenes', 'Permite gestionar órdenes de reparación');

-- Insertar datos en la tabla rol_permiso
INSERT INTO rol_permiso (rol_id, permiso_id) VALUES 
(1, 1), (1, 2), (1, 3), -- Administrador tiene todos los permisos
(2, 3); -- Técnico solo puede gestionar órdenes

-- Insertar datos en la tabla usuarios
INSERT INTO usuarios (nombre, email, password, rol_id) VALUES 
('Admin', 'admin@taller.com', 'admin123', 1),
('Juan Pérez', 'tecnico@taller.com', 'tecnico123', 2),
('Cliente Prueba', 'cliente@taller.com', 'cliente123', 3);

-- Insertar datos en la tabla clientes
INSERT INTO clientes (nombre, identificacion, telefono, email, direccion) VALUES 
('Carlos López', 'CC123456', '123456789', 'carlos@correo.com', 'Calle 123'),
('Ana Gómez', 'CC654321', '987654321', 'ana@correo.com', 'Avenida 456');

-- Insertar datos en la tabla productos
INSERT INTO productos (nombre, marca, modelo, precio_compra, precio_venta, stock) VALUES 
('Pantalla LCD', 'Samsung', 'Galaxy S10', 50.00, 100.00, 10),
('Batería', 'Apple', 'iPhone 11', 30.00, 70.00, 5);

-- Insertar datos en la tabla ordenes_reparacion
INSERT INTO ordenes_reparacion (cliente_id, usuario_tecnico_id, marca, modelo, imei_serial, falla_reportada, diagnostico, estado, prioridad, contraseña_equipo) VALUES 
(1, 2, 'Samsung', 'Galaxy S10', '1234567890', 'Pantalla rota', 'Reemplazo de pantalla', 'en_proceso', 'alta', '1234'),
(2, 2, 'Apple', 'iPhone 11', '0987654321', 'Batería no carga', 'Reemplazo de batería', 'pendiente', 'media', '5678');

-- Insertar datos en la tabla remisiones
INSERT INTO remisiones (cliente_id, orden_id, tipo, usuario_id) VALUES 
(1, 1, 'reparacion', 2),
(2, NULL, 'venta', 1);

-- Insertar datos en la tabla remision_detalle
INSERT INTO remision_detalle (remision_id, producto_id, descripcion, cantidad, precio_unitario, total) VALUES 
(1, NULL, 'Reparación de pantalla', 1, 100.00, 100.00),
(2, 1, NULL, 2, 100.00, 200.00);

-- Insertar datos en la tabla pagos
INSERT INTO pagos (remision_id, monto, metodo_pago) VALUES 
(1, 100.00, 'efectivo'),
(2, 200.00, 'nequi');

-- Consultas para verificar los resultados
SELECT * FROM roles;
SELECT * FROM permisos;
SELECT * FROM rol_permiso;
SELECT * FROM usuarios;
SELECT * FROM clientes;
SELECT * FROM productos;
SELECT * FROM ordenes_reparacion;
SELECT * FROM remisiones;
SELECT * FROM remision_detalle;
SELECT * FROM pagos;


