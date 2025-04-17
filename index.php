<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tenda da Sereia</title>

  <!-- JavaScript -->
  <script src="js/script.js"></script>
 
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Estilo personalizado -->
  <link rel="stylesheet" href="css/style.css">

</head>
<body class="relative min-h-screen text-white bg-black overflow-hidden">
  <!-- Fundo com blur (deve ficar no fundo e sem cliques) -->
  <div class="absolute inset-0 bg-overlay z-0 pointer-events-none"></div>

  <!-- Navbar -->
  <div class="relative z-50">
    <?php include 'navbar.php'; ?>
  </div>

  <!-- Botões flutuantes -->
  <div class="absolute top-1/3 left-[20%] z-50 flex flex-col gap-10">
    <a href="historia.php" id="historiaBtn"
       class="opacity-0 translate-x-[-20px] transition-all duration-500 delay-10 
              bg-white/10 hover:bg-yellow-400/20 backdrop-blur-md text-white px-6 py-4 
              rounded-xl border border-yellow-300 shadow-md hover:scale-105 hover:text-yellow-300 
              font-medium text-lg">
      Deseja conhecer uma<br> parte de nossa história?
    </a>

    <a href="acervo.php" id="pecasBtn"
       class="opacity-0 translate-x-[-20px] transition-all duration-500 delay-10 
              bg-white/10 hover:bg-yellow-400/20 backdrop-blur-md text-white px-6 py-4 
              rounded-xl border border-yellow-300 shadow-md hover:scale-105 hover:text-yellow-300 
              font-medium text-lg">
      Confira nossas peças!
    </a>
  </div>

<!-- Logo com brilho pulsante -->
<div class="flex justify-end items-end h-[80vh] pr-20 z-40 relative">
  <div class="relative">
    <!-- Círculo borrado e pulsante -->
    <div
      class="absolute top-1/2 right-1/2 
             translate-x-1/2 -translate-y-1/2 
             w-[200px] h-[200px] 
             bg-yellow-100 opacity-10 
             rounded-full blur-3xl 
             animate-ping">
    </div>
    <!-- Sua logo -->
    <img
      src="img/logo.png"
      alt="Logo Tenda da Sereia"
      id="logo"
      class="relative z-10
             w-[550px] max-w-none 
             drop-shadow-xl 
             opacity-0 scale-95 
             transition-all duration-[600ms]" />
  </div>
</div>

  <!-- Script -->
  <script src="js/script.js"></script>
</body>
</html>
