<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tenda");

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Pega todos os tópicos
$pesquisas = $conn->query("SELECT id, nome FROM pesquisas");

// Verifica se clicaram em algum tópico
$selectedId = isset($_GET['id']) ? intval($_GET['id']) : null;
$selected = null;

if ($selectedId) {
    $stmt = $conn->prepare("SELECT * FROM pesquisas WHERE id = ?");
    $stmt->bind_param("i", $selectedId);
    $stmt->execute();
    $result = $stmt->get_result();
    $selected = $result->fetch_assoc();
}

// Pega o tipo de usuário logado
$usuario_id = $_SESSION['usuario_id'];
$sqlTipo = "SELECT tipo FROM usuarios WHERE id = $usuario_id";
$resTipo = $conn->query($sqlTipo);
$tipoUsuario = $resTipo->fetch_assoc()['tipo'];

//echo "<p class='text-center text-gray-500'>Usuário logado como: <b>$tipoUsuario</b> , , <b>$usuario_id</b> </p>";

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Ensinamentos</title>
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
            <a href="pesquisas.php" class="hover:text-[#b85e2b]">Blog</a>
            <a href="sobre.php" class="hover:text-[#b85e2b]">Sobre nós</a>
            <a href="contato.php" class="hover:text-[#b85e2b]">Contato</a>
            <a href="login.php">
                <svg class="h-6 w-6 text-[#4f2905]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A4.992 4.992 0 0112 20a4.992 4.992 0 016.879-2.196M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </a>
        </nav>
    </header>

  <!-- Conteúdo -->
  <main class="flex-1 grid grid-cols-4 gap-6 p-6">
    
    <!-- Coluna esquerda - tópicos -->
    <aside class="bg-[#fde9c7] p-6 rounded-2xl col-span-1">
      <h2 class="font-bold mb-4 text-lg">Tópicos</h2>
      <ul class="space-y-2">
        <?php while ($row = $pesquisas->fetch_assoc()): ?>
          <li>
            <a href="?id=<?= $row['id'] ?>" 
               class="block hover:text-[#b85e2b] cursor-pointer font-medium">
              <?= htmlspecialchars($row['nome']) ?>
            </a>
          </li>
        <?php endwhile; ?>
      </ul>
    </aside>

    <!-- Coluna central - conteúdo -->
    <section class="bg-white p-6 rounded-2xl shadow col-span-2">
      <?php if ($selected): ?>
        <h2 class="text-xl font-bold mb-2">
          <?= htmlspecialchars($selected['nome']) ?>
        </h2>
        <p class="text-sm text-gray-500 mb-4">
          Publicado em <?= date("d/m/Y", strtotime($selected['data'])) ?>
        </p>
        <p class="leading-relaxed flex-col">
          <?= nl2br(htmlspecialchars($selected['conteudo'])) ?>
        </p>
      <?php else: ?>
        <p class="text-gray-500">Selecione um tópico à esquerda para ver os detalhes.</p>
      <?php endif; ?>
    </section>

    <!-- Coluna direita - imagem -->
    <aside class="bg-[#fde9c7] p-6 rounded-2xl col-span-1 flex items-center justify-center">
      <?php if ($selected && $selected['imagem']): ?>
        <img src="<?= htmlspecialchars($selected['imagem']) ?>" 
             alt="..." 
             class="max-h-64 rounded-lg shadow">
      <?php else: ?>
        <p class="text-gray-500">Nenhuma imagem disponível</p>
      <?php endif; ?>
    </aside>

    <!-- Mostrar botão apenas para admin -->
    <?php if ($tipoUsuario === 'admin'): ?>
        <div class="mb-4">
            <a href="pesquisas-clas/cadastrar_pesquisa.php" 
               class="bg-[#fbc97f] hover:bg-[#f7b95e] px-4 py-2 rounded font-medium shadow">
               + Nova Pesquisa
            </a>
        </div>
    <?php endif; ?>
    
  </main>
</body>
</html>
