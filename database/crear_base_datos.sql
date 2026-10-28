CREATE DATABASE IF NOT EXISTS Multiversal;
USE Multiversal;

CREATE TABLE libros (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  precio DECIMAL(10,2) NOT NULL,
  autor VARCHAR(150) NOT NULL,
  paginas INT,
  imagen VARCHAR(255),
  editorial VARCHAR(150),
  fecha VARCHAR(50),
  idioma VARCHAR(50),
  sinopsis TEXT
);

INSERT INTO libros (id, nombre, precio, autor, paginas, imagen, editorial, fecha, idioma, sinopsis) VALUES
(1, 'El Héroe Perdido (Los Héroes del Olimpo 1)', 890, 'Rick Riordan', 496, 'los-heroes-del-olimpo-1-el-heroe-perdido.webp', 'Montena', '01-04-2015', 'Español', 'Cuando Jason despierta sabe que algo va muy mal. Está en un autobús camino de un campamento para chicos problemáticos. Le acompañan Piper -una muchacha (bastante guapa, por cierto) que dice que es su novia- y el que parece ser su mejor amigo: Leo.
 Pero él no recuerda nada: ni quién es ni cómo ha llegado allí. Pocas horas después, los tres descubrirán no solo que son hijos dedioses del Olimpo, sino además que su destino es cumplir una profecía de locos: liberar a Hera, diosa de la furia, de las garras de un enemigo que lleva mucho tiempo planeando su venganza...'),
(2, 'El Arte de la Guerra (Edición Ilustrada)', 422.99, 'Sun Tzu', 144, 'el-arte-de-la-guerra-edicion-ilustrada.jpg', 'Roca Editorial', '21-11-2024', 'Español', 'Venerado desde el siglo V a.C., El arte de la guerra es algo más que un clásico de la literatura universal: es un estratégico manual de vida en el que se han inspirado los grandes dirigentes, generales y políticos de todos los tiempos. Atemporales y sabias, las enseñanzas de Sun Tzu pueden aplicarse para triunfar en cualquier ámbito (política, negocios, relaciones personales, autodisciplina…).
 Traducida por primera vez del chino al español en sus más de dos mil años de historia, en estas páginas se encuentra la edición definitiva de una obra imprescindible que sigue hipnotizándonos generación tras generación, y que nos abre los ojos a conceptos que van mucho más allá de la guerra o la batalla: el liderazgo y la gestión de grupos humanos, el éxito y el coste de nuestros esfuerzos y los elementos que conforman la auténtica sabiduría de un buen líder.'),
(3, 'Casa de Hojas', 661.46, 'Mark Z. Danielewski', 736, 'casa-de-hojas.jpg', 'Alpha Decay', '11-11-2013', 'Español', 'Al regresar a casa una noche, los Navidson, Will, Karen y sus dos hijos, que acaban de mudarse a Virginia, observan que ha aparecido una nueva habitación, como si siempre hubiera estado allí. ¿Un simple descuido? ¿Un elaborado engaño? Will descubre que la casa presenta una anomalía singular: su interior parece ligeramente más grande de lo que debería ser según sus dimensiones exteriores. Comienza así El expediente Navidson, un fascinante documento que narra los sucesos acaecidos en la casa de Ash Tree Lane, la casa de hojas.'),
(4, 'Into The Pit (five Nights At Freddy''s: Fazbear Frights #1)', 480.66, 'Scott Cawthon', 224, 'fazbear-frights-1-into-the-pit.jpg', 'Scholastic Inc.', '26-12-2019', 'Inglés', 'What do you wish for most? It''s a question that Oswald, Sarah, and Millie think they know the answer to. Oswald wishes his summer wasn''t so boring, Sarah wishes to be beautiful, and Millie wishes she could just disappear from the face of the earth. But in the twisted world of Five Nights at Freddy''s, their hearts'' deepest desires have an unexpected cost.
 In this volume, Five Nights at Freddy''s creator Scott Cawthon spins three sinister novella-length stories from different corners of his series'' canon, featuring cover art from fan-favorite artist LadyFiszi. Listeners beware: This collection of terrifying tales is enough to unsettle even the most hardened Five Nights at Freddy''s fans.'),
(5, 'Voces Anónimas Tomo 1: Leyendas De Montevideo', 459, 'Guillermo Lockhart', 159, 'voces-anonimas-1-leyendas-de-montevideo.webp', 'Autoedición', '29-10-2022', 'Español', 'Montevideo es una ciudad de belleza gris y una carga histórica que se percibe en sus calles limpias y ordenadas. Sin embargo, bajo esta calma y monotonía, se oculta un universo de mitos y leyendas lleno de magia y credibilidad.
 Desde cuadros que cambian de posición durante la noche, hasta alquimistas que erigieron castillos fantásticos, desde un espectro femenino que deambula por un enorme y hermoso parque, hasta una isla habitada por fantasmas, Montevideo nos brinda una colección de historias sorprendentes y poco conocidas. La magia de la ciudad está escondida, sus tesoros no son evidentes a primera vista, y para apreciarlos completamente, se podría necesitar un guía que indique los lugares donde la realidad se transforma en un mundo de fábulas y leyendas.'),
(6, 'Heartstopper Vol. 1', 890, 'Alice Oseman', 288, 'heartstopper-1.webp', 'Graphix', '05-05-2020', 'Inglés', 'Boy meets boy. Boys become friends. Boys fall in love. A sweet and charming coming-of-age story that explores friendship, love, and coming out. This edition features beautiful two-color artwork. Now streaming on Netflix! Shy and softhearted Charlie Spring sits next to rugby player Nick Nelson in class one morning. A warm and intimate friendship follows, and that soon develops into something more for Charlie, who doesn''t think he has a chance. But Nick is struggling with feelings of his own, and as the two grow closer and take on the ups and downs of high school, they come to understand the surprising and delightful ways in which love works.'),
(7, 'The Lord of the Rings: The Fellowship of the Ring', 670, 'J. R. R. Tolkien', 448, 'the-lord-of-the-rings-1-the-fellowship-of-the-ring.webp', 'HARPER & COLLINS ENGLAND', '29-07-1954', 'Inglés', 'In a sleepy village in the Shire, a young hobbit is entrusted with an immense task. He must make a perilous journey across Middle-earth to the Cracks of Doom, there to destroy the Ruling Ring of Power – the only thing that prevents the Dark Lord Sauron’s evil dominion. Thus begins J. R. R. Tolkien’s classic tale of adventure, which continues in The Two Towers and The Return of the King.'),
(8, 'Odisea', 1130, 'Homero', 520, 'odisea.jpg', 'Alianza Editorial', '02-07-2026', 'Español', 'Una travesía que forja héroes desafía a los dioses y revela lo más profundo del alma humana. Tras la caída de Troya, Odiseo (o Ulises), rey de Ítaca, emprende un viaje que ningún mortal desearía y que solo un espíritu indomable podría soportar. Monstruos que acechan en la oscuridad, islas donde el tiempo se detiene, el canto hipnótico de las Sirenas, tentaciones capaces de quebrar la voluntad más firme y la furia caprichosa de los dioses convierten su regreso en una lucha contra lo imposible. Pero esta no es solo la historia de un guerrero astuto. Es el relato de un hombre que, perdido entre mares desconocidos, se enfrenta a su propia fragilidad mientras su hogar, su reino y su familia lo esperan sin certezas.'),
(9, 'Drácula', 1710, 'Bram Stoker', 488, 'dracula.webp', 'Alma Editorial', '31-05-2018', 'Español', 'La historia comienza cuando el joven abogado Jonathan Harker viaja a Transilvania para cerrar un negocio inmobiliario con el Conde Drácula. Pronto descubre que su anfitrión es un vampiro ancestral que planea mudarse a Londres. Harker queda prisionero en el castillo mientras Drácula parte hacia Inglaterra.
 En Londres, Drácula comienza a aterrorizar a la población, atacando especialmente a Lucy Westenra, quien se convierte en vampiro tras múltiples transfusiones fallidas. Su transformación alarma a sus pretendientes y al Dr. Van Helsing, experto en lo sobrenatural, quien lidera la lucha contra el vampiro.
 El grupo, formado por Van Helsing, Arthur Holmwood, Quincey Morris, Dr. Seward y posteriormente Harker y su esposa Mina, descubre que Drácula también acecha a Mina. Utilizando métodos científicos y conocimientos ocultistas, rastrean al vampiro hasta su guarida. La novela culmina con una persecución épica de regreso a Transilvania, donde logran destruir a Drácula justo al atardecer, liberando su alma condenada.'),
(10, 'The Hunger Games (Book 1)', 729, 'Suzanne Collins', 478, 'the-hunger-games-1.jpg', 'Blackstone Publishing Inc', '19-12-2023', 'Inglés', 'In the ruins of a place once known as North America lies the nation of Panem, a shining Capitol surrounded by twelve outlying districts. The Capitol is harsh and cruel and keeps the districts in line by forcing them all to send one boy and one girl between the ages of twelve and eighteen to participate in the annual Hunger Games, a fight to the death on live TV. Sixteen-year-old Katniss Everdeen regards it as a death sentence when she steps forward to take her sister''s place in the Games. But Katniss has been close to dead before--and survival, for her, is second nature. Without really meaning to, she becomes a contender. But if she is to win, she will have to start making choices that weigh survival against humanity and life against love.'),
(11, 'The King in Yellow', 864, 'Robert W. Chambers', 276, 'the-king-in-yellow.webp', 'Heathen Editions', '15-12-2017', 'Inglés', 'Robert William Chambers (1865-1933) was an American illustrator and writer, best known for The King in Yellow, his influential and odd collection of ten macabre and French short stories first published in 1895. The title refers to a fictional play featured in four of the stories, and to a mysterious and malevolent supernatural entity within that play who may very well exist outside of it. It is whispered that the play leaves only insanity and sorrow in its wake; it tempts those who read it, bringing upon them hallucinations and madness.'),
(12, 'Die Verwandlung', 250, 'Franz Kafka', 53, 'die-verwandlung.jpg', 'Hamburger Lesehefte', '01-01-1995', 'Alemán', 'Kafka beendete die Niederschrift dieser "kleinen Geschichte", die er auch selbst einmal als "ausnehmend ekelhafte Geschichte" bezeichnete, in der Nacht vom 6. Dezember zum 7. Dezember 1912. Er beschreibt hier minutiös die Folgen der Verwandlung des Handelsreisenden Gregor Samsa in eine Tiergestalt und somit die unterschiedlichsten Elemente der Gegenwelt des Menschlichen. Im Jahr 1914 von der "Neuen Rundschau" abgelehnt, erschien diese "Wanzengeschichte" - so vom Verleger Kurt Wolff genannt - erstmals 1915 in der Oktoberausgabe der "Weissen Blätter" und im selben Jahr als Einzeldruck für den "Jüngsten Tag". Heute ist sie als Schullektüre aus dem Deutschunterricht nicht mehr fortzudenken. Im Nachwort dieses Heftes befinden sich Hinweise zu Leben und Werk Kafkas sowie sprachliche Erläuterungen.'),
(13, 'Voces Anónimas Tomo 2: Leyendas Del Interior De Uruguay', 473, 'Guillermo Lockhart', 157, 'voces-anonimas-2-leyendas-del-interior-de-uruguay.webp', 'Gussi', '31-05-2023', 'Español', 'El pueblo del interior tiene un encanto especial. Y es aquí donde, de nuevo, intervienen las Voces Anónimas. Su creador, Guillermo Lockhart, hace de ese abuelito que sabe y nos espanta con historias de la luz mala, del inoxidable lobisón, de hamacas que se mueven solas, de escuelas con fantasmas obsesivos en su interior... Claro que Guillermo no es tan viejo, de hecho es mucho más joven. Pero tiene la sabiduría de un anciano que ha recorrido mil caminos y visto diez mil prodigios. Sabe cómo contar una historia, cómo crear el clima y el suspenso adecuado, y hacerte sentir pequeño de nuevo. Un niño de pueblo asustado frente a las historias de fantasmas que cuentan sus mayores, mirando a cada momento por la ventana, esperando ver algún rostro, alguna entidad conjurada durante las aburridas tardes de siesta. En los pueblos hay más historias de las que solemos creer.'),
(14, 'Cien Años de Soledad', 630, 'Gabriel García Márquez', 512, 'cien-años-de-soledad.jpg', 'Debolsillo', '01-01-2001', 'Español', '«Muchos años después, frente al pelotón de fusilamiento, el coronel Aureliano Buendía había de recordar aquella tarde remota en que su padre lo llevó a conocer el hielo. Macondo era entonces una aldea de veinte casas de barro y cañabrava construidas a la orilla de un río de aguas diáfanas que se precipitaban por un lecho de piedras pulidas, blancas y enormes como huevos prehistóricos. El mundo era tan reciente, que muchas cosas carecían de nombre, y para mencionarlas había que señalarlas con el dedo».
 Con estas palabras empieza la novela ya legendaria en los anales de la literatura universal, una de las aventuras literarias más fascinantes de nuestro siglo. Millones de ejemplares de Cien años de soledad leídos en todas las lenguas y el Premio Nobel de Literatura coronando una obra que se había abierto paso «boca a boca» -como gusta decir al escritor- son la más palpable demostración de que la aventura fabulosa de la familia Buendía-Iguarán, con sus milagros, fantasías, obsesiones, tragedias, incestos, adulterios, rebeldías, descubrimientos y condenas, representaba al mismo tiempo el mito y la historia, la tragedia y el amor del mundo entero.'),
(15, 'Le Petit Prince', 1578.50, 'Antoine de Saint-Exupery', 120, 'le-petit-prince.jpg', 'Editions Gallimard', '06-04-1946', 'Francés', 'Le Petit Prince est paru pour la première fois il y a cinquante ans aux Etats-Unis. Quelques mois plus tard, son auteur, Antoine de Saint-Exupéry, disparaissait avec son avion en Méditerranée au cours d''une mission de guerre. " J''aurais l''air d''être mort et ce ne sera pas vrai ", nous avait prédit l''enfant blond. Pour tous ceux qui aiment ses livres, Antoine de Saint-Exupéry n''est pas mort et " Le petit Prince " est plus vivant que jamais avec ses millions d''exemplaires et ses quelque quatre-vingts traductions. Voici donc un album de fête pour cette double commémoration : un album enrichi de la fabuleuse histoire de la naissance du " Petit Prince ". Car tout est fabuleux dans le monde du Petit Prince : aux Etats-Unis, on a retrouvé quelques dessins originaux qu''Antoine de Saint-Exupéry a préféré écarter au moment de remettre son manuscrit à l''éditeur. Ils sont pour la première fois publiés ici. Ils donnent au livre une coloration inattendue, qui en accentue la portée symbolique.');

ALTER TABLE libros
ADD COLUMN stock INT NOT NULL DEFAULT 0,
ADD COLUMN stock_minimo INT NOT NULL DEFAULT 5,
ADD COLUMN activo TINYINT(1) NOT NULL DEFAULT 1;

-- Datos de prueba: los libros arrancan con stock 0 (no se pueden comprar), se les da stock para poder probar
UPDATE libros SET stock = 20 WHERE stock = 0;

CREATE TABLE generos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE libros_generos (
    libro_id INT NOT NULL,
    genero_id INT NOT NULL,

    PRIMARY KEY (libro_id, genero_id),

    FOREIGN KEY (libro_id)
        REFERENCES libros(id)
        ON DELETE CASCADE,

    FOREIGN KEY (genero_id)
        REFERENCES generos(id)
        ON DELETE CASCADE
);

INSERT INTO generos (id, nombre) VALUES
(1, 'Fantasía'),
(2, 'Aventura'),
(3, 'Estrategia'),
(4, 'Filosofía'),
(5, 'Terror'),
(6, 'Misterio'),
(7, 'Ciencia ficción'),
(8, 'Folclore'),
(9, 'Romance'),
(10, 'Drama'),
(11, 'Épica'),
(12, 'Gótico'),
(13, 'Distopía'),
(14, 'Ficción literaria'),
(15, 'Fantástico'),
(16, 'Realismo mágico'),
(17, 'Fábula');



INSERT INTO libros_generos (libro_id, genero_id) VALUES
(1, 1),
(1, 2),
(1, 11),
(2, 3),
(2, 4),
(3, 5),
(3, 6),
(3, 12),
(3, 14),
(4, 5),
(4, 6),
(4, 15),
(5, 5),
(5, 6),
(5, 8),
(5, 15),
(6, 9),
(6, 10),
(6, 14),
(7, 1),
(7, 2),
(7, 11),
(8, 2),
(8, 11),
(8, 15),
(9, 5),
(9, 6),
(9, 12),
(9, 15),
(10, 2),
(10, 7),
(10, 13),
(10, 9),
(11, 5),
(11, 6),
(11, 12),
(11, 15),
(12, 10),
(12, 14),
(12, 15),
(13, 5),
(13, 6),
(13, 8),
(13, 15),
(14, 10),
(14, 14),
(14, 16),
(15, 1),
(15, 2),
(15, 16),
(15, 17);



CREATE TABLE IF NOT EXISTS usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('cliente', 'admin', 'empleado') NOT NULL DEFAULT 'cliente'
);

ALTER TABLE usuario
ADD COLUMN activo TINYINT(1) NOT NULL DEFAULT 1;

-- Pedidos: un pedido por cada compra, con su dirección de envío y su detalle
CREATE TABLE pedido (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  estado ENUM('pendiente', 'pagado', 'rechazado') NOT NULL DEFAULT 'pendiente', -- estado del pago (lo decide la pasarela)
  estado_envio ENUM('preparando', 'enviado', 'entregado') NOT NULL DEFAULT 'preparando', -- estado del envio (lo cambia el admin)
  total DECIMAL(10,2) NOT NULL,
  envio_nombre VARCHAR(60) NULL,
  envio_direccion VARCHAR(100) NULL,
  envio_ciudad VARCHAR(50) NULL,
  envio_departamento VARCHAR(30) NULL,
  envio_telefono VARCHAR(15) NULL,
  CONSTRAINT fk_pedido_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id)
);

CREATE TABLE detalle_pedido (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT NOT NULL,
  libro_id INT NOT NULL,
  cantidad INT NOT NULL,
  precio_unitario DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (pedido_id) REFERENCES pedido(id),
  FOREIGN KEY (libro_id) REFERENCES libros(id)
);

-- Pago: un pedido puede tener varios intentos (relación 1:N). Solo se guardan los últimos 4 dígitos
CREATE TABLE pago (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_pedido INT NOT NULL,
  monto DECIMAL(10,2) NOT NULL,
  ultimos4 CHAR(4) NOT NULL,
  resultado ENUM('aprobado', 'rechazado') NOT NULL,
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_pago_pedido FOREIGN KEY (id_pedido) REFERENCES pedido(id)
);
