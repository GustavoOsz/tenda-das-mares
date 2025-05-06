<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Conectar ao banco de dados
$conn = new mysqli('127.0.0.1', 'adm', '12345', 'tenda_sereia');

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Buscar dados do usuário
$sql = "SELECT nome, email, tipo FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['id_usuario']);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Perfil | Tenda da Sereia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-[#4b2e1a] to-[#2b1a10] text-white min-h-screen">

    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="container mx-auto py-12 px-4">
        <h1 class="text-4xl font-bold text-center mb-10 text-yellow-400">Meu Perfil</h1>

        <div class="bg-white/10 border border-yellow-300 rounded-xl p-6 shadow-md">
            <h2 class="text-2xl font-semibold text-yellow-300 mb-4">Informações Pessoais</h2>
            <p class="text-lg">Nome: <?php echo $usuario['nome']; ?></p>
            <p class="text-lg">Email: <?php echo $usuario['email']; ?></p>

            <?php if ($usuario['tipo'] === 'admin'): ?>
                <p class="text-lg text-yellow-300">Você é um Administrador</p>
            <?php endif; ?>

            <!-- Botões de Ação -->
            <div class="mt-6 space-x-4 text-center">
                <!-- Botão para editar perfil -->
                <a href="editar_perfil.php" class="bg-yellow-500 text-white font-bold py-2 px-4 rounded hover:bg-yellow-600 transition-all">
                    Editar Perfil
                </a>

                <!-- Botão para acessar o painel de admin (se for admin) -->
                <?php if ($usuario['tipo'] === 'admin'): ?>
                    <a href="painel_admin.php" class="bg-yellow-500 text-white font-bold py-2 px-4 rounded hover:bg-yellow-600 transition-all">
                        Painel de Admin
                    </a>
                <?php endif; ?>
            </div>

            <!-- Botão para sair -->
            <div class="mt-4 text-center">
                <a href="logout.php" class="text-yellow-400 hover:text-yellow-500 font-semibold">Sair</a>
            </div>
        </div>

        <!-- Histórico de Compras -->
        <h2 class="text-2xl font-semibold text-yellow-300 mt-6 mb-4">Histórico de Compras</h2>
        <?php
        $sql_compras = "SELECT p.nome, hc.quantidade, hc.data_compra 
                        FROM historico_compras hc
                        JOIN produtos p ON hc.id_produto = p.id
                        WHERE hc.id_usuario = ? ORDER BY hc.data_compra DESC";
        $stmt_compras = $conn->prepare($sql_compras);
        $stmt_compras->bind_param("i", $_SESSION['id_usuario']);
        $stmt_compras->execute();
        $resultado_compras = $stmt_compras->get_result();

        if ($resultado_compras->num_rows > 0) {
            while ($compra = $resultado_compras->fetch_assoc()) {
                echo "<p>Produto: " . $compra['nome'] . " - Quantidade: " . $compra['quantidade'] . " - Data: " . $compra['data_compra'] . "</p>";
            }
        } else {
            echo "<p class='text-gray-400'>Você ainda não fez nenhuma compra.</p>";
        }
        ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>
