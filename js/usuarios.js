// js/usuarios.js

document.addEventListener('DOMContentLoaded', () => {
  cargarUsuarios();

  const btnAgregar = document.getElementById("btnAgregarUsuario");
  if (btnAgregar) {
    btnAgregar.addEventListener("click", mostrarModalAgregarUsuario);
  }
});

function cargarUsuarios() {
  fetch("../php/usuarios_controller.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ action: "listar" })
  })
    .then(res => res.json())
    .then(data => {
      if (!data.success) throw new Error(data.error || "No se pudieron obtener los usuarios");

      const tbody = document.querySelector("#tablaUsuarios tbody");
      tbody.innerHTML = "";

      data.usuarios.forEach(usuario => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
        <td class="p-3">${usuario.id}</td>  
          <td class="p-3">${usuario.nombre}</td>
          <td class="p-3">${usuario.correo}</td>
          <td class="p-3">${usuario.creado}</td>
          <td class="p-3 text-right">
            <button class="ext-yellow-400 hover:text-white border border-yellow-400 hover:bg-yellow-500 rounded-lg text-sm px-5 py-2.5 transition" onclick='editarUsuario(${JSON.stringify(usuario)})'>✏️ Editar</button>
            <button class="text-red-500 hover:text-white border border-red-500 hover:bg-red-600 rounded-lg text-sm px-5 py-2.5 transition" onclick="eliminarUsuario(${usuario.id})">🗑️ Eliminar</button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    })
    .catch(err => {
      Swal.fire("Error", err.message, "error");
    });
}

function mostrarModalAgregarUsuario() {
  Swal.fire({
    title: "Agregar Usuario",
    html: `
      <input id="swal-nombre" class="swal2-input" placeholder="Nombre">
      <input id="swal-correo" class="swal2-input" placeholder="Correo">
      <input id="swal-password" type="password" class="swal2-input" placeholder="Contraseña">
      <input id="swal-confirmar" type="password" class="swal2-input" placeholder="Confirmar Contraseña">
    `,
    confirmButtonText: "Guardar",
    showCancelButton: true,
    preConfirm: () => {
      const nombre = document.getElementById("swal-nombre").value.trim();
      const correo = document.getElementById("swal-correo").value.trim();
      const password = document.getElementById("swal-password").value;
      const confirmar = document.getElementById("swal-confirmar").value;

      if (!nombre || !correo || !password || !confirmar) {
        Swal.showValidationMessage("Todos los campos son obligatorios");
        return false;
      }

      if (password !== confirmar) {
        Swal.showValidationMessage("Las contraseñas no coinciden");
        return false;
      }

      return { action: "agregar", nombre, correo, password };
    }
  }).then(result => {
    if (result.isConfirmed) {
      Swal.showLoading();
      fetch("../php/usuarios_controller.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(result.value)
      })
        .then(res => res.json())
        .then(data => {
          Swal.close();
          if (data.success) {
            Swal.fire("Éxito", data.message, "success").then(() => cargarUsuarios());
          } else {
            Swal.fire("Error", data.message || "No se pudo agregar", "error");
          }
        })
        .catch(err => {
          Swal.close();
          Swal.fire("Error", err.message, "error");
        });
    }
  });
}


function editarUsuario(usuario) {
  Swal.fire({
    title: "Editar Usuario",
    html: `
      <input id="swal-nombre" class="swal2-input" value="${usuario.nombre}" placeholder="Nombre">
      <input id="swal-correo" class="swal2-input" value="${usuario.correo}" placeholder="Correo">
      <hr class="my-2">
      <input id="swal-password" type="password" class="swal2-input" placeholder="Nueva contraseña (opcional)">
    `,
    confirmButtonText: "Actualizar",
    showCancelButton: true,
    preConfirm: () => {
      const nombre = document.getElementById("swal-nombre").value.trim();
      const correo = document.getElementById("swal-correo").value.trim();
      const password = document.getElementById("swal-password").value;

      if (!nombre || !correo) {
        Swal.showValidationMessage("Nombre y correo son obligatorios");
        return false;
      }

      return { action: "editar", id: usuario.id, nombre, correo, password: password || null };
    }
  }).then(result => {
    if (result.isConfirmed) {
      Swal.showLoading();
      fetch("../php/usuarios_controller.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(result.value)
      })
        .then(res => res.json())
        .then(data => {
          Swal.close();
          if (data.success) {
            Swal.fire("Actualizado", data.message, "success").then(() => cargarUsuarios());
          } else {
            Swal.fire("Error", data.message || "No se pudo actualizar", "error");
          }
        })
        .catch(err => {
          Swal.close();
          Swal.fire("Error", err.message, "error");
        });
    }
  });
}


function eliminarUsuario(id) {
  Swal.fire({
    title: "¿Eliminar usuario?",
    text: "Esta acción no se puede deshacer",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar"
  }).then(result => {
    if (result.isConfirmed) {
      Swal.showLoading();
      fetch("../php/usuarios_controller.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "eliminar", id })
      })
        .then(res => res.json())
        .then(data => {
          Swal.close();
          if (data.success) {
            Swal.fire("Eliminado", data.message, "success").then(() => cargarUsuarios());
          } else {
            Swal.fire("Error", data.message || "No se pudo eliminar", "error");
          }
        })
        .catch(err => {
          Swal.close();
          Swal.fire("Error", err.message, "error");
        });
    }
  });
}
