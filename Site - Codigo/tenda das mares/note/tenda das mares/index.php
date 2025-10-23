<?php
// conexão com o banco
$conn = new mysqli("localhost", "root", "", "tenda");
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// pega os 3 últimos produtos cadastrados
$sql = "SELECT * FROM produto ORDER BY id DESC LIMIT 3";
$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Tenda das Marés</title>
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
            <a href="pesquisas.php" class="hover:text-[#b85e2b]">Pesquisas</a>
            <a href="Sobre.php" class="hover:text-[#b85e2b]">Sobre nós</a>
            <a href="Contato.php" class="hover:text-[#b85e2b]">Contato</a>
            <a href="login.php">
                <svg class="h-6 w-6 text-[#4f2905]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A4.992 4.992 0 0112 20a4.992 4.992 0 016.879-2.196M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </a>
        </nav>
    </header>

    <!-- Hero Section -->
<section class="bg-cover bg-center text-center py-24 text-white" 
         style="background-image: url('img/fundo.png');">
    <h1 class="text-5xl font-serif mb-4">TENDA DAS MARÉS</h1>
    <h2 class="text-xl font-light tracking-widest mb-8">ATELIÊ DE ARTIGOS RELIGIOSOS</h2>
    <a href="produtos.php" class="bg-[#3e230e] hover:bg-[#5a361c] text-white py-2 px-6 rounded-md">Ver produtos</a>
</section>


    <!-- Destaques -->
    <section id="produtos" class="text-center py-12 px-4">
        <h3 class="text-2xl font-medium mb-8">Produtos em Destaque</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 max-w-6xl mx-auto">
            <?php if ($resultado->num_rows > 0): ?>
                <?php while($produto = $resultado->fetch_assoc()): ?>
                    <a href="produtos-clas/detalhes.php?id=<?= $produto['id'] ?>" 
                       class="bg-[#fde9c7] rounded-xl shadow-md hover:shadow-lg transition-all duration-200 p-4 flex flex-col items-center text-center">
                        <img src="<?= $produto['imagem'] ?>" alt="<?= $produto['nome'] ?>" 
                             class="w-full h-96 object-contain rounded bg-white">
                        <h4 class="text-lg font-semibold mb-1"><?= $produto['nome'] ?></h4>
                        <span class="text-[#4f2905] font-bold">
                            R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                        </span>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nenhum produto encontrado.</p>
            <?php endif; ?>
        </div>
    </section>

</body>
</html>