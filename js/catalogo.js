
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
