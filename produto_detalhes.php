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

// Verifica se o ID do produto foi passado via URL
if (isset($_GET['id'])) {
    $id_produto = $_GET['id'];

    // Consulta o produto específico
    $sql = "SELECT * FROM produtos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_produto);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $produto = $resultado->fetch_assoc();
    } else {
        die("Produto não encontrado.");
    }
} else {
    die("ID do produto não especificado.");
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Detalhes do Produto | Tenda da Sereia</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-gradient-to-b from-[#4b2e1a] to-[#2b1a10] text-white min-h-screen">

  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

  <div class="container mx-auto py-12 px-4">
    <h1 class="text-4xl font-bold text-center mb-10 text-yellow-400"><?php echo $produto['nome']; ?></h1>

    <div class="flex flex-col md:flex-row gap-8">
      <!-- Galeria de Imagens -->
      <div class="flex flex-col md:w-1/2">
        <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>" class="w-full h-96 object-cover rounded mb-4">
        <!-- Aqui podemos adicionar mais imagens, se houver -->
        <div class="flex gap-4">
          <!-- Exemplo de imagens adicionais -->
          <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>" class="w-24 h-24 object-cover rounded">
          <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>" class="w-24 h-24 object-cover rounded">
        </div>
      </div>

      <!-- Detalhes do Produto -->
      <div class="md:w-1/2">
        <h2 class="text-2xl font-semibold text-yellow-300">Descrição</h2>
        <p class="text-sm mb-4"><?php echo $produto['descricao']; ?></p>

        <h3 class="text-xl font-bold text-yellow-400">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></h3>

        <!-- Botão Adicionar ao Carrinho -->
        <form action="adicionar_ao_carrinho.php" method="POST" class="mt-6">
          <input type="hidden" name="id_produto" value="<?php echo $produto['id']; ?>">
          <button type="submit" class="bg-yellow-400 text-black font-bold px-6 py-3 rounded-xl hover:bg-yellow-500 transition-transform hover:scale-105 shadow-md w-full">
            Adicionar ao Carrinho
          </button>
        </form>
      </div>
    </div>
  </div>
  <!-- Botão Remover Produto -->
  <?php if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin'): ?>
    <a href="acervo-tools\remover_produto.php=<?php echo $produto['id']; ?>"
   onclick="return confirm('Tem certeza que deseja remover este produto?');"
   class="fixed bottom-5 right-5 bg-red-600 text-white font-bold px-5 py-3 rounded-xl shadow-lg hover:bg-red-700 transition-all z-50">
  Remover Produto
</a>
    <?php endif; ?>
    
    <!-- Botão Voltar para o Acervo -->
    <a href="acervo.php" class="fixed bottom-5 left-5 bg-yellow-400 text-black font-bold px-5 py-3 rounded-xl shadow-lg hover:bg-yellow-500 transition-all z-50">
        Voltar para o Acervo
    </a>
    
    <?php if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin'): ?>
        <style>
        /* Estilo para o botão fixo */
        #inserir-produto-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 50;
        }
        </style>

<?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>