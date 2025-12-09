<?php
session_start();

$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/suscriptions/';

session_unset();
session_destroy();

header('Location: ' . $baseUrl . 'vistas/login.php');
exit;
