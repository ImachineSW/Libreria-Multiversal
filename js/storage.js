function guardarCarrito(carrito) { 
    localStorage.setItem(CLAVE_CARRITO, JSON.stringify(carrito));
};

const CLAVE_CARRITO = "carrito";

function cargarCarrito() {
    const carritoGuardado = localStorage.getItem(CLAVE_CARRITO);

    if (carritoGuardado) {
        return JSON.parse(carritoGuardado);
    }
    return [];
};

// funcion para agregar carrito
function agregar(libro) { 
    const carrito = cargarCarrito(); // asigna el carrito
    const existente = carrito.find(item => item.id === libro.id); // busca el id del libro
    if (existente) {
        existente.cantidad++; // si el libro ya esta en el carrito aumenta la cantidad
    } else {
        carrito.push({ // manda estos datos al array carrito
        id: libro.id,
        nombre: libro.nombre,
        precio: libro.precio,
        formato: libro.formato,
        imagen: libro.imagen,
        cantidad: 1
    });
    }
    mostrarMensaje(`Se agregó "${libro.nombre}" al carrito.`); // muestra mensaje flotante
    guardarCarrito(carrito); // guarda el carrito
}
//

// funcion para mostrar mensaje flotante
function mostrarMensaje(texto) {
    const mensaje = document.getElementById("mensaje-flotante");
    mensaje.textContent = texto;
    mensaje.classList.add("mensaje-flotante-visible");
    setTimeout(() => {
    mensaje.classList.remove("mensaje-flotante-visible");
    }, 1500);
}
//