<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Iniciar sesión · Suscripciones</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Estilos para SweetAlert oscuro -->
  <style>
    .swal2-popup {
      background: #020617 !important;               /* slate-950 */
      color: #e5e7eb !important;                    /* slate-200 */
      border-radius: 1rem !important;
      border: 1px solid #1e293b !important;         /* slate-800 */
      box-shadow: 0 24px 70px rgba(0,0,0,0.9) !important;
    }
    .swal2-title {
      color: #f9fafb !important;                    /* slate-50 */
      font-weight: 600 !important;
    }
    .swal2-html-container {
      color: #cbd5f5 !important;                    /* slate-200/indigo mix */
      font-size: 0.9rem !important;
    }
    .swal2-icon {
      box-shadow: none !important;
    }
    .swal2-icon.swal2-error {
      border-color: #f97373 !important;             /* rojo suave */
      color: #fecaca !important;
    }
    .swal2-icon.swal2-success {
      border-color: #4ade80 !important;             /* verde */
      color: #bbf7d0 !important;
    }
    .swal2-styled.swal2-confirm {
      background: #0ea5e9 !important;               /* primary-500 */
      color: #0b1120 !important;                    /* slate-950 */
      border-radius: 9999px !important;
      padding: 0.6rem 1.6rem !important;
      font-weight: 600 !important;
      border: 0 !important;
    }
    .swal2-styled.swal2-cancel {
      background: transparent !important;
      color: #e5e7eb !important;
      border-radius: 9999px !important;
      border: 1px solid #4b5563 !important;         /* slate-600 */
      padding: 0.6rem 1.6rem !important;
      font-weight: 500 !important;
    }
    .swal2-styled:focus {
      box-shadow: 0 0 0 2px rgba(56,189,248,0.5) !important;
    }
  </style>
</head>
<body class="min-h-screen bg-slate-950 text-white flex items-center justify-center relative overflow-hidden">

  <!-- Fondos decorativos -->
  <div class="pointer-events-none absolute -left-40 -top-40 h-80 w-80 bg-blue-600/20 blur-3xl rounded-full"></div>
  <div class="pointer-events-none absolute -right-32 -bottom-40 h-72 w-72 bg-cyan-500/20 blur-3xl rounded-full"></div>

  <!-- Card de login -->
  <div class="relative z-10 w-full max-w-md px-8 py-10 bg-slate-900/90 border border-slate-800 rounded-3xl shadow-[0_24px_70px_rgba(0,0,0,0.9)] backdrop-blur">

    <!-- Logo + título -->
    <div class="flex flex-col items-center text-center mb-8">
      <!-- LOGO: ajusta la ruta y tamaño a tu archivo -->
      <div class="mb-4 flex items-center justify-center h-16 w-16 rounded-2xl bg-slate-800 border border-slate-700 shadow-lg shadow-blue-600/40 overflow-hidden">
        <img src="../img/logo.png" alt="Logo suscripciones" class="h-14 w-14 object-contain">
      </div>

      <h1 class="text-2xl font-semibold tracking-tight">Iniciar sesión</h1>
      <p class="text-sm text-slate-400 mt-1">
        Accede al panel de suscripciones
      </p>
    </div>

    <!-- Formulario -->
    <form id="loginForm" class="space-y-5">
      <!-- Correo -->
      <div>
        <label for="correo" class="block text-xs font-medium text-slate-300 mb-1">
          Correo electrónico
        </label>
        <input
          type="email"
          id="correo"
          placeholder="tucorreo@dominio.com"
          class="w-full px-3 py-2.5 rounded-xl bg-slate-900/70 border border-slate-700 text-sm
                 placeholder:text-slate-500
                 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40
                 transition"
          required
        />
      </div>

      <!-- Contraseña -->
      <div>
        <label for="password" class="block text-xs font-medium text-slate-300 mb-1">
          Contraseña
        </label>
        <input
          type="password"
          id="password"
          placeholder="••••••••"
          class="w-full px-3 py-2.5 rounded-xl bg-slate-900/70 border border-slate-700 text-sm
                 placeholder:text-slate-500
                 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40
                 transition"
          required
        />
      </div>

      <!-- Botón -->
      <button
        type="submit"
        class="w-full mt-2 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500
               text-sm font-semibold tracking-wide
               shadow-lg shadow-blue-600/40
               transition-transform transform hover:-translate-y-0.5"
      >
        Entrar
      </button>
    </form>

    <!-- Footer pequeño -->
    <div class="mt-6 text-[11px] text-center text-slate-500">
      Panel de suscripciones · BBS Networks
    </div>
  </div>

  <!-- JS del login -->
  <script src="../js/login.js"></script>
    <!-- Config Tailwind (animaciones y colores extra) -->
</body>
</html>

