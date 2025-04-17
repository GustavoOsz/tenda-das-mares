<?php
session_start();

// Verificar se o usuário está logado e é um administrador
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header('Location: login.php'); // Redirecionar se não for admin
    exit();
}

$conn = new mysqli('127.0.0.1', 'adm', '12345', 'tenda_sereia');
if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Deletar o produto pelo ID
    $stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Produto deletado com sucesso
        header('Location: acervo.php'); // Redirecionar de volta para o acervo
        exit();
    } else {
        echo "Erro ao remover produto.";
    }

    $stmt->close();
} else {
    echo "Produto não encontrado.";
}

$conn->close();
?>
