<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Verifica se é admin
if ($_SESSION['tipo_usuario'] !== 'admin') {
    echo "Acesso negado.";
    exit();
}
?>

<?php
$host = '127.0.0.1';
$user = 'adm'; // Substitua com o usuário correto
$password = '12345'; // Substitua com a senha correta
$database = 'tenda_sereia';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

?>