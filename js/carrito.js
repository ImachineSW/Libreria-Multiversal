// funcion para mostrar el carrito
function mostrarCarrito() {
    const carrito = cargarCarrito(); // carga el carrito
    const cont = document.querySelector(".productos"); // le asigna a la variable cont el contenedor en el documento
    const resumen = document.querySelector(".total"); // le asigna a la variable resumen el resumen en el documento
    const bookr = document.querySelector(".libros-r"); // le asigna a la variable bookr el resumen de libros en el documento

    let total = 0; // inicializa la variable

    cont.innerHTML = ""; // reinicia la pagina
    bookr.innerHTML = ""; // reinicia el resumen de libros

    if (carrito.length === 0) { // si el carrito esta vacio muestra esto
        cont.innerHTML = `<h3>CARRITO VACIO</h3>`;
        resumen.textContent = `Total: $${total}`;
        return;
    }

    carrito.forEach(libro => { // recorre el array
        const subtotal = libro.precio * libro.cantidad;
        total += subtotal; // suma el precio de los libros
        bookr.appendChild(document.createElement("p")).textContent = `${libro.nombre}: $${libro.precio}`; // agrega el nombre y precio de los libros al resumen

        const div = document.createElement("div"); // crea un div
        div.classList.add("producto"); // le asigna la clase producto al div
        
        div.innerHTML = `
        <h3>${libro.nombre}</h3>
        <img src=".${libro.imagen}" alt="${libro.nombre}">
        <h4>Cantidad: ${libro.cantidad}</h4>
        <button class="eliminar">ELIMINAR</button>
        `; // por cada libro en el array agrega eso

        cont.appendChild(div); // agrega los div a la pagina

        const boton = div.querySelector(".eliminar"); // busca el boton de los libros
        boton.addEventListener("click", () => eliminar(libro)); // a los botones le da la funcion de eliminar el libro
    });

    resumen.textContent = `Total: $${total}`; // actualiza el total

};
//

// funcion para eliminar un libro
function eliminar(libro) { 
    let carrito = cargarCarrito(); // carga el carrito guardado
    const existente = carrito.find(item => item.id === libro.id);
    if (existente.cantidad > 1) {
        existente.cantidad--;
        guardarCarrito(carrito);
        mostrarCarrito();
        mostrarMensaje(`Se eliminó "${libro.nombre}" del carrito.`); // muestra mensaje flotante
        return;
    } else {
    carrito = carrito.filter(item => item.id !== libro.id); // elimina el libro del carrito
    }
    mostrarMensaje(`Se eliminó "${libro.nombre}" del carrito.`); // muestra mensaje flotante
    guardarCarrito(carrito); // guarda el carrito luego de eliminar el libro
    mostrarCarrito(); // vuelve a mostrar el carrito
}
//

document.addEventListener("DOMContentLoaded", () => {
    mostrarCarrito();
});