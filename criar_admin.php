<?php
// Conexão com o banco de dados
$conn = new mysqli('127.0.0.1', 'adm', '12345', 'tenda_sereia');
if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

// Dados do novo admin
$nome = "Gustavo Admin";
$email = "admin@email.com";
$senha = password_hash("123456", PASSWORD_DEFAULT); // Criptografa a senha
$tipo = "admin"; // Tipo de usuário

// Verificando se o usuário admin já existe
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    // Se o usuário não existir, insira no banco
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $senha, $tipo);

    if ($stmt->execute()) {
        echo "Usuário admin criado com sucesso!";
    } else {
        echo "Erro: " . $stmt->error;
    }
} else {
    echo "O usuário admin já existe.";
}

$stmt->close();
$conn->close();
?>
