<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Categorias — Minha Loja</title>

  <link rel="stylesheet" href="<?= BASE_URL ?>/css/categorias.css">
</head>
<body>

  <?php if (file_exists(__DIR__.'/components/cabecalho.php')) require __DIR__.'/components/cabecalho.php'; ?>

  <section class="area-categorias">
    <nav class="menu" id="menu-categorias">
      <a href="<?= BASE_URL ?>/categorias?categoria=feminino"  data-cat="feminino">Feminino</a>
      <a href="<?= BASE_URL ?>/categorias?categoria=masculino" data-cat="masculino">Masculino</a>
      <a href="<?= BASE_URL ?>/categorias?categoria=acessorios" data-cat="acessorios">Acessórios</a>
    </nav>
  </section>

  <main class="categorias">
    <aside class="filtro">
      <h2>Filtros</h2>

      <h4>Preço</h4>
      <div class="preco">
        <input type="number" id="preco-min" placeholder="min" min="0" step="10">
        <span>-</span>
        <input type="number" id="preco-max" placeholder="máx" min="0" step="10">
        <button id="aplicar" style="height:30px; padding:0 10px;">Aplicar</button>
      </div>

      <h4 style="margin-top:16px;">Tamanho</h4>
      <div class="tamanho" id="tamanhos">
        <label><input type="checkbox" value="PP"> PP</label>
        <label><input type="checkbox" value="P">  P</label>
        <label><input type="checkbox" value="M">  M</label>
        <label><input type="checkbox" value="G">  G</label>
        <label><input type="checkbox" value="GG"> GG</label>
      </div>

      <button id="limpar" style="margin-top:16px; width:100%;">Limpar filtros</button>
    </aside>

    <section class="area-produto">
      <div class="topo-lista">
        <h1 id="titulo">Categoria</h1>
        <div class="ordenar">
          <label>Ordenar:
            <select id="ordenar">
              <option value="relevance">Relevância</option>
              <option value="price_asc">Preço: menor → maior</option>
              <option value="price_desc">Preço: maior → menor</option>
              <option value="newest">Novidades</option>
            </select>
          </label>
        </div>
      </div>

      <div id="state-vazio" class="state">Nenhum produto encontrado.</div>
      <div id="state-erro" class="state" style="color:#b00">Falha ao carregar produtos.</div>

      <div class="lista-produtos" id="lista"></div>
      <div class="paginacao" id="paginacao"></div>
    </section>
  </main>

  <script src="<?= BASE_URL ?>/js/categorias.js"></script>
</body>
</html>
