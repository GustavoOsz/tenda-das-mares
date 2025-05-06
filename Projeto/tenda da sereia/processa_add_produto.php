<?php
// Conexão com o banco de dados
$host = '127.0.0.1';
$user = 'adm';
$password = '12345';
$database = 'tenda_sereia';

$conn = new mysqli($host, $user, $password, $database);

// Verifica se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
} else {
    echo "Conexão bem-sucedida!<br>";
}

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "Formulário foi enviado via POST!<br>"; // Verificação adicional

    // Coleta os dados do formulário
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $imagem = $_POST['imagem']; // URL da imagem

    // Verifica se os dados foram recebidos corretamente
    echo "Dados recebidos:<br>";
    echo "Nome: $nome<br>";
    echo "Descrição: $descricao<br>";
    echo "Preço: $preco<br>";
    echo "Imagem: $imagem<br>";

    // Verifica se todos os campos têm valor
    if (empty($nome) || empty($descricao) || empty($preco) || empty($imagem)) {
        echo "Erro: Algum campo está vazio!<br>";
    } else {
        // Insere os dados no banco de dados
        $sql = "INSERT INTO produtos (nome, descricao, preco, imagem) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Verifica se a preparação da consulta foi bem-sucedida
        if ($stmt === false) {
            die("Erro na preparação da consulta: " . $conn->error);
        } else {
            echo "Consulta preparada com sucesso!<br>";
        }

        // Faz o bind dos parâmetros
        $stmt->bind_param("ssss", $nome, $descricao, $preco, $imagem);

        // Executa a inserção e verifica se foi bem-sucedida
        if ($stmt->execute()) {
            echo "Produto adicionado com sucesso!<br>";
        } else {
            echo "Erro ao adicionar o produto: " . $conn->error . "<br>";
        }

        $stmt->close();
    }
} else {
    echo "Nenhum dado enviado via POST.<br>";
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Adicionar Produto | Tenda da Sereia</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gradient-to-b from-[#4b2e1a] to-[#2b1a10] text-white min-h-screen flex items-center justify-center p-4">

  <div class="bg-white/10 border border-yellow-300 rounded-2xl shadow-xl p-8 w-full max-w-2xl backdrop-blur">
    <h1 class="text-4xl font-bold text-yellow-400 mb-6 text-center animate-fade-in">Adicionar Novo Produto</h1>

    <!-- Mensagem de sucesso -->
    <div id="mensagem-sucesso" class="hidden bg-green-600/80 text-white text-center p-3 rounded-lg mb-5 font-semibold shadow-md">
      ✅ Produto adicionado com sucesso!
    </div>

    <form id="form-add-produto" method="POST" enctype="multipart/form-data" class="space-y-5">
      <input 
        type="text" 
        name="nome" 
        placeholder="Nome do Produto" 
        class="w-full p-3 rounded-lg bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition"
        required
      >

      <textarea 
        name="descricao" 
        placeholder="Descrição do Produto" 
        class="w-full p-3 rounded-lg bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition"
        rows="3" 
        required
      ></textarea>

      <input 
        type="number" 
        step="0.01" 
        name="preco" 
        placeholder="Preço (R$)" 
        class="w-full p-3 rounded-lg bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition"
        required
      >

      <input 
        type="text" 
        name="imagem" 
        placeholder="URL da Imagem" 
        class="w-full p-3 rounded-lg bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition"
        required
      >

      <div class="flex justify-between items-center pt-4">
        <a href="acervo.php" class="text-yellow-300 hover:underline">← Voltar para o Acervo</a>
        <button 
          type="submit" 
          class="bg-yellow-400 text-black font-bold px-6 py-3 rounded-xl hover:bg-yellow-500 transition-transform hover:scale-105 shadow-md"
        >
          Adicionar Produto
        </button>
      </div>
    </form>
  </div>

  <style>
    @keyframes fade-in {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
      animation: fade-in 0.6s ease-out forwards;
    }
  </style>

  <script>
    $(document).ready(function() {
      $('#form-add-produto').on('submit', function(event) {
        event.preventDefault();

        var formData = $(this).serialize();  // Serializa os dados do formulário

        $.ajax({
          url: 'processa_add_produto.php', // A URL do script de processamento
          type: 'POST',
          data: formData,  // Envia os dados serializados
          success: function(response) {
            $('#mensagem-sucesso').removeClass('hidden'); // Exibe a mensagem de sucesso
            $('#form-add-produto')[0].reset(); // Limpa o formulário
            console.log(response); // Exibe a resposta no console para depuração
          },
          error: function(xhr, status, error) {
            console.error('Erro: ' + status + ': ' + error);
            alert('Erro ao adicionar produto!');
          }
        });
      });
    });
  </script>
</body>
</html>
