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
// Conexão com o banco
$host = '127.0.0.1';
$user = 'adm';
$password = '12345';
$database = 'tenda_sereia';
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$preco = $_POST['preco'];
$imagem = $_POST['imagem'];

$sql = "INSERT INTO produtos (nome, descricao, preco, imagem) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssds", $nome, $descricao, $preco, $imagem);

if ($stmt->execute()) {
    echo "success";  // Retorna sucesso para o AJAX
} else {
    echo "error";  // Retorna erro para o AJAX
}

$stmt->close();
$conn->close();
?>