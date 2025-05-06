<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('127.0.0.1', 'adm', '12345', 'tenda_sereia');
if ($conn->connect_error) die("Erro: " . $conn->connect_error);

$id_usuario = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_nome = $_POST['nome'];
    $novo_email = $_POST['email'];

    $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $novo_nome, $novo_email, $id_usuario);
    $stmt->execute();
    header("Location: perfil.php");
    exit();
}

$stmt = $conn->prepare("SELECT nome, email FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Editar Perfil</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-900 text-white min-h-screen p-6">
  <div class="max-w-xl mx-auto bg-white/10 p-6 rounded-xl">
    <h1 class="text-2xl font-bold text-yellow-300 mb-4">Editar Perfil</h1>
    <form method="POST">
      <label class="block mb-2">Nome:
        <input type="text" name="nome" value="<?= $usuario['nome'] ?>" required class="w-full rounded p-2 bg-zinc-800 text-white">
      </label>
      <label class="block mb-4">Email:
        <input type="email" name="email" value="<?= $usuario['email'] ?>" required class="w-full rounded p-2 bg-zinc-800 text-white">
      </label>
      <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded">Salvar</button>
    </form>
  </div>
</body>
</html>
