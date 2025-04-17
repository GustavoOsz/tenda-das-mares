document.addEventListener("DOMContentLoaded", () => {
    // Marca o link da navbar clicado como ativo
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', () => {
        document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
        link.classList.add('active');
      });
    });
  
    // Botões flutuantes
    const historiaBtn = document.getElementById("historiaBtn");
    const pecasBtn = document.getElementById("pecasBtn");
    const logo = document.getElementById("logo");
  
    if (historiaBtn) {
      historiaBtn.classList.remove("opacity-0", "translate-x-[-20px]");
      historiaBtn.classList.add("opacity-100", "translate-x-0");
    }
  
    if (pecasBtn) {
      pecasBtn.classList.remove("opacity-0", "translate-x-[-20px]");
      pecasBtn.classList.add("opacity-100", "translate-x-0");
    }
  
    if (logo) {
      logo.classList.remove("opacity-0", "scale-95");
      logo.classList.add("opacity-100", "scale-100");
    }
  
    // Só para debug (opcional)
    historiaBtn?.addEventListener('mouseover', () => {
      console.log('Mouse sobre botão de história');
    });
  
    pecasBtn?.addEventListener('mouseover', () => {
      console.log('Mouse sobre botão de peças');
    });
  });
  