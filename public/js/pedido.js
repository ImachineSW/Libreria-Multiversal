// funcion para enviar el carrito al servidor y guardar el pedido
function comprar() {
    const carrito = cargarCarrito(); // carga el carrito

    if (carrito.length === 0) { // si el carrito esta vacio no se envia nada
        mostrarMensaje("El carrito está vacío.");
        return;
    }

    // solo se envian el id y la cantidad, el precio lo toma el servidor de la base de datos
    const items = carrito.map(libro => ({ id: libro.id, cantidad: libro.cantidad }));

    fetch("./index.php?pagina=pedido", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ items })
    })
        .then(respuesta => respuesta.json())
        .then(datos => {
            if (datos.ok) {
                guardarCarrito([]); // vacia el carrito guardado luego de comprar
                window.location.href = `./index.php?pagina=pedido_detalle&id=${datos.pedido_id}`; // muestra el detalle del pedido
            } else {
                mostrarMensaje(datos.mensaje); // muestra el error que devolvio el servidor
            }
        })
        .catch(() => mostrarMensaje("No se pudo realizar el pedido.")); // error de conexion
}
//

document.addEventListener("DOMContentLoaded", () => {
    const comprarbtn = document.querySelector(".comprar"); // busca el boton de comprar
    comprarbtn.addEventListener("click", comprar); // le da la funcion de guardar el pedido
});
