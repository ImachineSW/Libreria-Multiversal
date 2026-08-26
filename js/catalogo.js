const libros = [
    { id: 1, nombre: "El Héroe Perdido (Volumen 1)", precio: 875, autor: "Rick Riordan", formato: "Físico", imagen: "./img/Libros/elheroeperdido.jpg", genero: "Fantasía", editorial: "Montena", año: 2025, idioma: "Español", sinopsis: "Tras ser atacados por espíritus de la tormenta, los tres son llevados al Campamento Mestizo donde descubren que son semidioses: Jason es hijo de Zeus, Piper de Afrodita y Leo de Hefesto. Su destino es cumplir una profecía que exige que Leo y Piper liberen a la diosa Hera de su prisión antes del solsticio de invierno, para impedir que los gigantes despierten a la diosa Gaia." },
    { id: 2, nombre: "El Arte de la Guerra", precio: 723, autor: "Sun Tzu", formato: "Fisico", imagen: "./img/Libros/elartedelaguerra.webp", genero: "Filosofía", editorial: "Penguin Random House", año: 2020, idioma: "Español", sinopsis: "Tratado de estrategia militar y táctica, considerado uno de los textos más antiguos y reconocidos en la historia de la guerra." },
    { id: 3, nombre: "Casa de Hojas", precio: 877, autor: "Mark Z. Danielewski", formato: "Físico", imagen: "./img/Libros/casadehojas.jpg", genero: "Terror", editorial: "Duomo Ediciones", año: 2024, idioma: "Español", sinopsis: "Will descubre que la vivienda presenta una imposibilidad física: su espacio interior es ligeramente mayor que sus dimensiones exteriores, un misterio que se agrava con la aparición de un pasillo oscuro e interminable que crece exponencialmente." },
    { id: 4, nombre: "Into the Pit", precio: 402, autor: "Scott Cawthon", formato: "Fisico", imagen: "./img/Libros/intothepit.jpg", genero: "Terror", editorial: "Kobold Publishing", año: 2023, idioma: "Español", sinopsis: "Un thriller de terror psicológico que explora los límites de la realidad y el miedo." },
    { id: 5, nombre: "Voces Anonimas", precio: 490, autor: "Guillermo Lockhart", formato: "Fisico", imagen: "./img/Libros/vocesanonimas.webp", genero: "Ficción Literaria", editorial: "Editorial Planeta", año: 2022, idioma: "Español", sinopsis: "Una obra maestra del realismo mágico latinoamericano que combina elementos de fantasía y现实idad." },
    { id: 6, nombre: "Heartstopper", precio: 850, autor:"Alice Oseman", formato:"Fisico", imagen: "./img/Libros/heartstopper.webp", genero: "Ficción Literaria", editorial: "Kobold Publishing", año: 2023, idioma: "Español", sinopsis: "Una historia de amor y amistad que explora temas de identidad y aceptación." },
    { id: 7, nombre: "The Lord of the Rings", precio: 670, autor: "J. R. R. Tolkien", formato: "Fisico", imagen: "./img/Libros/lordofthering.webp", genero: "Fantasía", editorial: "Allen & Unwin", año: 1954, idioma: "Inglés", sinopsis: "Una épica obra de fantasía que sigue el viaje de Frodo Baggins para destruir el Anillo Único." }
];

// funcion para mostrar el catalogo
function mostrar() { 
    const cont = document.getElementById("contenedor"); // busca el div con id contenedor
    cont.innerHTML = ""; // reinicia el contenido del contenedor
    libros.forEach(libro => { // recorre los libros del array
        const tar = document.createElement("div"); // crea elemento div 
        tar.classList.add("tarjeta"); // le asigna la clase producto
    tar.innerHTML = `
        <a href="./detalle-producto/productos.html?id=${libro.id}">
        <img src="${libro.imagen}" alt="${libro.nombre}" class="portada">
        <h3>${libro.nombre}</h3>
        <p class="precio">$${libro.precio}</p>
        <p>${libro.autor}</p>
        <p>${libro.formato}</p>
        </a>
        <button class="addbutton">Agregar al carrito</button>
        `; // para cada libro se crea una tarjeta
        
        cont.appendChild(tar); //agrega al contenedor cont las tarjetas

        const boton = tar.querySelector(".addbutton"); // busca el boton de los libros
        boton.addEventListener('click', () => agregar(libro)); // le da una funcion al boton
});
}
//

document.addEventListener("DOMContentLoaded", () => { // cuando se carga la pagina muestra el catalogo
    mostrar();
});
