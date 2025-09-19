<script>
// ======= Mock de produtos (funciona já, sem API) =======
window.DB = (function(){
  const cats = ['feminino','masculino','acessorios'];
  const items = []; let id=1;
  for(const cat of cats){
    for(let i=0;i<24;i++){
      const preco = cat==='acessorios' ? 39.9 + (i%8)*5 : 59.9 + (i%10)*8;
      items.push({
        id: id++,
        nome: `${cat.charAt(0).toUpperCase()+cat.slice(1)} Modelo ${i+1}`,
        categoria: cat,
        preco: Number(preco.toFixed(2)),
        imagem: `https://picsum.photos/seed/${cat}-${i}/800/1200`
      });
    }
  }
  return items;
})();

// ======= Comportamento da página =======
document.addEventListener('DOMContentLoaded', () => {
  // 1) ler categoria da query (?categoria=feminino|masculino|acessorios). Padrão: feminino
  const qs = new URLSearchParams(location.search);
  const categoria = (qs.get('categoria') || 'feminino').toLowerCase();

  // 2) elementos da tela
  const titulo = document.getElementById('titulo');
  const lista  = document.getElementById('lista');
  const ordenar= document.getElementById('ordenar');
  const pag    = document.getElementById('paginacao');
  const vazio  = document.getElementById('state-vazio');
  const erro   = document.getElementById('state-erro');

  // destacar menu
  document.querySelectorAll('.area-categorias .menu a').forEach(a=>{
    a.classList.toggle('selecao', a.dataset.cat === categoria);
  });

  titulo.textContent = categoria.charAt(0).toUpperCase()+categoria.slice(1);

  // estado
  const state = { page:1, perPage:12, sort:'relevance', filtros:{ precoMin:'', precoMax:'', tamanhos:[] } };

  // ordenar
  ordenar.addEventListener('change', () => { state.sort = ordenar.value; state.page=1; render(); });

  // filtros
  document.getElementById('aplicar')?.addEventListener('click', () => {
    const min = document.getElementById('preco-min').value;
    const max = document.getElementById('preco-max').value;
    const tamanhos = Array.from(document.querySelectorAll('#tamanhos input:checked')).map(i=>i.value);
    state.filtros = { precoMin:min, precoMax:max, tamanhos };
    state.page = 1; render();
  });
  document.getElementById('limpar')?.addEventListener('click', () => {
    document.getElementById('preco-min').value = '';
    document.getElementById('preco-max').value = '';
    document.querySelectorAll('#tamanhos input').forEach(i=> i.checked=false);
    state.filtros = { precoMin:'', precoMax:'', tamanhos:[] };
    state.page = 1; render();
  });

  // “busca” local simulada
  function getData(){
    let data = (window.DB||[]).filter(p=> p.categoria===categoria);
    const min = state.filtros.precoMin !== '' ? Number(state.filtros.precoMin) : null;
    const max = state.filtros.precoMax !== '' ? Number(state.filtros.precoMax) : null;
    if (min!=null) data = data.filter(p=> p.preco >= min);
    if (max!=null) data = data.filter(p=> p.preco <= max);
    if (state.filtros.tamanhos?.length){
      // como o mock não tem tamanhos reais, vou simular alternando tamanhos
      const T = ['PP','P','M','G','GG'];
      data = data.map((p,i)=> ({...p, _tam: T[i%T.length]}))
                 .filter(p=> state.filtros.tamanhos.includes(p._tam));
    }
    if (state.sort==='price_asc')  data.sort((a,b)=> a.preco-b.preco);
    if (state.sort==='price_desc') data.sort((a,b)=> b.preco-a.preco);
    if (state.sort==='newest')     data = data.slice().reverse();
    return data;
  }

  function render(){
    try{
      const all = getData();
      const total = all.length;
      if (!total){
        lista.innerHTML = '';
        pag.innerHTML = '';
        vazio.style.display = 'block';
        erro.style.display = 'none';
        return;
      }
      vazio.style.display = 'none';
      erro.style.display = 'none';

      const start = (state.page-1)*state.perPage;
      const items = all.slice(start, start+state.perPage);

      const money = (v)=> v.toLocaleString('pt-BR',{style:'currency',currency:'BRL'});
      lista.innerHTML = items.map(p=>`
        <div class="produto">
          <div class="foto-produto">
            <img src="${p.imagem}" alt="${p.nome}" loading="lazy" width="800" height="1200">
            <button class="btn-comprar" onclick="alert('Adicionar ${p.nome} ao carrinho')">ADICIONAR AO CARRINHO</button>
          </div>
          <div class="info-produto">
            <p>${p.categoria.charAt(0).toUpperCase()+p.categoria.slice(1)}</p>
            <h4>${p.nome}</h4>
            <h3>${money(p.preco)}</h3>
          </div>
        </div>
      `).join('');

      // paginação
      const pages = Math.max(1, Math.ceil(total/state.perPage));
      pag.innerHTML = '';
      for(let i=1;i<=pages;i++){
        const b = document.createElement('button');
        b.textContent = i;
        if (i===state.page) b.disabled = true;
        b.onclick = ()=>{ state.page=i; render(); window.scrollTo({top:0,behavior:'smooth'}); };
        pag.appendChild(b);
      }
    }catch(e){
      console.error(e);
      lista.innerHTML = '';
      pag.innerHTML = '';
      vazio.style.display = 'none';
      erro.style.display = 'block';
    }
  }

  render();
});

// ======= Quando sua API estiver pronta, troque o mock por isso: =======
/*
fetch('/api/produtos?categoria=' + categoria)
  .then(r=>r.json())
  .then(lista=>{ window.DB = lista; render(); })
  .catch(()=> { mostrarErro(); });
*/
</script>

