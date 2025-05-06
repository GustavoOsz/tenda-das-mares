<?php
// Conexão com o banco de dados
$host = '127.0.0.1';
$user = 'adm';
$password = '12345';
$database = 'tenda_sereia';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Inicia a sessão
session_start();

// Inicializa a variável de busca
$busca = isset($_POST['busca']) ? $_POST['busca'] : '';

// Consulta os produtos com base na busca
$sql = "SELECT * FROM produtos WHERE nome LIKE ?";
$stmt = $conn->prepare($sql);
$likeBusca = "%$busca%";
$stmt->bind_param("s", $likeBusca);
$stmt->execute();
$result = $stmt->get_result();

// Verificando se o usuário está logado corretamente
if (isset($_SESSION['id_usuario']) && isset($_SESSION['tipo_usuario'])) {
    // Exibe as informações do usuário para depuração
    $usuario = [
        'id_usuario' => $_SESSION['id_usuario'],
        'tipo_usuario' => $_SESSION['tipo_usuario']
    ];
} else {
    $usuario = null;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Acervo | Tenda da Sereia</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-gradient-to-b from-[#4b2e1a] to-[#2b1a10] text-white min-h-screen">

  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

  <div class="container mx-auto py-12 px-4">
    <h1 class="text-4xl font-bold text-center mb-10 text-yellow-400">Nosso Acervo</h1>

    <!-- Barra de pesquisa -->
    <div class="mb-6 flex justify-center">
      <input 
        type="text" 
        id="pesquisa-produto" 
        class="p-3 rounded-lg bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400 w-1/2" 
        placeholder="Pesquisar produto..." 
        value="<?php echo $busca; ?>"
      >
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8" id="produtos">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <!-- Link para a página de detalhes do produto -->
          <a href="produto_detalhes.php?id=<?php echo $row['id']; ?>" class="bg-white/10 border border-yellow-300 rounded-xl p-4 shadow-md hover:scale-105 transition-transform text-white">
            <div>
              <img src="<?php echo $row['imagem']; ?>" alt="<?php echo $row['nome']; ?>" class="w-full h-48 object-cover rounded mb-4">
              <h2 class="text-xl font-semibold text-yellow-300"><?php echo $row['nome']; ?></h2>
              <p class="text-sm mb-2"><?php echo $row['descricao']; ?></p>
              <p class="font-bold text-yellow-400">R$ <?php echo number_format($row['preco'], 2, ',', '.'); ?></p>
            </div>
          </a>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center text-gray-400 col-span-full">Nenhum produto encontrado no acervo.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Botão para adicionar produto -->
  <?php if ($usuario && $usuario['tipo_usuario'] === 'admin'): ?>
    <a href="admin_add_produto.php" id="inserir-produto-btn" class="fixed bottom-5 right-5 bg-yellow-400 text-black font-bold px-6 py-3 rounded-xl hover:bg-yellow-500 transition-transform hover:scale-105 shadow-md z-50">
      Inserir Produto
    </a>
  <?php endif; ?>

  <script>
    // Função para filtrar os produtos via AJAX
    $(document).ready(function() {
      $('#pesquisa-produto').on('keyup', function() {
        var pesquisa = $(this).val(); // Pega o valor da pesquisa

        $.ajax({
          url: 'acervo.php', // Recarrega a página do acervo
          type: 'POST',
          data: { busca: pesquisa },
          success: function(response) {
            $('#produtos').html($(response).find('#produtos').html()); // Atualiza os produtos filtrados
          }
        });
      });
    });
  </script>
</body>
</html>

<?php $conn->close(); ?>
