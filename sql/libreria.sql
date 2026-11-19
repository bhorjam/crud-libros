CREATE DATABASE IF NOT EXISTS libreria_crud
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE libreria_crud;
CREATE TABLE IF NOT EXISTS categorias (
  id       INT AUTO_INCREMENT PRIMARY KEY,
  nombre   VARCHAR(80) NOT NULL
) ENGINE=InnoDB;

INSERT INTO categorias (nombre) VALUES
  ('Literatura peruana'),
  ('Ciencia y tecnología'),
  ('Historia del Perú'),
  ('Autoayuda'),
  ('Infantil'),
  ('Novela'),
  ('Texto escolar');
CREATE TABLE IF NOT EXISTS libros (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  titulo        VARCHAR(150) NOT NULL,
  autor         VARCHAR(100) NOT NULL,
  editorial     VARCHAR(100) NOT NULL,
  categoria_id  INT          NOT NULL,
  precio        DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  stock         INT          NOT NULL DEFAULT 0,
  anio          YEAR         NOT NULL,
  estado        ENUM('disponible','agotado','reservado') NOT NULL DEFAULT 'disponible',
  created_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (categoria_id) REFERENCES categorias(id)
) ENGINE=InnoDB;

INSERT INTO libros (titulo, autor, editorial, categoria_id, precio, stock, anio, estado) VALUES
  ('Los ríos profundos',      'José María Arguedas', 'Losada',         1,  38.90, 12, 1958, 'disponible'),
  ('La ciudad y los perros',  'Mario Vargas Llosa',  'Seix Barral',    6,  52.50,  8, 1963, 'disponible'),
  ('Historia del Tahuantinsuyo','María Rostworowski','IEP',            3,  45.00,  5, 1988, 'disponible'),
  ('El alquimista',           'Paulo Coelho',        'Planeta',        4,  35.00,  0, 1988, 'agotado'),
  ('Matemática 5to secundaria','Editorial Santillana','Santillana',    7,  28.00, 20, 2023, 'disponible'),
  ('Cien años de soledad',    'Gabriel García Márquez','Sudamericana', 6,  49.90,  3, 1967, 'reservado');
