// js/suscriptores.js

document.addEventListener("DOMContentLoaded", () => {
  cargarSuscriptores();

  const btnAgregar = document.getElementById("btnAgregarSuscriptor");
  if (btnAgregar) {
    btnAgregar.addEventListener("click", mostrarModalAgregar);
  }
});

function cargarSuscriptores() {
  fetch("../php/suscriptores_controller.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ action: "listar" }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (!data.success)
        throw new Error(data.message || "Error al cargar suscriptores");

      const tbody = document.querySelector("#tablaSuscriptores tbody");
      tbody.innerHTML = "";

      if (!data.suscriptores || data.suscriptores.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="6" class="text-center py-4 text-slate-400 text-sm">
              No hay suscriptores registrados aún.
            </td>
          </tr>
        `;
        return;
      }

      data.suscriptores.forEach((s) => {
        const activo = Number(s.activo) === 1;

        const badgeClasses = activo
          ? "bg-emerald-500/10 text-emerald-400 border border-emerald-500/50"
          : "bg-rose-500/10 text-rose-400 border border-rose-500/50";

        const tr = document.createElement("tr");
        tr.className =
          "border-t border-slate-800/80 hover:bg-slate-800/60 transition-colors";

        tr.innerHTML = `
          <td class="px-4 py-3 text-xs text-slate-400">${s.id}</td>
          <td class="px-4 py-3 text-sm font-medium text-slate-100">
            ${s.nombre} ${s.apellido || ""}
          </td>
          <td class="px-4 py-3 text-sm text-slate-300">${s.correo}</td>
          <td class="px-4 py-3 text-sm text-slate-300">${s.telefono}</td>
          <td class="px-4 py-3">
            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full ${badgeClasses}">
              ${activo ? "Activo" : "Inactivo"}
            </span>
          </td>
          <td class="px-4 py-3 text-right space-x-2">
            <button
              class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full 
                     border border-sky-500/60 text-sky-400 
                     hover:bg-sky-500/10 hover:text-sky-300 transition-colors"
              onclick='editarSuscriptor(${JSON.stringify(s)})'>
              Editar
            </button>
            <button
              class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full 
                     border border-rose-500/60 text-rose-400 
                     hover:bg-rose-500/10 hover:text-rose-300 transition-colors"
              onclick="eliminarSuscriptor(${s.id})">
              Eliminar
            </button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    })
    .catch((err) => Swal.fire("Error", err.message, "error"));
}

/* ========== MODAL AGREGAR ========== */

function mostrarModalAgregar() {
  Swal.fire({
    title: "Agregar Suscriptor",
    html: `
      <div style="max-width:420px;margin:0 auto;font-size:0.9rem;">
        <label class="block mb-1 mt-4 text-slate-300">Nombre</label>
        <input id="swal-nombre" class="swal2-input full-w" placeholder="Nombre">

        <label class="block mb-1 mt-4 text-slate-300">Apellido</label>
        <input id="swal-apellido" class="swal2-input full-w" placeholder="Apellido">

        <label class="block mb-1 mt-4 text-slate-300">Teléfono</label>
        <input id="swal-telefono" class="swal2-input full-w" placeholder="Teléfono">

        <label class="block mb-1 mt-4 text-slate-300">Correo</label>
        <input id="swal-correo" class="swal2-input full-w" placeholder="correo@ejemplo.com">

        <label class="block mb-1 mt-4 text-slate-300">Dirección</label>
        <input id="swal-direccion" class="swal2-input full-w" placeholder="Dirección completa">

        <label class="block mb-1 mt-4 text-slate-300">Comentarios</label>
        <textarea id="swal-comentarios" class="swal2-textarea full-w" placeholder="Comentarios (opcional)"></textarea>
      </div>
    `,
    confirmButtonText: "Guardar",
    showCancelButton: true,
    focusConfirm: false,
    preConfirm: () => {
      const nombre = document.getElementById("swal-nombre").value.trim();
      const apellido = document.getElementById("swal-apellido").value.trim();
      const telefono = document.getElementById("swal-telefono").value.trim();
      const correo = document.getElementById("swal-correo").value.trim();
      const direccion = document.getElementById("swal-direccion").value.trim();
      const comentarios = document.getElementById("swal-comentarios").value.trim();

      if (!nombre || !apellido || !telefono || !correo || !direccion) {
        Swal.showValidationMessage("Todos los campos obligatorios deben estar completos");
        return false;
      }

      const fecha_ingreso = new Date().toISOString().split("T")[0];

      return {
        action: "agregar",
        nombre,
        apellido,
        telefono,
        correo,
        direccion,
        comentarios,
        fecha_ingreso,
        fecha_fin: fecha_ingreso,
        activo: 0,
      };
    },
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.showLoading();
      fetch("../php/suscriptores_controller.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(result.value),
      })
        .then((res) => res.json())
        .then((data) => {
          Swal.close();
          if (data.success) {
            Swal.fire("Éxito", data.message, "success").then(() => cargarSuscriptores());
          } else {
            Swal.fire("Error", data.message || "No se pudo agregar", "error");
          }
        })
        .catch((err) => {
          Swal.close();
          Swal.fire("Error", err.message, "error");
        });
    }
  });
}


