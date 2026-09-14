/*
 * NEXORA — script del sitio público.
 *
 * Cada bloque comprueba que sus elementos existan antes de tocarlos: el
 * archivo es el mismo en todas las páginas y no todas tienen carrito,
 * filtros o formularios.
 */

// Comprobado con typeof: una llamada suelta en el nivel superior que truene
// vuelve a tirar el archivo completo, que era el fallo original del proyecto.
const REDUCIR_MOVIMIENTO =
  typeof window.matchMedia === "function" &&
  window.matchMedia("(prefers-reduced-motion: reduce)").matches;

document.addEventListener("DOMContentLoaded", function () {
  encabezadoFijo();
  menuMovil();
  menuSesion();
  aparicionAlDesplazar();
  filtrosDeCategoria();
  atajosDeCategoria();
});

/* ---------- encabezado ---------- */

function encabezadoFijo() {
  const encabezado = document.getElementById("encabezado");
  if (!encabezado) return;

  const alternar = () => encabezado.classList.toggle("fijo", window.scrollY > 24);
  alternar();
  window.addEventListener("scroll", alternar, { passive: true });
}

function menuMovil() {
  const boton = document.getElementById("menu-icon");
  const menu = document.getElementById("menu");
  if (!boton || !menu) return;

  boton.addEventListener("click", function () {
    const abierto = menu.classList.toggle("abierto");
    boton.classList.toggle("bx-x", abierto);
    boton.classList.toggle("bx-menu", !abierto);
    boton.setAttribute("aria-expanded", abierto);
  });

  // Al elegir un destino el menú se cierra solo.
  menu.querySelectorAll("a").forEach((enlace) => {
    enlace.addEventListener("click", function () {
      menu.classList.remove("abierto");
      boton.classList.replace("bx-x", "bx-menu");
      boton.setAttribute("aria-expanded", "false");
    });
  });
}

function menuSesion() {
  const boton = document.getElementById("btn_session");
  const menu = document.getElementById("menu_sesion");
  if (!boton || !menu) return;

  const alternar = (event) => {
    event.stopPropagation();
    const abierto = menu.classList.toggle("abierto");
    boton.setAttribute("aria-expanded", abierto);
  };

  boton.addEventListener("click", alternar);
  boton.addEventListener("keydown", (e) => {
    if (e.key === "Enter" || e.key === " ") {
      e.preventDefault();
      alternar(e);
    }
  });

  document.addEventListener("click", function (event) {
    if (!boton.contains(event.target) && !menu.contains(event.target)) {
      menu.classList.remove("abierto");
      boton.setAttribute("aria-expanded", "false");
    }
  });
}

/* ---------- aparición al desplazar ---------- */

function aparicionAlDesplazar() {
  const objetivos = document.querySelectorAll(".aparece");
  if (!objetivos.length) return;

  // Sin IntersectionObserver o con movimiento reducido, todo queda visible.
  if (REDUCIR_MOVIMIENTO || !("IntersectionObserver" in window)) {
    objetivos.forEach((el) => el.classList.add("dentro"));
    return;
  }

  const observador = new IntersectionObserver(
    (entradas) => {
      entradas.forEach((entrada, i) => {
        if (!entrada.isIntersecting) return;
        // Escalonado corto para que la fila entre como grupo, no una por una.
        setTimeout(() => entrada.target.classList.add("dentro"), i * 60);
        observador.unobserve(entrada.target);
      });
    },
    { rootMargin: "0px 0px -8% 0px", threshold: 0.12 }
  );

  objetivos.forEach((el) => observador.observe(el));
}

/* ---------- filtros del catálogo ---------- */

function filtrosDeCategoria() {
  const botones = document.querySelectorAll(".filtro");
  const rejilla = document.getElementById("rejilla");
  if (!botones.length || !rejilla) return;

  const piezas = rejilla.querySelectorAll(".pieza");
  const vacio = document.getElementById("sin_resultados");

  const aplicar = (categoria) => {
    let visibles = 0;

    piezas.forEach((pieza) => {
      const suya = categoria === "0" || pieza.dataset.categoria === categoria;
      pieza.classList.toggle("oculta", !suya);
      if (suya) {
        visibles++;
        // Las piezas que entran al filtro se revelan, no aparecen de golpe.
        pieza.classList.add("dentro");
      }
    });

    botones.forEach((b) => b.classList.toggle("activo", b.dataset.filtro === categoria));
    if (vacio) vacio.hidden = visibles > 0;
  };

  botones.forEach((boton) => {
    boton.addEventListener("click", () => aplicar(boton.dataset.filtro));
  });

  // Respeta el filtro que ya venía marcado desde el servidor (?categoria=).
  const marcado = document.querySelector(".filtro.activo");
  if (marcado && marcado.dataset.filtro !== "0") aplicar(marcado.dataset.filtro);
}

/* Los discos de categoría llevan al catálogo con su filtro ya puesto. */
function atajosDeCategoria() {
  document.querySelectorAll("[data-ir-a]").forEach((atajo) => {
    atajo.addEventListener("click", function () {
      const boton = document.querySelector('.filtro[data-filtro="' + atajo.dataset.irA + '"]');
      if (boton) boton.click();
    });
  });
}

