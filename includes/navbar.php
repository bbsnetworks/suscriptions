<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/suscriptions/');
}
?>

<aside id="sidebar"
  class="fixed top-0 left-0 w-64 h-full 
         bg-slate-950 border-r border-slate-800/70 
         shadow-[0_0_40px_rgba(0,0,0,0.5)]
         flex flex-col z-50
         animate-[fadeIn_0.4s_ease-out]">

  <!-- LOGO -->
  <div class="flex items-center justify-center h-20 border-b border-slate-800/70">
    <img src="<?= BASE_URL ?>img/logo.png" class="h-12" alt="Logo">
  </div>

  <!-- NAVIGATION -->
  <nav class="px-4 pt-6 flex-1 space-y-1 font-medium text-sm">

    <!-- DASHBOARD -->
    <a href="<?= BASE_URL ?>index.php"
       class="nav-item flex items-center gap-3 px-4 py-2.5 rounded-xl 
              text-slate-300 hover:bg-slate-800 hover:text-white
              transition-all duration-150 group">
      <svg class="w-5 h-5 text-slate-400 group-hover:text-white"
           viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="1.8"
           stroke-linecap="round" stroke-linejoin="round">
        <!-- icono tipo layout/dashboard -->
        <rect x="3" y="3" width="8" height="8" rx="2" />
        <rect x="13" y="3" width="8" height="5" rx="2" />
        <rect x="13" y="10" width="8" height="11" rx="2" />
        <rect x="3" y="14" width="8" height="7" rx="2" />
      </svg>
      Dashboard
    </a>

    <!-- USUARIOS -->
    <a href="<?= BASE_URL ?>vistas/usuarios.php"
       class="nav-item flex items-center gap-3 px-4 py-2.5 rounded-xl 
              text-slate-300 hover:bg-slate-800 hover:text-white
              transition-all duration-150 group">
      <svg class="w-5 h-5 text-slate-400 group-hover:text-white"
           viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="1.8"
           stroke-linecap="round" stroke-linejoin="round">
        <!-- icono dos usuarios -->
        <circle cx="9" cy="7.5" r="3" />
        <path d="M3.5 18c.7-2.7 2.7-4 5.5-4s4.8 1.3 5.5 4" />
        <path d="M16.5 6.5a2.6 2.6 0 0 1 0 5.1" />
        <path d="M18.5 17.5c-.3-1.1-.9-1.9-1.8-2.5" />
      </svg>
      Usuarios
    </a>

    <!-- SUSCRIPTORES -->
    <a href="<?= BASE_URL ?>vistas/clientes.php"
       class="nav-item flex items-center gap-3 px-4 py-2.5 rounded-xl 
              text-slate-300 hover:bg-slate-800 hover:text-white
              transition-all duration-150 group">
      <svg class="w-5 h-5 text-slate-400 group-hover:text-white"
           viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="1.8"
           stroke-linecap="round" stroke-linejoin="round">
        <!-- icono tipo credencial -->
        <rect x="3" y="4" width="18" height="16" rx="2" />
        <circle cx="9" cy="11" r="2.4" />
        <path d="M6.5 16.5c.7-1.4 1.9-2.2 3.5-2.2s2.8.8 3.5 2.2" />
        <line x1="14" y1="9" x2="18" y2="9" />
        <line x1="14" y1="12" x2="18" y2="12" />
        <line x1="14" y1="15" x2="18" y2="15" />
      </svg>
      Suscriptores
    </a>

  </nav>

  <!-- LOGOUT -->
  <div class="px-4 pb-6 border-t border-slate-800/70">
    <a href="<?= BASE_URL ?>php/logout.php"
       class="flex items-center gap-3 px-4 py-2.5 rounded-xl 
              text-red-400 hover:bg-slate-800 hover:text-red-300
              transition-all duration-150 group">
      <svg class="w-5 h-5 text-red-500 group-hover:text-red-300"
           viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="1.8"
           stroke-linecap="round" stroke-linejoin="round">
        <!-- icono logout -->
        <path d="M10 5H6.5A1.5 1.5 0 0 0 5 6.5v11A1.5 1.5 0 0 0 6.5 19H10" />
        <path d="M15 8l3 4-3 4" />
        <path d="M18 12H10" />
      </svg>
      Cerrar sesión
    </a>
  </div>
</aside>

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateX(-12px); }
  to   { opacity: 1; transform: translateX(0); }
}
</style>