/* ========== MODAL EDITAR ========== */

function editarSuscriptor(s) {
  Swal.fire({
    title: "Editar Suscriptor",
    html: `
      <div style="max-width:420px;margin:0 auto;font-size:0.9rem;">
        <label class="block mb-1 mt-4 text-slate-300">Nombre</label>
        <input id="swal-nombre" class="swal2-input full-w" value="${s.nombre}" placeholder="Nombre">

        <label class="block mb-1 mt-4 text-slate-300">Apellido</label>
        <input id="swal-apellido" class="swal2-input full-w" value="${s.apellido}" placeholder="Apellido">

        <label class="block mb-1 mt-4 text-slate-300">Teléfono</label>
        <input id="swal-telefono" class="swal2-input full-w" value="${s.telefono}" placeholder="Teléfono">

        <label class="block mb-1 mt-4 text-slate-300">Correo</label>
        <input id="swal-correo" class="swal2-input full-w" value="${s.correo}" placeholder="Correo">

        <label class="block mb-1 mt-4 text-slate-300">Dirección</label>
        <input id="swal-direccion" class="swal2-input full-w" value="${s.direccion}" placeholder="Dirección">

        <label class="block mb-1 mt-4 text-slate-300">Comentarios</label>
        <textarea id="swal-comentarios" class="swal2-textarea full-w" placeholder="Comentarios">${s.comentarios || ""}</textarea>

        <label class="block mb-1 mt-4 text-slate-300">Fecha de Ingreso</label>
        <input id="swal-fecha-ingreso" type="date" class="swal2-input full-w" value="${s.fecha_ingreso}">

        <label class="block mb-1 mt-4 text-slate-300">Fecha de Fin</label>
        <input id="swal-fecha-fin" type="date" class="swal2-input full-w" value="${s.fecha_fin}">

        <label class="inline-flex items-center mt-3 text-slate-300 text-sm">
          <input type="checkbox" id="swal-activo" class="mr-2" ${s.activo ? "checked" : ""}>
          Activo
        </label>
      </div>
    `,
    confirmButtonText: "Actualizar",
    showCancelButton: true,
    focusConfirm: false,
    preConfirm: () => {
      const nombre = document.getElementById("swal-nombre").value.trim();
      const apellido = document.getElementById("swal-apellido").value.trim();
      const telefono = document.getElementById("swal-telefono").value.trim();
      const correo = document.getElementById("swal-correo").value.trim();
      const direccion = document.getElementById("swal-direccion").value.trim();
      const comentarios = document.getElementById("swal-comentarios").value.trim();
      const fecha_ingreso = document.getElementById("swal-fecha-ingreso").value;
      const fecha_fin = document.getElementById("swal-fecha-fin").value;
      const activo = document.getElementById("swal-activo").checked ? 1 : 0;

      if (!nombre || !apellido || !telefono || !correo || !direccion || !fecha_ingreso || !fecha_fin) {
        Swal.showValidationMessage("Todos los campos obligatorios deben estar completos");
        return false;
      }

      return {
        action: "editar",
        id: s.id,
        nombre,
        apellido,
        telefono,
        correo,
        direccion,
        comentarios,
        fecha_ingreso,
        fecha_fin,
        activo,
      };
    },
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.showLoading();
      fetch("../php/suscriptores_controller.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(result.value),
      })
        .then((res) => res.json())
        .then((data) => {
          Swal.close();
          if (data.success) {
            Swal.fire("Actualizado", data.message, "success").then(() => cargarSuscriptores());
          } else {
            Swal.fire("Error", data.message || "No se pudo actualizar", "error");
          }
        })
        .catch((err) => {
          Swal.close();
          Swal.fire("Error", err.message, "error");
        });
    }
  });
}


/* ========== ELIMINAR ========== */

function eliminarSuscriptor(id) {
  Swal.fire({
    title: "¿Eliminar suscriptor?",
    text: "Esta acción no se puede deshacer",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.showLoading();
      fetch("../php/suscriptores_controller.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "eliminar", id }),
      })
        .then((res) => res.json())
        .then((data) => {
          Swal.close();
          if (data.success) {
            Swal.fire("Eliminado", data.message, "success").then(() =>
              cargarSuscriptores()
            );
          } else {
            Swal.fire(
              "Error",
              data.message || "No se pudo eliminar",
              "error"
            );
          }
        })
        .catch((err) => {
          Swal.close();
          Swal.fire("Error", err.message, "error");
        });
    }
  });
}
