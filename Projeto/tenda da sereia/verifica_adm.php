<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'adm') {
    header("Location: index.php");
    exit;
}
?>
