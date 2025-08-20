<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tenda"); // ajuste seu BD

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $conteudo = $_POST['conteudo'];
    $data = $_POST['data'];
    $usuario_id = $_SESSION['usuario_id'];

    $caminho = null;

    // Se enviou um arquivo
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $pasta = "uploads/";
        if (!is_dir($pasta)) mkdir($pasta, 0777, true);

        $nomeArquivo = time() . "_" . $_FILES['imagem']['name'];
        $caminho = $pasta . $nomeArquivo;
        move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho);
    } 
    // Se enviou link
    elseif (!empty($_POST['imagem_link'])) {
        $caminho = $_POST['imagem_link'];
    }

    $sql = "INSERT INTO pesquisas (usuario_id, nome, conteudo, data, imagem) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $usuario_id, $nome, $conteudo, $data, $caminho);

    if ($stmt->execute()) {
        header("Location: ../pesquisas.php?msg=sucesso");
        exit();
    } else {
        echo "Erro: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Pesquisa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4 text-center">Cadastrar Nova Pesquisa</h2>
        <form method="POST" enctype="multipart/form-data">
            <label class="block mb-2 font-semibold">Nome da Pesquisa:</label>
            <input type="text" name="nome" required class="w-full border rounded-lg p-2 mb-4">

            <label class="block mb-2 font-semibold">Conteúdo:</label>
            <textarea name="conteudo" rows="5" required class="w-full border rounded-lg p-2 mb-4"></textarea>

            <label class="block mb-2 font-semibold">Data:</label>
            <input type="date" name="data" value="<?= date('Y-m-d'); ?>" required class="w-full border rounded-lg p-2 mb-4">

            <label class="block mb-2 font-semibold">Imagem (arquivo ou link):</label>
            <input type="file" name="imagem" accept="image/*" class="w-full mb-2">
            <input type="url" name="imagem_link" placeholder="Cole o link da imagem aqui" class="w-full border rounded-lg p-2 mb-4">

            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700">
                Cadastrar
            </button>
        </form>
    </div>
</body>
</html>
