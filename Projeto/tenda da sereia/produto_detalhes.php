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

// Verifique a sessão de tipo de usuário
echo "Sessão tipo_usuario: " . (isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : 'não definido') . "<br>";

// REMOÇÃO DO PRODUTO (antes de buscar os dados, para evitar erro após remoção)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remover_produto'])) {
    // Verifica se o usuário está logado e é admin
    if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin') {
        $id_para_remover = $_POST['id_produto'];
        $stmt_remover = $conn->prepare("DELETE FROM produtos WHERE id = ?");
        $stmt_remover->bind_param("i", $id_para_remover);
        $stmt_remover->execute();

        // Redireciona para o acervo após remover
        header("Location: acervo.php");
        exit;
    } else {
        die("Você não tem permissão para remover este produto.");
    }
}

// BUSCA OS DETALHES DO PRODUTO
if (isset($_GET['id'])) {
    $id_produto = $_GET['id'];
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

  <?php include 'navbar.php'; ?>

  <div class="container mx-auto py-12 px-4">
    <h1 class="text-4xl font-bold text-center mb-10 text-yellow-400"><?php echo $produto['nome']; ?></h1>

    <div class="flex flex-col md:flex-row gap-8">
      <div class="flex flex-col md:w-1/2">
        <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>" class="w-full h-96 object-cover rounded mb-4">
        <div class="flex gap-4">
          <img src="<?php echo $produto['imagem']; ?>" alt="miniatura" class="w-24 h-24 object-cover rounded">
          <img src="<?php echo $produto['imagem']; ?>" alt="miniatura" class="w-24 h-24 object-cover rounded">
        </div>
      </div>

      <div class="md:w-1/2">
        <h2 class="text-2xl font-semibold text-yellow-300">Descrição</h2>
        <p class="text-sm mb-4"><?php echo $produto['descricao']; ?></p>
        <h3 class="text-xl font-bold text-yellow-400">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></h3>

        <form action="adicionar_ao_carrinho.php" method="POST" class="mt-6">
          <input type="hidden" name="id_produto" value="<?php echo $produto['id']; ?>">
          <button type="submit" class="bg-yellow-400 text-black font-bold px-6 py-3 rounded-xl hover:bg-yellow-500 transition-transform hover:scale-105 shadow-md w-full">
            Adicionar ao Carrinho
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Botão para remover o produto, visível apenas para administradores -->
  <?php
    if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin'): 
  ?>
    <form method="POST" onsubmit="return confirm('Tem certeza que deseja remover este produto?');">
      <input type="hidden" name="id_produto" value="<?php echo $produto['id']; ?>">
      <input type="hidden" name="remover_produto" value="1">
      <button type="submit"
              class="fixed bottom-5 right-5 bg-red-600 text-white font-bold px-5 py-3 rounded-xl shadow-lg hover:bg-red-700 transition-all z-50">
        Remover Produto
      </button>
    </form>
  <?php else: ?>
    <p>O botão de remover não está visível para você, pois você não é um administrador.</p>
  <?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
