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
        carrito.push({ // manda estos datos al array carrito
        id: libro.id,
        nombre: libro.nombre,
        precio: libro.precio,
        formato: libro.formato,
        imagen: libro.imagen
    });
    guardarCarrito(carrito); // guarda el carrito
}
//