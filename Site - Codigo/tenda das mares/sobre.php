<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Nossa História</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .typing {
      border-right: 2px solid #4f2905;
      white-space: pre-wrap;
      overflow: hidden;
    }
  </style>
</head>
<body class="bg-white text-[#4f2905] font-sans">

  <!-- Navbar -->
  <header class="bg-[#f5f1e6] flex justify-between items-center px-6 py-4 shadow">
    <div>
      <a href="index.php"><img src="img/logo.png" alt="Logo" class="h-10"></a>
    </div>
    <nav class="flex gap-6 text-lg font-medium">
      <a href="produtos.php" class="hover:text-[#b85e2b]">Produtos</a>
      <a href="pesquisas.php" class="hover:text-[#b85e2b]">Pesquisas</a>
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
  <main class="max-w-6xl mx-auto mt-12 grid grid-cols-1 md:grid-cols-3 gap-8 px-6">

    <!-- História da loja -->
    <div class="bg-[#fde9c7] p-8 rounded-2xl shadow-lg col-span-2">
      <h2 class="text-2xl font-bold mb-4">Nossa História</h2>
      <p id="historia" class="typing text-lg leading-relaxed"></p>
    </div>

    <!-- Cards pessoais -->
    <div class="flex flex-col gap-8">

      <div class="bg-[#fde9c7] p-6 rounded-2xl shadow-lg">
        <h3 class="text-xl font-semibold mb-3">Minha trajetória</h3>
        <p id="pessoal1" class="typing text-base leading-relaxed"></p>
      </div>

      <div class="bg-[#fde9c7] p-6 rounded-2xl shadow-lg">
        <h3 class="text-xl font-semibold mb-3">Sobre minha namorada</h3>
        <p id="pessoal2" class="typing text-base leading-relaxed"></p>
      </div>

    </div>
  </main>

  <!-- Script efeito digitação -->
  <script>
    function typeEffect(elementId, text, speed) {
      let i = 0;
      function typing() {
        if (i < text.length) {
          document.getElementById(elementId).textContent += text.charAt(i);
          i++;
          setTimeout(typing, speed);
        }
      }
      typing();
    }

    // Textos
    const historiaTxt = "Nossa loja nasceu do sonho de compartilhar tradições, espiritualidade e boas energias. Com dedicação, transformamos esse ideal em um espaço acolhedor, feito para todos.";
    const pessoal1Txt = "Olá! Meu nome é [Seu Nome], sou apaixonado por cultura, tradições e espiritualidade. Essa loja representa muito da minha caminhada pessoal e profissional.";
    const pessoal2Txt = "Essa é minha namorada [Nome dela], companheira nessa jornada, que com carinho e criatividade ajuda a dar vida a cada detalhe da nossa loja.";

    // Chamando os efeitos
    typeEffect("historia", historiaTxt, 40);
    setTimeout(() => typeEffect("pessoal1", pessoal1Txt, 40), 3000);
    setTimeout(() => typeEffect("pessoal2", pessoal2Txt, 40), 6000);
  </script>

</body>
</html>