/* ---------- aviso emergente ---------- */

let temporizadorAviso = null;

function avisar(texto) {
  const caja = document.getElementById("mensajito");
  const cuerpo = document.getElementById("mensajito_texto");
  if (!caja || !cuerpo) return;

  cuerpo.textContent = texto;
  caja.classList.add("visible");

  clearTimeout(temporizadorAviso);
  temporizadorAviso = setTimeout(() => caja.classList.remove("visible"), 2600);
}

/* ---------- carrito ---------- */

function actualizaContador(numero) {
  const elemento = document.getElementById("num_cart");
  if (!elemento || typeof numero === "undefined") return;

  elemento.textContent = numero;
  elemento.classList.remove("late");
  void elemento.offsetWidth; // reinicia la animación
  elemento.classList.add("late");
}

function addProducto(id, nombre) {
  const formData = new FormData();
  formData.append("id", id);

  fetch("back/carrito.php", { method: "POST", body: formData })
    .then((r) => r.json())
    .then((data) => {
      if (!data.ok) {
        avisar("Esa pieza ya no está disponible");
        return;
      }
      actualizaContador(data.numero);
      avisar(nombre ? nombre + " va en tu carrito" : "Se agregó a tu carrito");
    })
    .catch(() => avisar("No pudimos agregarla. Revisa tu conexión"));
}

function actualizaCantidad(cantidad, id) {
  const formData = new FormData();
  formData.append("action", "agregar");
  formData.append("id", id);
  formData.append("cantidad", cantidad);

  fetch("back/actualizar_carrito.php", { method: "POST", body: formData })
    .then((r) => r.json())
    .then((data) => {
      if (!data.ok) {
        avisar("La cantidad debe estar entre 1 y 10");
        return;
      }

      const subtotal = document.getElementById("subtotal_" + id);
      if (subtotal) {
        subtotal.textContent = data.sub;
        // Un destello confirma qué importe acaba de cambiar.
        subtotal.classList.add("cambiado");
        setTimeout(() => subtotal.classList.remove("cambiado"), 700);
      }

      const total = document.getElementById("total");
      if (total) total.textContent = data.total;

      const piezas = document.getElementById("conteo_piezas");
      if (piezas) piezas.textContent = data.numero;

      actualizaContador(data.numero);
    })
    .catch(() => avisar("No pudimos actualizar la cantidad"));
}

/* ---------- diálogo de confirmación ---------- */

let idPorEliminar = null;

function mostrarModal(id, nombre) {
  const dialogo = document.getElementById("eliminaModal");
  if (!dialogo) return;

  idPorEliminar = id;

  const texto = document.getElementById("dialogo_texto");
  if (texto) {
    texto.textContent = nombre
      ? "¿Quitas " + nombre + " de tu carrito?"
      : "¿Quitas esta pieza de tu carrito?";
  }

  dialogo.classList.add("abierto");
  document.addEventListener("keydown", cerrarConEscape);
}

function cerrarConEscape(event) {
  if (event.key === "Escape") ocultarModal();
}

function ocultarModal() {
  const dialogo = document.getElementById("eliminaModal");
  if (dialogo) dialogo.classList.remove("abierto");
  idPorEliminar = null;
  document.removeEventListener("keydown", cerrarConEscape);
}

function eliminar() {
  if (idPorEliminar === null) return;

  const formData = new FormData();
  formData.append("action", "eliminar");
  formData.append("id", idPorEliminar);

  fetch("back/actualizar_carrito.php", { method: "POST", body: formData })
    .then((r) => r.json())
    .then((data) => {
      ocultarModal();
      if (data.ok) location.reload();
    })
    .catch(() => {
      ocultarModal();
      avisar("No pudimos quitarla. Inténtalo de nuevo");
    });
}

/* ---------- validaciones del registro ---------- */

document.addEventListener("DOMContentLoaded", function () {
  const usuario = document.getElementById("usuario");
  const email = document.getElementById("email");

  if (usuario && document.getElementById("validaUsuario")) {
    usuario.addEventListener("blur", () => existeUsuario(usuario.value));
  }
  if (email && document.getElementById("validaEmail")) {
    email.addEventListener("blur", () => existeEmail(email.value));
  }
});

function existeUsuario(usuario) {
  if (!usuario) return;

  const formData = new FormData();
  formData.append("action", "existeUsuario");
  formData.append("usuario", usuario);

  fetch("back/validaciones.php", { method: "POST", body: formData })
    .then((r) => r.json())
    .then((data) => {
      const aviso = document.getElementById("validaUsuario");
      if (aviso) aviso.textContent = data.ok ? "Ese usuario ya está ocupado" : "";
    })
    .catch(() => {});
}

function existeEmail(email) {
  if (!email) return;

  const formData = new FormData();
  formData.append("action", "existeEmail");
  formData.append("email", email);

  fetch("back/validaciones.php", { method: "POST", body: formData })
    .then((r) => r.json())
    .then((data) => {
      const aviso = document.getElementById("validaEmail");
      if (aviso) aviso.textContent = data.ok ? "Ese correo ya está registrado" : "";
    })
    .catch(() => {});
}
