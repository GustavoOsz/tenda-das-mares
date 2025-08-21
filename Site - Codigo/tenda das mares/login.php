<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tenda");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];
        $_SESSION['usuario_email'] = $usuario['email'];

        header("Location: index.php");
        exit;
    } else {
        $erro = "Email ou senha incorretos!";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar'])) {
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (email, senha, tipo) VALUES (?, ?, 'user')"); 
    $stmt->bind_param("ss", $email, $senha);

    if ($stmt->execute()) {
        $sucesso = "Usuário cadastrado com sucesso!";
    } else {
        $erro = "Erro ao cadastrar. Tente outro email.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - Tenda das Marés</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 9999;
        box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        animation: fadein 0.5s, fadeout 0.5s 2.5s;
    }
    .toast.success { background-color: #4CAF50; }
    .toast.error { background-color: #e53935; }
    @keyframes fadein {
        from {opacity: 0; transform: translateY(-10px);}
        to {opacity: 1; transform: translateY(0);}
    }
    @keyframes fadeout {
        from {opacity: 1; transform: translateY(0);}
        to {opacity: 0; transform: translateY(-10px);}
    }
  </style>
</head>
<body class="bg-white text-[#4f2905] font-sans">

  <!-- Navbar -->
  <header class="bg-[#f5f1e6] flex justify-between items-center px-6 py-4 shadow">
      <div>
        <a href="index.php"><img src="img/logo.png" alt="Logo" class="h-10"></a>
      </div>
      <nav class="flex gap-6 text-lg font-medium">
          <a href="produtos.php" class="hover:text-[#b85e2b]">Produtos</a>
          <a href="pesquisas.php" class="hover:text-[#b85e2b]">Pesquisas</a>
          <a href="#" class="hover:text-[#b85e2b]">Sobre nós</a>
          <a href="contato.php" class="hover:text-[#b85e2b]">Contato</a>
      </nav>
  </header>

  <!-- Conteúdo -->
  <main class="flex justify-center items-center min-h-screen px-6">
    <div class="grid md:grid-cols-2 gap-12 w-full max-w-5xl">

      <!-- Login -->
      <form method="POST" 
            class="bg-[#fde9c7] p-10 rounded-2xl shadow-lg flex flex-col gap-5 transition hover:scale-[1.02]">
        <div class="flex items-center gap-2 mb-2">
          <svg class="w-6 h-6 text-[#b85e2b]" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" 
              d="M16 12a4 4 0 11-8 0 4 4 0 018 0zM12 14v7m-6-7v7m12-7v7"/>
          </svg>
          <h2 class="text-2xl font-bold">Login</h2>
        </div>
        
        <input type="email" name="email" placeholder="Digite seu email" required
            class="px-4 py-2 rounded bg-[#e2c9a4] focus:ring-2 focus:ring-[#fbc97f] outline-none">
        
        <input type="password" name="senha" placeholder="Digite sua senha" required
            class="px-4 py-2 rounded bg-[#e2c9a4] focus:ring-2 focus:ring-[#fbc97f] outline-none">
        
        <button type="submit" name="login"
            class="bg-[#fbc97f] hover:bg-[#f7b95e] py-2 rounded-lg font-semibold shadow">
            Entrar
        </button>
      </form>

      <!-- Cadastro -->
      <form method="POST" 
            class="bg-[#fde9c7] p-10 rounded-2xl shadow-lg flex flex-col gap-5 transition hover:scale-[1.02]">
        <div class="flex items-center gap-2 mb-2">
          <svg class="w-6 h-6 text-[#b85e2b]" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" 
              d="M12 4v16m8-8H4"/>
          </svg>
          <h2 class="text-2xl font-bold">Cadastrar</h2>
        </div>
        
        <input type="email" name="email" placeholder="Digite seu email" required
            class="px-4 py-2 rounded bg-[#e2c9a4] focus:ring-2 focus:ring-[#fbc97f] outline-none">
        
        <input type="password" name="senha" placeholder="Crie uma senha" required
            class="px-4 py-2 rounded bg-[#e2c9a4] focus:ring-2 focus:ring-[#fbc97f] outline-none">
        
        <button type="submit" name="cadastrar"
            class="bg-[#fbc97f] hover:bg-[#f7b95e] py-2 rounded-lg font-semibold shadow">
            Criar conta
        </button>
      </form>
    </div>
  </main>
  
  <!-- Mensagem Toast -->
  <?php if (isset($sucesso)): ?>
      <div class="toast success"><?= $sucesso ?></div>
      <script>
          setTimeout(() => {
              document.querySelector('.toast').style.display = 'none';
          }, 3000);
      </script>
  <?php endif; ?>

  <?php if (isset($erro)): ?>
      <div class="toast error"><?= $erro ?></div>
      <script>
          setTimeout(() => {
              document.querySelector('.toast').style.display = 'none';
          }, 3000);
      </script>
  <?php endif; ?>

</body>
</html>
