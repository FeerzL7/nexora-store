const header = document.querySelector("header");

window.addEventListener("scroll", function () {
  header.classList.toggle("sticky", this.window.scrollY > 0);
});

let menu = document.querySelector("#menu-icon");
let navmenu = document.querySelector(".navmenu");

menu.onclick = () => {
  menu.classList.toggle("bx-x");
  navmenu.classList.toggle("open");
};

function addProducto(id) {
  let url = "back/carrito.php";
  let formData = new FormData();
  formData.append("id", id);

  fetch(url, {
    method: "POST",
    body: formData,
    mode: "cors",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.ok) {
        let elemento = document.getElementById("num_cart");
        elemento.innerHTML = data.numero;
      }
    });
}

function actualizaCantidad(cantidad, id) {
  let url = "back/actualizar_carrito.php";
  let formData = new FormData();
  formData.append("action", "agregar");
  formData.append("id", id);
  formData.append("cantidad", cantidad);
  fetch(url, {
    method: "POST",
    body: formData,
    mode: "cors",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.ok) {
        let divsubtotal = document.getElementById("subtotal_" + id);
        divsubtotal.innerHTML = data.sub;
        let total = 0.0;
        let list = document.getElementsByName("subtotal[]");
        for (let i = 0; i < list.lenght; i++) {
          total += parsefloat(list[i].innerHTML.replace(/[$,]/g, ""));
        }
        total = new Intl.NumberFormat("en-US", {
          minimumFractionDigits: 2,
        }).format(total);
        document.getElementById("total").innerHTML =
          "<?php echo PRECIO; ?>" + total;
      }
    });
}

let eliminaModal = document.getElementById("eliminaModal");
eliminaModal.addEventListener("show.bs.modal", function (event) {
  let button = event.relatedTarget;
  let id = button.getAttribute("data-bs-id");
  let buttonElimina = eliminaModal.querySelector("#btn-elimina");
  buttonElimina.value = id;
});

function mostrarModal() {
  document.getElementById("eliminaModal").style.display = "flex";
}

function ocultarModal() {
  document.getElementById("eliminaModal").style.display = "none";
}

document.addEventListener("DOMContentLoaded", function () {
  const lgn = document.getElementById("lgn");
  const btnSession = document.getElementById("btn_session");
  const dropdownMenu = document.querySelector(".dropdown-menu");

  // Muestra u oculta el menú desplegable al hacer clic en el icono del usuario
  btnSession.addEventListener("click", function () {
    dropdownMenu.classList.toggle("show");
  });

  // Cierra el menú desplegable si se hace clic fuera de él
  document.addEventListener("click", function (event) {
    if (
      !btnSession.contains(event.target) &&
      !dropdownMenu.contains(event.target)
    ) {
      dropdownMenu.classList.remove("show");
    }
  });
  lgn.addEventListener("click", function (event) {
    event.stopPropagation();
  });
});

function eliminar() {
  let botonElimina = document.getElementById("btn-elimina");
  let id = botonElimina.value;
  let url = "back/actualizar_carrito.php";
  let formData = new FormData();
  formData.append("action", "eliminar");
  formData.append("id", id);
  fetch(url, {
    method: "POST",
    body: formData,
    mode: "cors",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.ok) {
        location.reload();
      }
    });
}

let txtUsuario = document.getElementById("usuario");
txtUsuario.addEventListener(
  "blur",
  function () {
    exsisteUsuario(txtUsuario.value);
  },
  false
);
let txtEmail = document.getElementById("email");
txtEmail.addEventListener(
  "blur",
  function () {
    existeEmail(txtEmail.value);
  },
  false
);

function existeUsuario(usuario) {
  let url = "back/validaciones.php";
  let formData = new FormData();
  formData.append("action", "existeUsuario");
  formData.append("usuario", usuario);
  fetch(url, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.ok) {
        document.getElementById("usuario").value = "";
        document.getElementById("validaUsuario").innerHTML =
          "Usuario no disponible";
      } else {
        document.getElementById("validaUsuario").innerHTML = "";
      }
    });
}

function existeEmail(email) {
  let url = "back/validaciones.php";
  let formData = new FormData();
  formData.append("action", "existeEmail");
  formData.append("email", email);
  fetch(url, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.ok) {
        document.getElementById("email").value = "";
        document.getElementById("validaEmail").innerHTML =
          "Correo no disponible";
      } else {
        document.getElementById("validaEmail").innerHTML = "";
      }
    });
}
