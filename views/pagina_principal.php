<?php include 'components/cabecalho.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Título da Página</title>

 <link rel="stylesheet" href="css/pagina_principal.css">
    <link rel="stylesheet" href="css/global/cabecalho.css">
</head>
<body>  
<section class="cabecalho">
        <div class="logo">
            <a href="#">ELEGANCIA</a>
        </div>
        <ul>
            <li>
                <h4>Novidades</h4>
            </li>

            <li>
                <h4>Feminino</h4>
            </li>

            <li>
                <h4>Masculino</h4>
            </li>

            <li>
                <h4>Acessórios</h4>
            </li>
        </ul>

        <div class="pesquisa">
            <img src="../src/assets/icones/lupa.png" alt="">

            <form action="">
                <input type="text" placeholder="Buscar produtos...">
            </form>
        </div>

        <div class="area-dados">
            <img id="usuario" src="../src/assets/icones/perfil.png" alt="">
            <img id="carrinho" src="../src/assets/icones/carrinho.png" alt="">
        </div>
    </section>

    <!--conteudo de propaganda-->
    <section class="propaganda">
        <img src="../src/assets/imagens/propaganda.jpg" alt="">

        <div class="conteudo-propaganda">
            <h1>NOVA COLEÇÃO</h1>
            <H3>Descubra as últimas tendências da moda feminina</H3>
            <button>Explorar Coleção</button>
        </div>
    </section>

    <!--menu-->
    <section class="menu">
        <a class="selecao" href="">Todos</a>
        <a href="">Vestidos</a>
        <a href="">Blusas</a>
        <a href="">Calças</a>
        <a href="">Blazers</a>
    </section>

    <!--pagina de venda-->
    <main>
        <div class="filtro">
            <h3>Filtros</h3>

            <h4 class="categoria">Categoria</h4>

            <div class="opcoes">
                <p>
                    <input type="checkbox" id="vestidos">
                    <label for="vestidos">Vestidos</label>
                </p>

                <p>
                    <input type="checkbox" id="blusas">
                    <label for="blusas">Blusas</label>
                </p>

                <p>
                    <input type="checkbox" id="calcas">
                    <label for="calcas">Calças</label>
                </p>

                <p>
                    <input type="checkbox" id="blazers">
                    <label for="blazers">Blazers</label>
                </p>

                <p>
                    <input type="checkbox" id="casacos">
                    <label for="casacos">Casacos</label>
                </p>
            </div>

            <h4>Faixa de preço</h4><br>
            <article class="preco">
                <label><input type="number" name="minimo" value="" placeholder="Min.."></label>
                <label><input type="number" name="maximo" value="" placeholder="Max.."> </label>
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
                <label><input type="checkbox" name="tamanho" value="PP"> Preto</label>
                <label><input type="checkbox" name="tamanho" value="P"> Cinza</label>
                <label><input type="checkbox" name="tamanho" value="M"> Vermelho</label>
                <label><input type="checkbox" name="tamanho" value="G"> Branco</label>
                <label><input type="checkbox" name="tamanho" value="GG"> Azul</label>
                <label><input type="checkbox" name="tamanho" value="XG"> Verde</label>
            </article>
        </div>

       <div class="area-produto">
            <h1>Todos</h1>

            <div class="lista-produtos">
                <div class="produto">
                    <div class="foto-produto">
                        <button class="favorito">
                            <img src="../src/assets/icones/favorito.png" alt="Favoritar">
                        </button>
                        <img src="../src/assets/imagens/vestido.jpg" alt="Vestido Elegante">
                        <button class="btn-comprar">ADICIONAR AO CARRINHO</button>
                    </div>
                    <div class="info-produto">
                        <p>VESTIDOS</p>
                        <h4>Vestido Elegante</h4>
                        <h3>R$ 299,90</h3>
                    </div>
                </div>

                <div class="produto">
                    <div class="foto-produto">
                        <button class="favorito">
                            <img src="../src/assets/icones/favorito.png" alt="Favoritar">
                        </button>
                        <img src="../src/assets/imagens/vestido.jpg" alt="Vestido Elegante">
                        <button class="btn-comprar">ADICIONAR AO CARRINHO</button>
                    </div>
                    <div class="info-produto">
                        <p>VESTIDOS</p>
                        <h4>Vestido Elegante</h4>
                        <h3>R$ 299,90</h3>
                    </div>
                </div>

                <div class="produto">
                    <div class="foto-produto">
                        <button class="favorito">
                            <img src="../src/assets/icones/favorito.png" alt="Favoritar">
                        </button>
                        <img src="../src/assets/imagens/vestido.jpg" alt="Vestido Elegante">
                        <button class="btn-comprar">ADICIONAR AO CARRINHO</button>
                    </div>
                    <div class="info-produto">
                        <p>VESTIDOS</p>
                        <h4>Vestido Elegante</h4>
                        <h3>R$ 299,90</h3>
                    </div>
                </div>

                <div class="produto">
                    <div class="foto-produto">
                        <button class="favorito">
                            <img src="../src/assets/icones/favorito.png" alt="Favoritar">
                        </button>
                        <img src="../src/assets/imagens/vestido.jpg" alt="Vestido Elegante">
                        <button class="btn-comprar">ADICIONAR AO CARRINHO</button>
                    </div>
                    <div class="info-produto">
                        <p>VESTIDOS</p>
                        <h4>Vestido Elegante</h4>
                        <h3>R$ 299,90</h3>
                    </div>
                </div>

                <div class="produto">
                    <div class="foto-produto">
                        <button class="favorito">
                            <img src="../src/assets/icones/favorito.png" alt="Favoritar">
                        </button>
                        <img src="../src/assets/imagens/vestido.jpg" alt="Vestido Elegante">
                        <button class="btn-comprar">ADICIONAR AO CARRINHO</button>
                    </div>
                    <div class="info-produto">
                        <p>VESTIDOS</p>
                        <h4>Vestido Elegante</h4>
                        <h3>R$ 299,90</h3>
                    </div>
                </div>

                <div class="produto">
                    <div class="foto-produto">
                        <button class="favorito">
                            <img src="../src/assets/icones/favorito.png" alt="Favoritar">
                        </button>
                        <img src="../src/assets/imagens/vestido.jpg" alt="Vestido Elegante">
                        <button class="btn-comprar">ADICIONAR AO CARRINHO</button>
                    </div>
                    <div class="info-produto">
                        <p>VESTIDOS</p>
                        <h4>Vestido Elegante</h4>
                        <h3>R$ 299,90</h3>
                    </div>
                </div>
                
            </div>
        </div>
    </main>
</body>
</html>
