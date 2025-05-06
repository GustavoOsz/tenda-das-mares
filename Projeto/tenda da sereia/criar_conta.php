<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];

    // Verificar se as senhas coincidem
    if ($senha !== $confirma_senha) {
        $erro = "As senhas não coincidem!";
    } else {
        // Conectar ao banco de dados
        $conn = new mysqli('127.0.0.1', 'adm', '12345', 'tenda_sereia');

        if ($conn->connect_error) {
            die("Erro na conexão: " . $conn->connect_error);
        }

        // Verificar se o email já está registrado
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $erro = "Email já registrado!";
        } else {
            // Inserir o novo usuário
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, 'comum')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $nome, $email, $senha_hash);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $erro = "Erro ao criar conta. Tente novamente.";
            }
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Criar Conta | Tenda da Sereia</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-[#4b2e1a] to-[#2b1a10] text-white min-h-screen flex items-center justify-center">

  <div class="bg-white/10 border border-yellow-300 rounded-xl p-8 w-full max-w-md shadow-lg">
    <h1 class="text-3xl font-bold text-center text-yellow-400 mb-6">Criar Conta</h1>

    <?php if (isset($erro)): ?>
      <p class="text-red-400 text-center mb-4"><?php echo $erro; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-4">
        <label class="block mb-1">Nome</label>
        <input type="text" name="nome" required class="w-full px-4 py-2 rounded bg-white/20 text-white focus:outline-none">
      </div>

      <div class="mb-4">
        <label class="block mb-1">Email</label>
        <input type="email" name="email" required class="w-full px-4 py-2 rounded bg-white/20 text-white focus:outline-none">
      </div>

      <div class="mb-4">
        <label class="block mb-1">Senha</label>
        <input type="password" name="senha" required class="w-full px-4 py-2 rounded bg-white/20 text-white focus:outline-none">
      </div>

      <div class="mb-6">
        <label class="block mb-1">Confirmar Senha</label>
        <input type="password" name="confirma_senha" required class="w-full px-4 py-2 rounded bg-white/20 text-white focus:outline-none">
      </div>

      <button type="submit" class="w-full bg-yellow-500 text-white font-bold py-2 rounded hover:bg-yellow-600 transition-all">
        Criar Conta
      </button>
    </form>

    <div class="mt-4 text-center">
      <a href="login.php" class="text-yellow-400 hover:text-yellow-500 font-semibold">Já tem uma conta? Faça login.</a>
    </div>
  </div>

</body>
</html>
