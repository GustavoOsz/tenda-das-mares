
    
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>História - Tenda da Sereia</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .writing {
      white-space: pre-wrap;
      overflow: hidden;
      border-right: 2px solid yellow;
      font-family: monospace;
    }
  </style>
</head>
<body class="bg-gradient-to-b from-[#4b2e1a] to-[#2b1a10] text-white min-h-screen">
   <?php include 'navbar.php'; ?>
 <div class="items-center justify-center flex flex-col h-screen">  
    <div class="flex flex-col md:flex-row items-center justify-between px-10 py-12 gap-10">
        <!-- Imagem -->
        <div class="w-full md:w-1/2 flex justify-center">
            <img src="img/velas.jpg" alt="História da Tenda"
            class="w-full max-w-md max-h-[400px] object-contain rounded-lg shadow-xl">
        </div>
        
        <!-- Texto com efeito de escrita -->
        <div class="w-full md:w-1/2">
            <h2 class="text-3xl font-bold mb-4 text-yellow-400">Nossa História</h2>
            <p id="historiaTexto" class="text-lg leading-relaxed writing"></p>
        </div>
    </div>
    
    <script>
       const linhas = [
  "A Tenda da Sereia surgiu com a missão de conectar pessoas com sua ancestralidade.",
  "Fortalecendo as raízes culturais e espirituais através da arte, da fé e do respeito."
];

const elemento = document.getElementById("historiaTexto");
let linhaAtual = 0;
let index = 0;

function escreverLinha() {
  if (linhaAtual >= linhas.length) {
    elemento.style.borderRight = 'none';
    return;
  }

  if (index < linhas[linhaAtual].length) {
    elemento.innerHTML += linhas[linhaAtual].charAt(index);
    index++;
    setTimeout(escreverLinha, 35);
  } else {
    elemento.innerHTML += '<br>';
    linhaAtual++;
    index = 0;
    setTimeout(escreverLinha, 400); // Pequeno delay entre linhas
  }
}

document.addEventListener("DOMContentLoaded", escreverLinha);

        </script>
 </div>

</body>
</html>
