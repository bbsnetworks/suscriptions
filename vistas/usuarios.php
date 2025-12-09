<?php
require_once '../php/verificar_sesion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Usuarios</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-900 text-gray-200 font-sans min-h-screen">

  <!-- Navbar lateral -->
  <?php include "../includes/navbar.php"; ?>

  <div class="ml-64 p-6"> <!-- Deja espacio para el sidebar -->
    <h1 class="text-3xl font-bold mb-6 text-white">Administrar Usuarios</h1>

    <button id="btnAgregarUsuario" class="mb-6 px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">+ Agregar Usuario</button>

    <div class="overflow-x-auto bg-gray-800 rounded-lg shadow">
      <table class="w-full table-auto text-left" id="tablaUsuarios">
        <thead class="bg-gray-700 text-gray-300">
          <tr>
            <th class="px-4 py-3">ID</th>
            <th class="px-4 py-3">Nombre</th>
            <th class="px-4 py-3">Correo</th>
            <th class="px-4 py-3">Fecha</th>
            <th class="px-4 py-3">Acciones</th>
          </tr>
        </thead>
        <tbody class="text-gray-100 divide-y divide-gray-700"></tbody>
      </table>
    </div>
  </div>

  <script src="../js/usuarios.js"></script>
</body>
</html>

