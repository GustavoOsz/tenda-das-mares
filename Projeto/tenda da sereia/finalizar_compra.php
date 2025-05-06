<?php
session_start();

// Aqui futuramente você pode salvar o pedido no banco, gerar nota, enviar e-mail etc.
// Por enquanto, só limpa o carrinho.

unset($_SESSION['carrinho']);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Compra Finalizada | Tenda da Sereia</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-[#4b2e1a] to-[#2b1a10] text-white min-h-screen">
  <?php include 'navbar.php'; ?>

  <div class="container mx-auto py-20 text-center">
    <h1 class="text-4xl font-bold text-yellow-400 mb-6">Compra finalizada com sucesso!</h1>
    <p class="text-lg text-gray-300 mb-10">Seu pedido foi processado. Agradecemos pela preferência!</p>
    <a href="index.php" class="bg-yellow-400 text-black font-bold px-6 py-3 rounded-xl hover:bg-yellow-500 transition-transform hover:scale-105">
      Voltar à Página Inicial
    </a>
  </div>
</body>
</html>
