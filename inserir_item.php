<?php
include 'verifica_adm.php';
include 'conexao.php';

$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$preco = $_POST['preco'];

$sql = "INSERT INTO produtos (nome, descricao, preco) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssd", $nome, $descricao, $preco);
$stmt->execute();

header("Location: painel_adm.php?sucesso=1");
exit;
?>
