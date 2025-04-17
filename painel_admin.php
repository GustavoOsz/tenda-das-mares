<?php
session_start();

// Verificação de login e se é admin
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Painel Admin | Tenda da Sereia</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-[#4b2e1a] to-[#1e120a] text-white min-h-screen">
  
  <?php include 'navbar.php'; ?>

  <div class="container mx-auto py-12 px-4">
    <h1 class="text-4xl font-bold text-yellow-400 text-center mb-10">Painel Administrativo</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      
      <!-- Gerenciar Produtos -->
      <a href="gerenciar_produtos.php" class="bg-white/10 border border-yellow-400 hover:bg-white/20 transition rounded-xl p-6 text-center shadow-lg">
        <h2 class="text-2xl font-semibold text-yellow-300 mb-2">📦 Produtos</h2>
        <p>Adicionar, editar e remover produtos do catálogo.</p>
      </a>

      <!-- Gerenciar Usuários -->
      <a href="gerenciar_usuarios.php" class="bg-white/10 border border-yellow-400 hover:bg-white/20 transition rounded-xl p-6 text-center shadow-lg">
        <h2 class="text-2xl font-semibold text-yellow-300 mb-2">👥 Usuários</h2>
        <p>Visualize e gerencie todos os usuários do sistema.</p>
      </a>

      <!-- Histórico de Compras -->
      <a href="historico_compras_admin.php" class="bg-white/10 border border-yellow-400 hover:bg-white/20 transition rounded-xl p-6 text-center shadow-lg">
        <h2 class="text-2xl font-semibold text-yellow-300 mb-2">🧾 Histórico</h2>
        <p>Acompanhe todas as compras realizadas na plataforma.</p>
      </a>

    </div>
  </div>
</body>
</html>
