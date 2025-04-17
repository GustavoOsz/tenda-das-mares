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

// Verifica se existe carrinho
$carrinho = isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];

$produtos = [];
$total = 0;

if (!empty($carrinho)) {
    foreach ($carrinho as $item) {
        $sql = "SELECT * FROM produtos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $item['id']);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            $produto = $resultado->fetch_assoc();
            $produto['quantidade'] = $item['quantidade'];
            $produto['subtotal'] = $produto['preco'] * $item['quantidade'];
            $produtos[] = $produto;
            $total += $produto['subtotal'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Carrinho | Tenda da Sereia</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-[#4b2e1a] to-[#2b1a10] text-white min-h-screen">
  <?php include 'navbar.php'; ?>

  <div class="container mx-auto py-12 px-4">
    <h1 class="text-4xl font-bold text-center mb-10 text-yellow-400">Seu Carrinho</h1>

    <?php if (empty($produtos)): ?>
      <p class="text-center text-gray-400">Seu carrinho está vazio.</p>
    <?php else: ?>
      <div class="space-y-6">
        <?php foreach ($produtos as $p): ?>
          <div class="bg-white/10 border border-yellow-300 rounded-xl p-4 shadow-md flex items-center gap-6">
            <img src="<?php echo $p['imagem']; ?>" alt="<?php echo $p['nome']; ?>" class="w-24 h-24 object-cover rounded">
            <div class="flex-1">
              <h2 class="text-xl font-semibold text-yellow-300"><?php echo $p['nome']; ?></h2>
              <p class="text-sm"><?php echo $p['descricao']; ?></p>
              <p class="font-bold text-yellow-400 mt-2">R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?> x <?php echo $p['quantidade']; ?></p>
              <p class="text-yellow-200">Subtotal: R$ <?php echo number_format($p['subtotal'], 2, ',', '.'); ?></p>
            </div>
            <form action="remover_do_carrinho.php" method="POST">
              <input type="hidden" name="id_produto" value="<?php echo $p['id']; ?>">
              <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Remover</button>
            </form>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="mt-10 text-right">
        <h3 class="text-2xl font-bold text-yellow-300">Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></h3>
        <a href="finalizar_compra.php" class="inline-block mt-4 bg-green-500 text-white font-bold px-6 py-3 rounded-xl hover:bg-green-600 transition-all">
          Finalizar Compra
        </a>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>

<?php $conn->close(); ?>
