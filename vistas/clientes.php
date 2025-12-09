<?php
include_once '../php/verificar_sesion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Suscriptores · SmartGate</title>

  <!-- Config Tailwind -->
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
          }
        }
      }
    };
  </script>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- SweetAlert oscuro (igual que login/dashboard) -->
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
    .full-w{
      width: -webkit-fill-available;
    }
  </style>
</head>

<body class="bg-slate-950 text-slate-50 font-sans min-h-screen flex">

  <?php include_once '../includes/navbar.php'; ?>

  <!-- CONTENIDO -->
  <main class="ml-64 flex-1 px-8 py-8 relative">
    <div class="pointer-events-none absolute inset-x-0 -top-32 h-40 bg-gradient-to-b from-sky-500/10 via-transparent to-transparent"></div>

    <div class="relative z-10 max-w-6xl mx-auto">
      <!-- Header -->
      <header class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold tracking-tight">
          Gestión de Suscriptores
        </h1>
        <p class="text-sm text-slate-400 mt-1">
          Administra la información de tus suscriptores y controla rápidamente su estado.
        </p>
      </header>

      <!-- Barra superior (botón + espacio para futuro buscador) -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <button
          id="btnAgregarSuscriptor"
          class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl
                 bg-emerald-600 hover:bg-emerald-500 text-sm font-semibold
                 shadow-md shadow-emerald-600/40 transition-transform hover:-translate-y-0.5"
        >
          + Agregar Suscriptor
        </button>

        <!-- (si luego quieres, aquí podemos meter un input de búsqueda) -->
      </div>

      <!-- Tabla -->
      <section class="bg-slate-900/90 border border-slate-800 rounded-2xl shadow-lg shadow-black/40 px-6 py-6">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="text-lg font-semibold">Listado de Suscriptores</h2>
            <p class="text-xs text-slate-400 mt-1">
              Visualiza y edita la información básica de cada suscriptor.
            </p>
          </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-800">
          <table class="min-w-full text-left text-sm" id="tablaSuscriptores">
            <thead class="bg-slate-800 text-slate-200 text-xs uppercase tracking-wide">
              <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Nombre</th>
                <th class="px-4 py-2">Correo</th>
                <th class="px-4 py-2">Teléfono</th>
                <th class="px-4 py-2">Activo</th>
                <th class="px-4 py-2 text-right">Acciones</th>
              </tr>
            </thead>
            <tbody class="text-slate-200">
              <!-- clientes.js llenará aquí -->
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </main>

  <script src="../js/clientes.js"></script>
</body>
</html>
