document.addEventListener('DOMContentLoaded', () => {
  // === Carrinho de Compras ===
  const botoesAdicionar = document.querySelectorAll('.btn-adicionar');
  const iconeCarrinho = document.querySelector('.icons a[href$="carrinho.html"] .contador-carrinho');
  
  let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

  // Atualizar contador no carrinho
  function atualizarContadorCarrinho() {
    if (iconeCarrinho) {
      iconeCarrinho.textContent = carrinho.length;
    }
  }

  // Adicionar produto ao carrinho
  botoesAdicionar.forEach((botao) => {
    botao.addEventListener('click', () => {
      const card = botao.closest('.card-produto');
      const nome = card.querySelector('h3').textContent;
      const preco = card.querySelector('.preco').textContent;
      const imagem = card.querySelector('img').src;

      // Verifica se algum tamanho está selecionado
      const tamanhoSelecionado = card.querySelector('.tamanho.selecionado');
      if (!tamanhoSelecionado) {
        alert("Por favor, selecione um tamanho antes de adicionar ao carrinho.");
        return;
      }

      const tamanho = tamanhoSelecionado.textContent;

      const produto = { nome, preco, imagem, tamanho };

      // Verifica se o produto com mesmo nome, preço e tamanho já está no carrinho
      const produtoExistente = carrinho.find(item =>
        item.nome === produto.nome &&
        item.preco === produto.preco &&
        item.tamanho === produto.tamanho
      );

      if (!produtoExistente) {
        carrinho.push(produto);
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
        alert(`${nome} (${tamanho}) foi adicionado ao carrinho!`);
      } else {
        alert(`${nome} (${tamanho}) já está no carrinho!`);
      }

      atualizarContadorCarrinho();
    });
  });

  atualizarContadorCarrinho(); // Inicializa o contador

  // === Carrossel Automático ===
  let slideIndex = 0;
  const slides = document.querySelectorAll('.slide');

  function mostrarSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.toggle('active', i === index);
    });
  }

  function proximoSlide() {
    slideIndex = (slideIndex + 1) % slides.length;
    mostrarSlide(slideIndex);
  }

  if (slides.length > 0) {
    mostrarSlide(slideIndex);
    setInterval(proximoSlide, 5000); // Troca de slide a cada 5 segundos
  }

  // === Seleção de Tamanhos ===
  const todosTamanhos = document.querySelectorAll('.tamanho');

  todosTamanhos.forEach(tamanho => {
    tamanho.addEventListener('click', () => {
      const grupo = tamanho.closest('.tamanhos') || tamanho.parentElement;
      grupo.querySelectorAll('.tamanho').forEach(t => t.classList.remove('selecionado'));
      tamanho.classList.add('selecionado');
    });
  });

  // === Finalizar Compra ===
  const botaoFinalizar = document.getElementById('finalizar-compra');
  if (botaoFinalizar) {
    botaoFinalizar.addEventListener('click', () => {
      fetch('finalizar_compra.php')
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert('Compra finalizada! Pedido #' + data.pedido_id);
            localStorage.removeItem('carrinho');
            carrinho = [];
            atualizarContadorCarrinho();
            window.location.href = 'obrigado.html'; // página de confirmação, crie essa página
          } else {
            alert('Erro: ' + (data.error || 'Não foi possível finalizar a compra.'));
          }
        })
        .catch(() => alert('Erro na requisição'));
    });
  }
});
