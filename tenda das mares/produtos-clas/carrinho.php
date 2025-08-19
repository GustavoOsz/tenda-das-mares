<?php
session_start();

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Remover quantidade específica
if (isset($_GET['remover_quantidade']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $qtdRemover = (int)$_GET['remover_quantidade'];

    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]['quantidade'] -= $qtdRemover;

        // Remove completamente se a quantidade chegar a 0 ou menos
        if ($_SESSION['carrinho'][$id]['quantidade'] <= 0) {
            unset($_SESSION['carrinho'][$id]);
        }
    }
    header("Location: carrinho.php");
    exit;
}

// Adicionar itens ao carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $imagem = $_POST['imagem'];
    $quantidade = $_POST['quantidade'];

    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]['quantidade'] += $quantidade;
    } else {
        $_SESSION['carrinho'][$id] = [
            'id' => $id,
            'nome' => $nome,
            'preco' => $preco,
            'imagem' => $imagem,
            'quantidade' => $quantidade
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-[#4f2905] font-sans">

<!-- Navbar -->
    <header class="bg-[#f5f1e6] flex justify-between items-center px-6 py-4">
        <div>
            <a href="../index.php"><img src="../img/logo.png" alt="Logo" class="h-10"></a>
        </div>
        <nav class="flex gap-6 text-lg font-medium">
            <a href="../produtos.php" class="hover:text-[#b85e2b]">Produtos</a>
            <a href="../" class="hover:text-[#b85e2b]">Blog</a>
            <a href="../" class="hover:text-[#b85e2b]">Sobre nós</a>
            <a href="../" class="hover:text-[#b85e2b]">Contato</a>
            <a href="../login.php">
                <svg class="h-6 w-6 text-[#4f2905]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A4.992 4.992 0 0112 20a4.992 4.992 0 016.879-2.196M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </a>
        </nav>
    </header>

    <!-- Carrinho -->
    <div class="max-w-5xl mx-auto mt-10 p-8 bg-[#fde9c7] rounded-2xl shadow">
        <h1 class="text-2xl font-bold mb-6">🛒 Seu Carrinho</h1>

        <?php if (empty($_SESSION['carrinho'])): ?>
            <p class="text-gray-500">Seu carrinho está vazio.</p>
        <?php else: ?>
            <div class="space-y-4">
                <?php
                $total = 0;
                foreach ($_SESSION['carrinho'] as $item):
                    $subtotal = $item['preco'] * $item['quantidade'];
                    $total += $subtotal;
                ?>
                <div class="flex items-center bg-[#f5f1e6] p-4 rounded-lg shadow">
    <img src="../imagens/<?php echo $item['imagem']; ?>" alt="<?php echo $item['nome']; ?>" class="w-20 h-20 object-cover rounded">
    <div class="ml-4 flex-1">
        <h2 class="text-lg font-semibold"><?php echo $item['nome']; ?></h2>
        <p>Preço: R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></p>
        <p>Quantidade: <?php echo $item['quantidade']; ?></p>
        <p class="font-bold">Subtotal: R$ <?php echo number_format($item['preco'] * $item['quantidade'], 2, ',', '.'); ?></p>
    </div>
    
    <!-- Form para remover quantidade -->
    <form method="get" action="carrinho.php" class="flex items-center gap-2">
        <input type="number" name="remover_quantidade" min="1" max="<?php echo $item['quantidade']; ?>" value="1" class="w-16 p-1 border rounded">
        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
        <button type="submit" class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600">
            Remover
        </button>
    </form>
</div>
                <?php endforeach; ?>
            </div>

            <div class="mt-6 bg-white p-4 rounded-lg shadow flex justify-between items-center">
                <h2 class="text-xl font-bold">Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></h2>
                <button class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600">
                    Finalizar Compra
                </button>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
