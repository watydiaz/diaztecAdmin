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
