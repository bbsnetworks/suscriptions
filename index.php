<?php
include_once 'php/verificar_sesion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard · Suscripciones</title>

  <!-- Config Tailwind (animaciones / colores) -->
  <script>
    tailwind = window.tailwind || {};
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              500: '#0ea5e9',
              600: '#0284c7',
            }
          },
          keyframes: {
            floatUp: {
              '0%': { opacity: 0, transform: 'translateY(16px) scale(0.97)' },
              '100%': { opacity: 1, transform: 'translateY(0) scale(1)' }
            }
          },
          animation: {
            floatUp: 'floatUp 0.35s ease-out both',
          }
        }
      }
    };
  </script>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Estilo SweetAlert oscuro (mismo del login) -->
  <style>
    .swal2-popup {
      background: #020617 !important;
      color: #e5e7eb !important;
      border-radius: 1rem !important;
      border: 1px solid #1e293b !important;
      box-shadow: 0 24px 70px rgba(0,0,0,0.9) !important;
    }
    .swal2-title {
      color: #f9fafb !important;
      font-weight: 600 !important;
    }
    .swal2-html-container {
      color: #cbd5f5 !important;
      font-size: 0.9rem !important;
    }
    .swal2-styled.swal2-confirm {
      background: #0ea5e9 !important;
      color: #0b1120 !important;
      border-radius: 9999px !important;
      border: 0 !important;
      padding: 0.55rem 1.6rem !important;
      font-weight: 600 !important;
    }
    .swal2-styled.swal2-cancel {
      background: transparent !important;
      color: #e5e7eb !important;
      border-radius: 9999px !important;
      border: 1px solid #4b5563 !important;
      padding: 0.55rem 1.6rem !important;
      font-weight: 500 !important;
    }
  </style>
</head>

<body class="bg-slate-950 text-slate-50 font-sans min-h-screen flex">

  <?php include_once 'includes/navbar.php'; ?>

  <!-- CONTENIDO PRINCIPAL -->
  <main class="ml-64 flex-1 min-h-screen px-8 py-8 relative">

    <!-- Glows suaves -->
    <div class="pointer-events-none absolute inset-x-0 -top-32 h-40 bg-gradient-to-b from-sky-500/10 via-transparent to-transparent"></div>

    <!-- Contenido -->
    <div class="relative z-10 max-w-6xl mx-auto animate-floatUp">

      <!-- Header -->
      <header class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold tracking-tight">
          Bienvenido al Panel de Control
        </h1>
        <p class="text-sm text-slate-400 mt-1">
          Monitorea suscriptores, actividad de usuarios e ingresos de tu sistema de suscripciones.
        </p>
      </header>

      <!-- Tarjetas resumen -->
      <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Total Suscriptores -->
        <article class="bg-slate-900/90 border border-slate-800 rounded-2xl px-5 py-4 shadow-lg shadow-black/40">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-medium text-slate-400">Total Suscriptores</h2>
            <div class="h-8 w-8 flex items-center justify-center rounded-xl bg-sky-500/10 border border-sky-500/40">
              <span class="text-xs text-sky-300">Σ</span>
            </div>
          </div>
          <p id="cardTotalSuscriptores" class="text-3xl font-semibold text-slate-50">0</p>
        </article>

        <!-- Usuarios Activos -->
        <article class="bg-slate-900/90 border border-slate-800 rounded-2xl px-5 py-4 shadow-lg shadow-black/40">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-medium text-slate-400">Usuarios Activos</h2>
            <div class="h-8 w-8 flex items-center justify-center rounded-xl bg-emerald-500/10 border border-emerald-500/40">
              <span class="text-xs text-emerald-300">●</span>
            </div>
          </div>
          <p id="cardActivos" class="text-3xl font-semibold text-slate-50">0</p>
        </article>

        <!-- Ingresos -->
        <article class="bg-slate-900/90 border border-slate-800 rounded-2xl px-5 py-4 shadow-lg shadow-black/40">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-medium text-slate-400">Ingresos</h2>
            <div class="h-8 w-8 flex items-center justify-center rounded-xl bg-amber-500/10 border border-amber-500/40">
              <span class="text-xs text-amber-300">$</span>
            </div>
          </div>
          <p id="cardIngresos" class="text-3xl font-semibold text-slate-50">$0.00</p>
        </article>
      </section>

      <!-- Bloque principal de estadísticas -->
      <section class="bg-slate-900/90 border border-slate-800 rounded-2xl shadow-lg shadow-black/40 px-6 py-6 mb-10">
        <h2 class="text-xl font-semibold mb-1">Estadísticas</h2>
        <p class="text-sm text-slate-400 mb-6">
          Revisa las suscripciones registradas por año y administra los pagos desde un solo lugar.
        </p>

        <!-- Suscripciones por año -->
        <div class="bg-slate-950/40 border border-slate-800 rounded-2xl px-5 py-5">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <div>
              <h3 class="text-lg font-semibold">Suscripciones por Año</h3>
              <p class="text-xs text-slate-400 mt-1">
                Selecciona un año para ver el historial de pagos de suscripción.
              </p>
            </div>

            <div class="flex items-center gap-3">
              <label for="selectAnio" class="text-xs text-slate-400">Año</label>
              <select id="selectAnio"
                      class="bg-slate-900 text-slate-100 px-3 py-2 rounded-xl border border-slate-700 text-sm
                             focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500">
                <?php
                  $anioActual = date('Y');
                  for ($i = $anioActual; $i >= $anioActual - 5; $i--) {
                    echo "<option value='$i'>$i</option>";
                  }
                ?>
              </select>
            </div>
          </div>

          <!-- Botón registrar pago -->
          <button onclick="mostrarModalPago()"
                  class="mb-4 inline-flex items-center justify-center px-4 py-2.5 rounded-xl
                         bg-emerald-600 hover:bg-emerald-500 text-sm font-semibold
                         shadow-md shadow-emerald-600/40 transition-transform hover:-translate-y-0.5">
            + Registrar Pago de Suscripción
          </button>

          <!-- Tabla -->
          <div class="overflow-x-auto rounded-xl border border-slate-800 mt-2">
            <table class="w-full text-left text-sm">
              <thead class="bg-slate-800 text-slate-200 text-xs uppercase tracking-wide">
                <tr>
                  <th class="px-4 py-2">ID</th>
                  <th class="px-4 py-2">Nombre</th>
                  <th class="px-4 py-2">Correo</th>
                  <th class="px-4 py-2">Inicio</th>
                  <th class="px-4 py-2">Fin</th>
                  <th class="px-4 py-2">Código</th>
                  <th class="px-4 py-2">Estado</th>
                  <th class="px-4 py-2 text-center">Acciones</th>
                </tr>
              </thead>
              <tbody id="tablaSuscripciones" class="text-slate-200">
                <tr>
                  <td colspan="8" class="text-center py-4 text-slate-400 text-sm">
                    Selecciona un año para ver las suscripciones.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
      </section>
    </div>
  </main>

  <script src="js/dashboard.js"></script>
</body>
</html>
