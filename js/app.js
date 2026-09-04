// funcion para agregar carrito
function agregar() { 
    const carrito = cargarCarrito(); // carga el carrito
    const add = document.querySelectorAll(".addb"); // busca el boton de agregar al carrito
    add.forEach(boton => { // recorre el array de los botones
        boton.onclick = function(e) {
            const btn = e.currentTarget; // boton actual      
            const id = btn.dataset.id; // obtiene el id del boton
            const nombre = btn.dataset.nombre; // obtiene el nombre del boton
            const precio = parseFloat(btn.dataset.precio); // obtiene el precio del boton
            const autor = btn.dataset.autor; // obtiene el autor del boton
            const formato = btn.dataset.formato; // obtiene el formato del boton
            const imagen = btn.dataset.imagen; // obtiene la imagen del boton
            const existente = carrito.find(item => item.id === id); // busca si el libro ya existe en el carrito
            if (existente) {
                existente.cantidad++; // si existe incrementa la cantidad
            } else {
                carrito.push({ id, nombre, precio, autor, formato, imagen, cantidad: 1 }); // agrega el libro al carrito si no esta en el carrito
            }
            mostrarMensaje(`Se agregó "${nombre}" al carrito.`); // muestra el mensaje flotante
            guardarCarrito(carrito); // guarda el carrito
        };
    });
}
//

// funcion para mostrar mensaje flotante
function mostrarMensaje(texto) {
    const mensaje = document.getElementById("mensaje-flotante"); // obtiene el mensaje flotante
    mensaje.textContent = texto; // muestra el mensaje flotante
    mensaje.classList.add("mensaje-flotante-visible"); // muestra el mensaje flotante
    setTimeout(() => {
    mensaje.classList.remove("mensaje-flotante-visible"); // oculta el mensaje flotante
    }, 1500);
}
//