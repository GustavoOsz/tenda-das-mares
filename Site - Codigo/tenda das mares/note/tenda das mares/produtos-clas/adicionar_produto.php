<?php
session_start();

// Conexão com o banco
$conn = new mysqli("localhost", "root", "", "tenda");

// Verifica conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}


$mensagem = "";

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];

    // Upload da imagem
$imagem = "";
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
    $nomeImg = uniqid() . "-" . basename($_FILES['imagem']['name']);
    $caminho = "../img/" . $nomeImg; // Salva dentro da pasta img
    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {
        $imagem = "img/" . $nomeImg; // Caminho que vai pro banco
    }
}

    // Insere no banco
    $sql = "INSERT INTO produto (nome, descricao, preco, imagem) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $nome, $descricao, $preco, $imagem);

    if ($stmt->execute()) {
        $mensagem = "Produto adicionado com sucesso!";
    } else {
        $mensagem = "Erro ao adicionar produto: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Produto</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-lg">
        <h2 class="text-2xl font-bold text-center mb-6 text-yellow-600">Adicionar Produto</h2>

        <?php if ($mensagem): ?>
            <p class="bg-green-100 text-green-700 p-3 rounded-lg text-center mb-4"><?= $mensagem ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block font-semibold mb-1">Nome</label>
                <input type="text" name="nome" required class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-yellow-500">
            </div>

            <div>
                <label class="block font-semibold mb-1">Descrição</label>
                <textarea name="descricao" required class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-yellow-500"></textarea>
            </div>

            <div>
                <label class="block font-semibold mb-1">Preço</label>
                <input type="number" step="0.01" name="preco" required class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-yellow-500">
            </div>

            <div>
                <label class="block font-semibold mb-1">Imagem</label>
                <input type="file" name="imagem" accept="image/*" class="w-full border border-gray-300 rounded-lg p-2">
            </div>

            <button type="submit" class="w-full bg-yellow-600 text-white py-2 rounded-lg hover:bg-yellow-700 transition">Adicionar Produto</button>
        </form>
    </div>
    <a href="../produtos.php" 
           class="fixed bottom-6 right-6 bg-[#fbc97f] hover:bg-[#f7b95e] text-[#4f2905] px-4 py-3 rounded-full shadow-lg flex items-center gap-2 font-semibold">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" 
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            voltar
        </a>
</body>
</html>
