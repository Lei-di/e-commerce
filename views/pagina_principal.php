<!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Elegancia Store</title>
        <link rel="stylesheet" href="<?= BASE_URL ?>/css/pagina_principal.css">
        <link rel="stylesheet" href="<?= BASE_URL ?>/css/global/cabecalho.css">
        <link rel="stylesheet" href="<?= BASE_URL ?>/css/sidecart.css">
        
        </head>
    <body>
        <?php include __DIR__ . '/components/cabecalho.php'; ?>


        <section class="propaganda">
            <img src="<?= BASE_URL ?>/assets/imagens/propaganda.jpg" alt="Propaganda de nova coleção">
            <div class="conteudo-propaganda">
                <h1>NOVA COLEÇÃO</h1>
                <h3>Descubra as últimas tendências da moda</h3>
                <a class="botao-colecao" href="<?= BASE_URL ?>/?categoria=novidades">Explorar Coleção</a>
            </div>
        </section>


        <main>
            <div class="filtro">
                <h3>Filtros</h3>

                <h4 class="categoria">Categoria</h4>
                <div class="opcoes">
                    <?php if (isset($categorias_filtro) && !empty($categorias_filtro)): ?>
                        <?php foreach ($categorias_filtro as $categoria): ?>
                            <p>
                                <input type="radio" 
                                       id="<?= htmlspecialchars($categoria['nome']) ?>" 
                                       name="categoria_filtro" 
                                       value="<?= htmlspecialchars($categoria['nome']) ?>">
                                <label for="<?= htmlspecialchars($categoria['nome']) ?>">
                                    <?= htmlspecialchars($categoria['nome']) ?>
                                </label>
                            </p>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Nenhuma categoria encontrada.</p>
                    <?php endif; ?>
                </div>
                <h4>Faixa de preço</h4><br>
                <article class="preco">
                    <label><input type="number" name="minimo" value="" placeholder="Min.." min="0"></label>
                    <label><input type="number" name="maximo" value="" placeholder="Max.." ></label>
                </article>

                <h4>Tamanhos</h4><br>
                <article class="tamanho">
                    <label><input type="checkbox" name="tamanho" value="PP"> PP</label>
                    <label><input type="checkbox" name="tamanho" value="P"> P</label>
                    <label><input type="checkbox" name="tamanho" value="M"> M</label>
                    <label><input type="checkbox" name="tamanho" value="G"> G</label>
                    <label><input type="checkbox" name="tamanho" value="GG"> GG</label>
                    <label><input type="checkbox" name="tamanho" value="XG"> XG</label>
                </article>
 
                <button id="btn-aplicar-filtros" class="btn-aplicar-filtros">Aplicar Filtros</button>
                </div>
        <div class="area-produto">
                <h1><?= htmlspecialchars($titulo) ?></h1>
                <div class="lista-produtos">
                    <?php if (empty($produtos)): ?>
                        <p>Nenhum produto encontrado nesta categoria.</p>
                    <?php else: ?>
                        <?php foreach ($produtos as $produto): ?>
                            <div class="produto" data-categoria="<?= htmlspecialchars($produto['categoria'] ?? '') ?>">
                                <div class="foto-produto">
                                    <img src="<?= BASE_URL ?>/assets/imagens/<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                    <button class="btn-comprar" data-id-produto="<?= $produto['id'] ?>">ADICIONAR AO CARRINHO</button>
                                </div>
                                <div class="info-produto">
                                    <p><?= strtoupper(htmlspecialchars($produto['categoria'] ?? 'CATEGORIA')) ?></p>
                                    <h4><?= htmlspecialchars($produto['nome']) ?></h4>
                                    
                                    <h3>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></h3>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </div>
            </div>
        </main>
        

        <div id="tamanho-modal-overlay" class="tamanho-modal-overlay"></div>
        <div id="tamanho-modal" class="tamanho-modal">
            <h3>Selecione o Tamanho</h3>
            <button id="btn-cancelar-tamanho" class="btn-cancelar-tamanho">&times;</button>
            <div id="tamanho-modal-opcoes" class="tamanho-opcoes">
                <label><input type="radio" name="tamanho_modal" value="PP"> PP</label>
                <label><input type="radio" name="tamanho_modal" value="P"> P</label>
                <label><input type="radio" name="tamanho_modal" value="M"> M</label>
                <label><input type="radio" name="tamanho_modal" value="G"> G</label>
                <label><input type="radio" name="tamanho_modal" value="GG"> GG</label>
                <label><input type="radio" name="tamanho_modal" value="XG"> XG</label>
            </div>
            <button id="btn-confirmar-tamanho" class="btn-confirmar-tamanho">Confirmar</button>
        </div>
        <div id="sidecart-overlay" class="sidecart-overlay"></div>
            <div id="sidecart" class="sidecart">
                <div class="sidecart-container"> <div class="sidecart-header">
                        <h1>Carrinho</h1>
                        <button id="close-cart-btn" class="close-cart-btn">&times;</button> </div>

                    <div id="sidecart-items" class="sidecart-items">
                        <p>Seu carrinho está vazio.</p> </div>

                    <div class="sidecart-footer"> <p id="sidecart-total">Tudo: <span>R$ 0,00</span></p>
                        <a href="<?= BASE_URL ?>/checkout" id="sidecart-checkout-btn" class="finalize-btn">FINALIZAR (0)</a>
                        <p class="free-shipping" style="display: none;">Elegível para o TRANSPORTE LIVRE!</p>
                    </div>
                </div>
        </div>

        <script>
            // Passa a URL base do PHP para o JavaScript
            const baseURL = "<?= BASE_URL ?>";
        </script>
        <script src="<?= BASE_URL ?>/js/sidecart.js"></script>
        <script src="<?= BASE_URL ?>/js/main.js"></script>
    </body>
    </html>