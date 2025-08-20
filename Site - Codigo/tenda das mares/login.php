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
        // Salva na sessão tanto o ID quanto o tipo
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_tipo'] = $usuario['tipo']; // <-- importante
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
    // 👆 define que novos cadastros entram como 'user'
    $stmt->bind_param("ss", $email, $senha);

    if ($stmt->execute()) {
        $sucesso = "Cadastro realizado! Agora você pode fazer login.";
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
</head>
<body class="bg-white text-[#4f2905] font-sans">

    <!-- Navbar -->
    <header class="bg-[#f5f1e6] flex justify-between items-center px-6 py-4">
          <div>
             <a href="index.php"><img src="img/logo.png" alt="Logo" class="h-10"></a>
          </div>
        <nav class="flex gap-6 text-lg font-medium">
            <a href="produtos.php" class="hover:text-[#b85e2b]">Produtos</a>
            <a href="pesquisas.php" class="hover:text-[#b85e2b]">Blog</a>
            <a href="#" class="hover:text-[#b85e2b]">Sobre nós</a>
            <a href="#" class="hover:text-[#b85e2b]">Contato</a>
        </nav>
    </header>

    <!-- Conteúdo -->
    <main class="flex justify-center items-center min-h-screen gap-12 px-6">
        
        <!-- Login -->
        <form method="POST" class="bg-[#fde9c7] p-8 rounded-2xl shadow-md w-80 flex flex-col gap-4">
            <h2 class="text-xl font-semibold mb-2">Login</h2>
            
            <input type="email" name="email" placeholder="Email" required
                class="px-4 py-2 rounded bg-[#e2c9a4] focus:outline-none">
            
            <input type="password" name="senha" placeholder="Senha" required
                class="px-4 py-2 rounded bg-[#e2c9a4] focus:outline-none">
            
            <button type="submit" name="login"
                class="bg-[#fbc97f] hover:bg-[#f7b95e] py-2 rounded font-medium">Login</button>
        </form>

        <!-- Cadastro -->
        <form method="POST" class="bg-[#fde9c7] p-8 rounded-2xl shadow-md w-80 flex flex-col gap-4">
            <h2 class="text-xl font-semibold mb-2">Cadastrar</h2>
            
            <input type="email" name="email" placeholder="Email" required
                class="px-4 py-2 rounded bg-[#e2c9a4] focus:outline-none">
            
            <input type="password" name="senha" placeholder="Senha" required
                class="px-4 py-2 rounded bg-[#e2c9a4] focus:outline-none">
            
            <button type="submit" name="cadastrar"
                class="bg-[#fbc97f] hover:bg-[#f7b95e] py-2 rounded font-medium">Cadastrar</button>
        </form>
    </main>

    <!-- Mensagens -->
    <div class="text-center mt-6">
        <?php if (isset($erro)) echo "<p class='text-red-600 font-medium'>$erro</p>"; ?>
        <?php if (isset($sucesso)) echo "<p class='text-green-600 font-medium'>$sucesso</p>"; ?>
    </div>

</body>
</html>
