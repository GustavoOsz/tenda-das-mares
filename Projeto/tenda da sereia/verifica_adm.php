<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
    header("Location: index.php");
    exit;
}
?>
