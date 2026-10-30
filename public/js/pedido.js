// funcion para enviar el carrito al servidor y guardar el pedido
function comprar() {
    const carrito = cargarCarrito(); // carga el carrito

    if (carrito.length === 0) { // si el carrito esta vacio no se envia nada
        mostrarMensaje("El carrito está vacío.");
        return;
    }

    // solo se envian el id y la cantidad, el precio lo toma el servidor de la base de datos
    const items = carrito.map(libro => ({ id: libro.id, cantidad: libro.cantidad }));

    // se crea un formulario POST con el carrito en el campo oculto carritoJson
    const formulario = document.createElement("form");
    formulario.method = "POST";
    formulario.action = "./index.php?pagina=checkout";

    const campo = document.createElement("input");
    campo.type = "hidden";
    campo.name = "carritoJson";
    campo.value = JSON.stringify(items);

    formulario.appendChild(campo);
    document.body.appendChild(formulario);
    formulario.submit(); // el servidor pide los datos de envio
}
//

document.addEventListener("DOMContentLoaded", () => {
    const comprarbtn = document.querySelector(".comprar"); // busca el boton de comprar
    comprarbtn.addEventListener("click", comprar); // le da la funcion de guardar el pedido
});
