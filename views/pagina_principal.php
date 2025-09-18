<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elegancia Store</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/pagina_principal.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/global/cabecalho.css">
</head>
<body>
    <?php include __DIR__ . '/components/cabecalho.php'; ?>

    <section class="propaganda">
        <img src="<?= BASE_URL ?>/assets/imagens/propaganda.jpg" alt="Propaganda de nova coleção">
        <div class="conteudo-propaganda">
            <h1>NOVA COLEÇÃO</h1>
            <h3>Descubra as últimas tendências da moda feminina</h3>
            <button>Explorar Coleção</button>
        </div>
    </section>

    <main>
        <div class="filtro">
             <h3>Filtros</h3>

            <h4 class="categoria">Categoria</h4>
            <div class="opcoes">
                <p><input type="checkbox" id="vestidos"><label for="vestidos">Vestidos</label></p>
                <p><input type="checkbox" id="blusas"><label for="blusas">Blusas</label></p>
                <p><input type="checkbox" id="calcas"><label for="calcas">Calças</label></p>
                <p><input type="checkbox" id="blazers"><label for="blazers">Blazers</label></p>
                <p><input type="checkbox" id="casacos"><label for="casacos">Casacos</label></p>
            </div>

            <h4>Faixa de preço</h4><br>
            <article class="preco">
                <label><input type="number" name="minimo" value="" placeholder="Min.."></label>
                <label><input type="number" name="maximo" value="" placeholder="Max.."></label>
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

            <h4>Cores</h4><br>
            <article class="tamanho">
                <label><input type="checkbox" name="cor" value="Preto"> Preto</label>
                <label><input type="checkbox" name="cor" value="Cinza"> Cinza</label>
                <label><input type="checkbox" name="cor" value="Vermelho"> Vermelho</label>
                <label><input type="checkbox" name="cor" value="Branco"> Branco</label>
                <label><input type="checkbox" name="cor" value="Azul"> Azul</label>
                <label><input type="checkbox" name="cor" value="Verde"> Verde</label>
            </article>
        </div>
       <div class="area-produto">
            <h1>Todos</h1>
            <div class="lista-produtos">
                </div>
        </div>
    </main>

    <script>
        // Passa a URL base do PHP para o JavaScript
        const baseURL = "<?= BASE_URL ?>";
    </script>
    <script src="<?= BASE_URL ?>/js/main.js"></script>
</body>
</html>