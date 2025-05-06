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
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if (password_verify($senha_atual, $usuario['senha'])) {
        $stmt = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
        $stmt->bind_param("si", $nova_senha, $id_usuario);
        $stmt->execute();
        header("Location: perfil.php");
        exit();
    } else {
        $erro = "Senha atual incorreta.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Alterar Senha</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-900 text-white min-h-screen p-6">
  <div class="max-w-xl mx-auto bg-white/10 p-6 rounded-xl">
    <h1 class="text-2xl font-bold text-yellow-300 mb-4">Alterar Senha</h1>
    <?php if (isset($erro)) echo "<p class='text-red-500'>$erro</p>"; ?>
    <form method="POST">
      <label class="block mb-2">Senha Atual:
        <input type="password" name="senha_atual" required class="w-full rounded p-2 bg-zinc-800 text-white">
      </label>
      <label class="block mb-4">Nova Senha:
        <input type="password" name="nova_senha" required class="w-full rounded p-2 bg-zinc-800 text-white">
      </label>
      <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded">Alterar</button>
    </form>
  </div>
</body>
</html>
