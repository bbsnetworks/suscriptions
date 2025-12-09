document.addEventListener("DOMContentLoaded", () => {
  const selectAnio = document.getElementById("selectAnio");

  selectAnio.addEventListener("change", () => {
    const anio = selectAnio.value;
    cargarSuscripciones(anio);
  });

  // Cargar año actual al iniciar
  cargarSuscripciones(selectAnio.value);
});

function cargarSuscripciones(anio) {
  const tbody = document.getElementById("tablaSuscripciones");
  tbody.innerHTML = `
    <tr>
      <td colspan="8" class="text-center py-4 text-slate-400 text-sm">
        Cargando suscripciones...
      </td>
    </tr>
  `;

  fetch(`php/obtener_suscripciones.php?anio=${anio}`)
    .then(res => res.json())
    .then(data => {
      tbody.innerHTML = "";

      // Actualizar cards SIEMPRE
      if (data.resumen) {
        document.getElementById("cardTotalSuscriptores").textContent = data.resumen.total_suscriptores;
        document.getElementById("cardActivos").textContent = data.resumen.activos;
        document.getElementById("cardIngresos").textContent =
          `$${parseFloat(data.resumen.ingresos).toLocaleString('es-MX', { minimumFractionDigits: 2 })}`;
      }

      // Si no hay datos
      if (!data.success || data.suscripciones.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="8" class="text-center py-4 text-slate-400 text-sm">
              No se encontraron registros para el año ${anio}
            </td>
          </tr>
        `;
        return;
      }

      // Filas
      data.suscripciones.forEach(s => {
        const estado = s.activo == 1 ? "Activo" : "Inactivo";

        const badgeClasses =
          s.activo == 1
            ? "bg-emerald-500/10 text-emerald-400 border border-emerald-500/50"
            : "bg-rose-500/10 text-rose-400 border border-rose-500/50";

        const tr = document.createElement("tr");
        tr.className =
          "border-t border-slate-800/80 hover:bg-slate-800/60 transition-colors";

        tr.innerHTML = `
          <td class="px-4 py-3 text-xs text-slate-400">${s.pago_id}</td>
          <td class="px-4 py-3 text-sm font-medium text-slate-100">${s.nombre}</td>
          <td class="px-4 py-3 text-sm text-slate-300">${s.correo}</td>
          <td class="px-4 py-3 text-sm text-slate-300">${s.fecha_inicio}</td>
          <td class="px-4 py-3 text-sm text-slate-300">${s.fecha_fin}</td>
          <td class="px-4 py-3 text-sm font-mono text-slate-200">${s.codigo || ""}</td>
          <td class="px-4 py-3">
            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full ${badgeClasses}">
              ${estado}
            </span>
          </td>
          <td class="px-4 py-3 text-center">
            <button
              onclick="eliminarPago(${s.pago_id})"
              class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full 
                     border border-red-500/60 text-red-400 
                     hover:bg-red-500/10 hover:text-red-300 
                     transition-colors"
            >
              Eliminar
            </button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    })
    .catch(err => {
      tbody.innerHTML = `
        <tr>
          <td colspan="8" class="text-center py-4 text-red-500">
            Error al obtener los datos
          </td>
        </tr>
      `;
      console.error("Error:", err);
    });
}

/* ========== MODAL BUSCAR SUSCRIPTOR ========== */

function mostrarModalPago() {
  Swal.fire({
    title: "Buscar Suscriptor",
    html: `
      <input id="busqueda-suscriptor" class="swal2-input" placeholder="Nombre, apellido o correo">
      <div id="resultados-suscriptores" class="text-left mt-4 max-h-48 overflow-y-auto text-slate-200 text-sm"></div>
    `,
    showCancelButton: true,
    confirmButtonText: "Cerrar",
    focusConfirm: false
  });

  const input = document.getElementById("busqueda-suscriptor");
  input.focus();

  input.addEventListener("input", () => {
    const query = input.value.trim();
    if (query.length < 3) return;

    fetch(`php/buscar_suscriptor.php?query=${encodeURIComponent(query)}`)
      .then(res => res.json())
      .then(data => {
        const contenedor = document.getElementById("resultados-suscriptores");
        contenedor.innerHTML = "";

        if (!data.success || data.suscriptores.length === 0) {
          contenedor.innerHTML = `
            <div class="px-4 py-2 text-rose-400 text-sm">Sin resultados</div>
          `;
          return;
        }

        data.suscriptores.forEach(s => {
          const div = document.createElement("div");
          div.className =
            "cursor-pointer px-4 py-2 text-sm text-slate-200 " +
            "hover:bg-slate-800 hover:text-slate-100 rounded-lg transition-colors";
          div.textContent = `${s.nombre} ${s.apellido} (${s.correo})`;
          div.onclick = () => abrirModalPagoDetalle(s);
          contenedor.appendChild(div);
        });
      });
  });
}

/* ========== MODAL REGISTRAR PAGO ========== */

function abrirModalPagoDetalle(suscriptor) {
  Swal.fire({
    title: "Registrar Pago",
    html: `
      <div class="text-left text-sm">
        <label class="block text-slate-300 mb-1">Nombre</label>
        <input id="pago-nombre" class="swal2-input" value="${suscriptor.nombre}" disabled>

        <label class="block text-slate-300 mb-1">Correo</label>
        <input id="pago-correo" class="swal2-input" value="${suscriptor.correo}" disabled>

        <label class="block text-slate-300 mb-1">Fecha Inicio</label>
        <input id="pago-inicio" type="date" class="swal2-input" value="${new Date()
          .toISOString()
          .slice(0, 10)}">

        <label class="block text-slate-300 mb-1">Fecha Fin</label>
        <input id="pago-fin" type="date" class="swal2-input">

        <label class="block text-slate-300 mb-1">Monto</label>
        <input id="pago-monto" type="number" class="swal2-input" placeholder="Ej: 1000" min="0">

        <label class="block text-slate-300 mb-1">Comentarios</label>
        <textarea id="pago-comentarios" class="swal2-textarea" placeholder="Opcional"></textarea>
      </div>
    `,
    showCancelButton: true,
    confirmButtonText: "Registrar",
    cancelButtonText: "Cancelar",
    preConfirm: () => {
      const fecha_inicio = document.getElementById("pago-inicio").value;
      const fecha_fin = document.getElementById("pago-fin").value;
      const monto = parseFloat(document.getElementById("pago-monto").value);
      const comentarios = document.getElementById("pago-comentarios").value;

      if (!fecha_inicio || !fecha_fin || isNaN(monto) || monto <= 0) {
        Swal.showValidationMessage("Completa todos los campos requeridos");
        return false;
      }

      return {
        suscriptor_id: suscriptor.id,
        fecha_inicio,
        fecha_fin,
        monto,
        comentarios
      };
    }
  }).then(result => {
    if (result.isConfirmed) {
      Swal.showLoading();
      fetch("php/registrar_pago.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(result.value)
      })
        .then(res => res.json())
        .then(data => {
          Swal.close();
          if (data.success) {
            Swal.fire("Éxito", data.message, "success");
            cargarSuscripciones(document.getElementById("selectAnio").value);
          } else {
            Swal.fire("Error", data.message || "No se pudo registrar el pago", "error");
          }
        })
        .catch(err => {
          Swal.close();
          Swal.fire("Error", err.message, "error");
        });
    }
  });
}

/* ========== ELIMINAR PAGO ========== */

function eliminarPago(id) {
  Swal.fire({
    title: "¿Eliminar este pago?",
    text: "Esta acción no se puede deshacer.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar"
  }).then(result => {
    if (result.isConfirmed) {
      fetch("php/eliminar_pago.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            Swal.fire("Eliminado", data.message, "success").then(() => {
              cargarSuscripciones(document.getElementById("selectAnio").value);
            });
          } else {
            Swal.fire("Error", data.message || "No se pudo eliminar", "error");
          }
        })
        .catch(err => Swal.fire("Error", err.message, "error"));
    }
  });
}
