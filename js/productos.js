// funcion para mostrar info de libro
function mostrarLibro() {

const parametros = new URLSearchParams(window.location.search); // obtiene los parametros de la url
const id = Number(parametros.get("id")); // obtiene el id de la url

const libro = libros.find(p => p.id === id); // encuentra el libro con el id correspondiente
document.title = libro.nombre; // cambia el titulo de la pagina al nombre del libro

document.getElementById("formato").textContent = libro.formato; // muestra el formato del libro
document.getElementById("año").textContent = libro.año; // muestra el año del libro
document.getElementById("idioma").textContent = libro.idioma; // muestra el idioma del libro
document.getElementById("editorial").textContent = libro.editorial; // muestra la editorial del libro
document.getElementById("titulo").textContent = libro.nombre; // muestra el titulo del libro
document.getElementById("autor").textContent = libro.autor; // muestra el autor del libro
document.getElementById("genero").textContent = libro.genero; // muestra el género del libro
document.getElementById("imagen").src = "." + libro.imagen; // muestra la imagen del libro
document.getElementById("precio").textContent = "$" + libro.precio; // muestra el precio del libro
document.getElementById("sinopsis").textContent = libro.sinopsis; // muestra la sinopsis del libro
}
//


document.addEventListener("DOMContentLoaded", () => {
    mostrarLibro();
});