<?php
session_start();

$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/suscriptions/';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ' . $baseUrl . 'vistas/login.php');
    exit;
}



