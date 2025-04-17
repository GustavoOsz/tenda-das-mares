<?php
include 'verifica_adm.php';
include 'conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Painel Administrativo</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white p-10">
  <h1 class="text-3xl font-bold mb-6">Painel do Administrador</h1>

  <form action="inserir_item.php" method="POST" class="bg-white/10 p-6 rounded-lg max-w-lg space-y-4">
    <input type="text" name="nome" placeholder="Nome do item" class="w-full p-2 rounded text-black" required>
    <textarea name="descricao" placeholder="Descrição" class="w-full p-2 rounded text-black" required></textarea>
    <input type="text" name="preco" placeholder="Preço" class="w-full p-2 rounded text-black" required>
    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 px-4 py-2 rounded font-bold text-black">
      Inserir Item
    </button>
  </form>
</body>
</html>
