<?php
session_start();

// Se já estiver logado, redireciona para o perfil
if (isset($_SESSION['id_usuario'])) {
    header("Location: perfil.php");
    exit();
}

$erro = "";

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $conn = new mysqli('127.0.0.1', 'adm', '12345', 'tenda_sereia');

    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }

    // Busca o usuário no banco
    $sql = "SELECT id, senha FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        // Verifica a senha
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['id_usuario'] = $usuario['id'];
            header("Location: perfil.php");
            exit();
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login | Tenda da Sereia</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-[#4b2e1a] to-[#2b1a10] text-white min-h-screen flex items-center justify-center">
 <!-- Navbar -->
 <?php include 'navbar.php'; ?>
  <div class="bg-white/10 border border-yellow-300 rounded-xl p-8 w-full max-w-md shadow-lg">
    <h1 class="text-3xl font-bold text-center text-yellow-400 mb-6">Login</h1>

    <?php if ($erro): ?>
      <p class="text-red-400 text-center mb-4"><?php echo $erro; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-4">
        <label class="block mb-1">Email</label>
        <input type="email" name="email" required class="w-full px-4 py-2 rounded bg-white/20 text-white focus:outline-none">
      </div>

      <div class="mb-6">
        <label class="block mb-1">Senha</label>
        <input type="password" name="senha" required class="w-full px-4 py-2 rounded bg-white/20 text-white focus:outline-none">
      </div>

      <button type="submit" class="w-full bg-yellow-500 text-white font-bold py-2 rounded hover:bg-yellow-600 transition-all">
        Entrar
      </button>
    </form>

    <!-- Botão para Criar Conta -->
    <div class="mt-4 text-center">
      <a href="criar_conta.php" class="text-yellow-400 hover:text-yellow-500 font-semibold">Criar Conta</a>
    </div>
  </div>

</body>
</html>
