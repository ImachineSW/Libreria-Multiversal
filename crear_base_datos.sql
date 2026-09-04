CREATE DATABASE IF NOT EXISTS Multiversal;
USE Multiversal;

CREATE TABLE libros (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  precio DECIMAL(10,2) NOT NULL,
  autor VARCHAR(150) NOT NULL,
  formato VARCHAR(50),
  imagen VARCHAR(255),
  genero VARCHAR(100),
  editorial VARCHAR(150),
  `año` INT,
  idioma VARCHAR(50),
  sinopsis TEXT
);

INSERT INTO libros (id, nombre, precio, autor, formato, imagen, genero, editorial, `año`, idioma, sinopsis) VALUES
(1, 'El Héroe Perdido (Volumen 1)', 875, 'Rick Riordan', 'Físico', './img/Libros/elheroeperdido.jpg', 'Fantasía', 'Montena', 2025, 'Español', 'Tras ser atacados por espíritus de la tormenta, los tres son llevados al Campamento Mestizo donde descubren que son semidioses: Jason es hijo de Zeus, Piper de Afrodita y Leo de Hefesto. Su destino es cumplir una profecía que exige que Leo y Piper liberen a la diosa Hera de su prisión antes del solsticio de invierno, para impedir que los gigantes despierten a la diosa Gaia.'),
(2, 'El Arte de la Guerra', 723, 'Sun Tzu', 'Fisico', './img/Libros/elartedelaguerra.webp', 'Filosofía', 'Penguin Random House', 2020, 'Español', 'Tratado de estrategia militar y táctica, considerado uno de los textos más antiguos y reconocidos en la historia de la guerra.'),
(3, 'Casa de Hojas', 877, 'Mark Z. Danielewski', 'Físico', './img/Libros/casadehojas.jpg', 'Terror', 'Duomo Ediciones', 2024, 'Español', 'Will descubre que la vivienda presenta una imposibilidad física: su espacio interior es ligeramente mayor que sus dimensiones exteriores, un misterio que se agrava con la aparición de un pasillo oscuro e interminable que crece exponencialmente.'),
(4, 'Into the Pit', 402, 'Scott Cawthon', 'Fisico', './img/Libros/intothepit.jpg', 'Terror', 'Kobold Publishing', 2023, 'Español', 'Un thriller de terror psicológico que explora los límites de la realidad y el miedo.'),
(5, 'Voces Anonimas', 490, 'Guillermo Lockhart', 'Fisico', './img/Libros/vocesanonimas.webp', 'Ficción Literaria', 'Editorial Planeta', 2022, 'Español', 'Una obra maestra del realismo mágico latinoamericano que combina elementos de fantasía y realidad.'),
(6, 'Heartstopper', 850, 'Alice Oseman', 'Fisico', './img/Libros/heartstopper.webp', 'Ficción Literaria', 'Kobold Publishing', 2023, 'Español', 'Una historia de amor y amistad que explora temas de identidad y aceptación.'),
(7, 'The Lord of the Rings', 670, 'J. R. R. Tolkien', 'Fisico', './img/Libros/lordofthering.webp', 'Fantasía', 'Allen & Unwin', 1954, 'Inglés', 'Una épica obra de fantasía que sigue el viaje de Frodo Baggins para destruir el Anillo Único.');