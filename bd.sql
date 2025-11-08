-- Crear base de datos
CREATE DATABASE IF NOT EXISTS xingxing_store CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE xingxing_store;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL
);

-- Tabla de productos
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  type VARCHAR(100),
  price DECIMAL(10,2) NOT NULL,
  stock INT NOT NULL,
  description TEXT
);

-- Insertar productos de ejemplo
INSERT INTO products (name, type, price, stock, description) VALUES
('Camiseta Blanca', 'Ropa', 199.99, 40, 'Camiseta básica 100% algodón'),
('Pantalón de Mezclilla', 'Ropa', 499.00, 25, 'Jeans azul corte regular'),
('Sudadera Negra', 'Ropa', 599.00, 30, 'Sudadera con capucha y bolsillo frontal'),
('Tenis Deportivos', 'Calzado', 849.50, 20, 'Tenis ligeros para correr'),
('Zapatos de Vestir', 'Calzado', 899.00, 15, 'Zapatos de piel color café'),
('Reloj Digital', 'Accesorios', 450.00, 18, 'Reloj resistente al agua con cronómetro'),
('Gorra Azul', 'Accesorios', 129.00, 50, 'Gorra ajustable de algodón'),
('Bolsa de Mano', 'Accesorios', 650.00, 12, 'Bolsa de piel sintética color negro'),
('Audífonos Bluetooth', 'Electrónica', 699.00, 22, 'Audífonos inalámbricos con micrófono'),
('Mouse Inalámbrico', 'Electrónica', 249.00, 35, 'Mouse óptico con conexión USB'),
('Teclado Mecánico', 'Electrónica', 999.00, 14, 'Teclado con retroiluminación RGB'),
('Monitor LED 24"', 'Electrónica', 2399.00, 10, 'Monitor Full HD 1920x1080'),
('Laptop 15.6"', 'Electrónica', 10500.00, 8, 'Laptop con procesador Intel i5 y 8GB RAM'),
('Smartphone 128GB', 'Electrónica', 7499.00, 10, 'Teléfono con cámara triple y pantalla AMOLED'),
('Cargador Rápido', 'Electrónica', 199.00, 45, 'Cargador USB-C con carga rápida'),
('Cable HDMI', 'Electrónica', 99.00, 60, 'Cable HDMI 2.0 de 1.5 metros'),
('Smartwatch', 'Electrónica', 1299.00, 18, 'Reloj inteligente con sensor de ritmo cardíaco'),
('Cafetera Automática', 'Hogar', 1299.00, 9, 'Cafetera programable con capacidad de 1.5L'),
('Licuadora 600W', 'Hogar', 899.00, 14, 'Licuadora con vaso de vidrio y 3 velocidades'),
('Tostadora', 'Hogar', 499.00, 20, 'Tostadora de pan con bandeja desmontable'),
('Ventilador de Piso', 'Hogar', 899.00, 13, 'Ventilador con 3 velocidades y oscilación'),
('Plancha de Vapor', 'Hogar', 649.00, 17, 'Plancha antiadherente con rociador de agua'),
('Sartén Antiadherente', 'Cocina', 299.00, 40, 'Sartén de aluminio 28 cm con recubrimiento antiadherente'),
('Juego de Cuchillos', 'Cocina', 499.00, 25, 'Set de 5 cuchillos con soporte de madera'),
('Taza de Cerámica', 'Cocina', 89.00, 60, 'Taza con diseño minimalista de 350ml'),
('Mochila Escolar', 'Escolar', 399.00, 30, 'Mochila resistente al agua con compartimentos múltiples'),
('Cuaderno Profesional', 'Escolar', 59.00, 80, 'Cuaderno de 100 hojas rayadas'),
('Bolígrafo Azul', 'Escolar', 12.00, 200, 'Bolígrafo de tinta azul retráctil'),
('Paquete de Colores', 'Escolar', 75.00, 60, 'Caja de 24 colores de madera'),
('Calculadora Científica', 'Escolar', 249.00, 25, 'Calculadora con funciones trigonométricas y estadísticas');
