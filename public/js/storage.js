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