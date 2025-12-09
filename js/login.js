document.getElementById("loginForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const correo = document.getElementById("correo").value.trim();
  const password = document.getElementById("password").value;

  Swal.showLoading();

  fetch("../php/login.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ correo, password })
  })
    .then(res => res.json())
    .then(data => {
      Swal.close();
      if (data.success) {
        Swal.fire("Bienvenido", data.message, "success").then(() => {
          window.location.href = "../index.php"; // o dashboard.php
        });
      } else {
        Swal.fire("Error", data.message, "error");
      }
    })
    .catch(err => {
      Swal.close();
      Swal.fire("Error", err.message, "error");
    });
});
