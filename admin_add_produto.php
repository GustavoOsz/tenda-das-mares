
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

        var formData = new FormData(this);

        $.ajax({
          url: 'processa_add_produto.php', // A URL do script de processamento
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function(response) {
            $('#mensagem-sucesso').removeClass('hidden'); // Exibe a mensagem de sucesso
            $('#form-add-produto')[0].reset(); // Limpa o formulário
          },
          error: function() {
            alert('Erro ao adicionar produto!');
          }
        });
      });
    });
  </script>
</body>
</html>
