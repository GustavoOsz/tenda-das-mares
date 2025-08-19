<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "tenda"); // Altere o nome do banco se necessário

// Verifica conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Lógica de pesquisa
$pesquisa = isset($_GET['pesquisa']) ? $conn->real_escape_string($_GET['pesquisa']) : "";
$sql = "SELECT * FROM produto WHERE nome LIKE '%$pesquisa%' ORDER BY id DESC";
$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Produtos - Tenda das Marés</title>
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
            <a href="#" class="hover:text-[#b85e2b]">Blog</a>
            <a href="#" class="hover:text-[#b85e2b]">Sobre nós</a>
            <a href="#" class="hover:text-[#b85e2b]">Contato</a>
            <a href="login.php">
                <svg class="h-6 w-6 text-[#4f2905]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A4.992 4.992 0 0112 20a4.992 4.992 0 016.879-2.196M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </a>
        </nav>
    </header>

    <!-- Pesquisa -->
<div class="flex justify-end px-6 py-4">
    <input type="text" id="pesquisa" placeholder="Buscar..." class="border-b-2 border-[#4f2905] focus:outline-none px-2 py-1 text-sm">
</div>

<!-- Produtos -->
<section class="px-6 pb-12">
    <div id="resultado" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php
        // Mostra todos os produtos inicialmente
        $sql = "SELECT * FROM produto ORDER BY id DESC";
        $res = $conn->query($sql);
        if ($res->num_rows > 0) {
            while($produto = $res->fetch_assoc()) {
                echo '
                <a href="produtos-clas/detalhes.php?id='.$produto['id'].'" class="bg-[#fde9c7] rounded-xl shadow-md p-4 flex flex-col items-center text-center hover:shadow-lg transition no-underline text-[#4f2905]">
                    <img src="'.$produto['imagem'].'" alt="'.$produto['nome'].'" class="w-full h-40 object-cover rounded mb-2">
                    <h4 class="text-lg font-semibold mb-1">'.$produto['nome'].'</h4>
                    <p class="text-sm mb-2">'.$produto['descricao'].'</p>
                    <span class="text-[#4f2905] font-bold mb-2">R$ '.number_format($produto['preco'], 2, ',', '.').'</span>
                </a>
                ';
            }
        }
        ?>
    </div>
</section>

<script>
const input = document.getElementById('pesquisa');
const resultado = document.getElementById('resultado');

input.addEventListener('input', () => {
    const pesquisa = input.value;

    fetch('produtos-clas/buscar_produtos.php?pesquisa=' + encodeURIComponent(pesquisa))
        .then(res => res.text())
        .then(html => {
            resultado.innerHTML = html;
        });
});
</script>
</body>
</html>
