// funcion para mostrar el carrito
function mostrarCarrito() {
    const carrito = cargarCarrito(); // carga el carrito
    const cont = document.querySelector(".productos"); // le asigna a la variable cont el contenedor en el documento
    const resumen = document.querySelector(".total"); // le asigna a la variable resumen el resumen en el documento
    const bookr = document.querySelector(".libros-r"); // le asigna a la variable bookr el resumen de libros en el documento

    let total = 0; // inicializa la variable

    cont.innerHTML = ""; // reinicia la pagina

    if (carrito.length === 0) { // si el carrito esta vacio muestra esto
        cont.innerHTML = `<h3>CARRITO VACIO</h3>`;
        resumen.textContent = `Total: $${total}`;
        return;
    }

    carrito.forEach(libro => { // recorre el array
        const subtotal = libro.precio;
        total += subtotal; // suma el precio de los libros
        bookr.appendChild(document.createElement("p")).textContent = `${libro.nombre}: $${libro.precio}`; // agrega el nombre y precio de los libros al resumen

        const div = document.createElement("div"); // crea un div
        div.classList.add("producto"); // le asigna la clase producto al div
        
        div.innerHTML = `
        <h3>${libro.nombre}</h3>
        <img src="${libro.imagen}" alt="${libro.nombre}">
        <p>Formato: ${libro.formato}</p>
        <button class="eliminar">ELIMINAR</button>
        `; // por cada libro en el array agrega eso

        cont.appendChild(div); // agrega los div a la pagina
    });

    resumen.textContent = `Total: $${total}`; // actualiza el total

};
//

document.addEventListener("DOMContentLoaded", () => {
    mostrarCarrito();
});