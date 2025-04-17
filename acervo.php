<?php
session_start();
$host = '127.0.0.1';
$user = 'adm';
$password = '12345';
$database = 'tenda_sereia';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$sql = "SELECT * FROM produtos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Acervo | Tenda da Sereia</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-gradient-to-b from-[#4b2e1a] to-[#2b1a10] text-white min-h-screen">

  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

  <div class="container mx-auto py-12 px-4">
    <h1 class="text-4xl font-bold text-center mb-10 text-yellow-400">Nosso Acervo</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <!-- Cada produto agora é envolvido por um link que leva à página de detalhes -->
          <a href="acervo-tools/produto_detalhes.php" class="bg-white/10 border border-yellow-300 rounded-xl p-4 shadow-md hover:scale-105 transition-transform text-white relative">
            <img src="<?php echo $row['imagem']; ?>" alt="<?php echo $row['nome']; ?>" class="w-full h-48 object-cover rounded mb-4">
            <h2 class="text-xl font-semibold text-yellow-300"><?php echo $row['nome']; ?></h2>
            <p class="text-sm mb-2"><?php echo $row['descricao']; ?></p>
            <p class="font-bold text-yellow-400">R$ <?php echo number_format($row['preco'], 2, ',', '.'); ?></p>
          </a>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center text-gray-400 col-span-full">Nenhum produto encontrado no acervo.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Botão para adicionar produto -->
  <?php if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin'): ?>
    <a href="admin_add_produto.php" id="inserir-produto-btn" class="fixed bottom-5 right-5 bg-yellow-400 text-black font-bold px-6 py-3 rounded-xl hover:bg-yellow-500 transition-transform hover:scale-105 shadow-md z-50">
      Inserir Produto
    </a>
  <?php endif; ?>
  
  <style>
    /* Estilo para o botão fixo */
    #inserir-produto-btn {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 50;
    }
  </style>

</body>
</html>

<?php $conn->close(); ?>

