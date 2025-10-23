<?php 
session_start(); 
$conn = new mysqli("localhost", "root", "", "tenda"); 

if ($conn->connect_error) { 
    die("Erro na conexão: " . $conn->connect_error); 
} 

$id = isset($_GET['id']) ? intval($_GET['id']) : 0; 
if ($id <= 0) { 
    die("Produto inválido."); 
} 

$stmt = $conn->prepare("SELECT id, nome, preco, imagem, descricao FROM produto WHERE id = ?"); 
$stmt->bind_param("i", $id); 
$stmt->execute(); 
$produto = $stmt->get_result()->fetch_assoc(); 

if (!$produto) { 
    die("Produto não encontrado."); 
} 
?> 

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($produto['nome']) ?> - Tenda das Marés</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-[#4f2905] font-sans">

    <!-- Navbar -->
    <header class="bg-[#f5f1e6] flex justify-between items-center px-6 py-4">
        <div>
            <a href="../index.php">
                <img src="../img/logo.png" alt="Logo" class="h-10">
            </a>
        </div>
        <nav class="flex gap-6 text-lg font-medium">
            <a href="../produtos.php" class="hover:text-[#b85e2b]">Produtos</a>
            <a href="../pesquisas.php" class="hover:text-[#b85e2b]">Blog</a>
            <a href="sobre.php" class="hover:text-[#b85e2b]">Sobre nós</a>
            <a href="../contato.php" class="hover:text-[#b85e2b]">Contato</a>
            <a href="../login.php" class="hover:text-[#b85e2b]">Login</a>
        </nav>
    </header>

    <!-- Detalhes do Produto -->
    <main class="grid grid-cols-1 lg:grid-cols-3 gap-6 px-6 py-8 max-w-7xl mx-auto">

        <!-- Imagem principal + miniaturas -->
        <div class="lg:col-span-2">
            <div class="bg-[#fde9c7] rounded-xl p-4 mb-4">
                <img src="<?= '../' . htmlspecialchars($produto['imagem']) ?>" 
                     alt="<?= htmlspecialchars($produto['nome']) ?>" 
                     class="w-full h-96 object-contain rounded bg-white">
            </div>

            <div class="flex gap-3 overflow-x-auto">
                <?php for ($i = 0; $i < 4; $i++): ?>
                    <div class="w-24 h-24 bg-[#fddda5] rounded shadow-sm flex-shrink-0"></div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Informações e ações -->
        <div class="bg-[#fde9c7] rounded-xl p-6 flex flex-col justify-between shadow-md">
            <div>
                <h1 class="text-2xl font-semibold mb-2"><?= htmlspecialchars($produto['nome']) ?></h1>
                <p class="text-xl font-bold mb-4">
                    R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                </p>

                <!-- Envia para o carrinho via POST -->
                <form method="POST" action="carrinho.php" class="flex flex-col gap-4">
                    <input type="hidden" name="id" value="<?= $produto['id']; ?>">
                    <input type="hidden" name="nome" value="<?= htmlspecialchars($produto['nome']); ?>">
                    <input type="hidden" name="preco" value="<?= $produto['preco']; ?>">
                    <input type="hidden" name="imagem" value="<?= htmlspecialchars($produto['imagem']); ?>">

                    <label for="quantidade" class="text-sm font-medium">Quantidade:</label>
                    <input type="number" name="quantidade" value="1" min="1" 
                           class="border border-[#4f2905] rounded px-2 py-1 text-sm w-24">

                    <button type="submit" 
                            class="bg-[#4f2905] hover:bg-[#623613] text-white py-2 rounded text-sm">
                        Adicionar ao carrinho
                    </button>
                </form>
            </div>

            <!-- Botão de exclusão (somente admin) -->
            <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'admin'): ?>
                <form method="POST" action="excluir_produto.php" 
                      onsubmit="return confirm('Tem certeza que deseja excluir este produto?');" 
                      class="mt-4">
                    <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded text-sm">
                        Excluir Produto
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <!-- Informações adicionais -->
        <div class="lg:col-span-3 mt-6">
            <div class="bg-[#fddda5] rounded-xl p-6 shadow">
                <h2 class="text-lg font-semibold mb-2">Informações:</h2>
                <p class="text-sm">
                    <?= !empty($produto['descricao']) 
                        ? nl2br(htmlspecialchars($produto['descricao'])) 
                        : "Produto artesanal selecionado com carinho, perfeito para seu altar ou presente especial." ?>
                </p>
            </div>
        </div>

    </main>
</body>
</html>
