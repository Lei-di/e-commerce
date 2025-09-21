<style>
    /* Adicionado para que os links não fiquem com a decoração padrão */
    .cabecalho ul li a {
        text-decoration: none;
        color: inherit;
    }
</style>
<section class="cabecalho">
    <div class="logo">
        <a href="<?= BASE_URL ?>/">ELEGANCIA</a>
    </div>
    <ul>
        <li><a href="<?= BASE_URL ?>/?categoria=novidades"><h4>Novidades</h4></a></li>
        <li><a href="<?= BASE_URL ?>/?categoria=feminino"><h4>Feminino</h4></a></li>
        <li><a href="<?= BASE_URL ?>/?categoria=masculino"><h4>Masculino</h4></a></li>
        <li><a href="<?= BASE_URL ?>/?categoria=acessorios"><h4>Acessórios</h4></a></li>
    </ul>
    <div class="pesquisa">
        <img src="<?= BASE_URL ?>/assets/icones/lupa.png" alt="Ícone de busca">
        <form action="">
            <input type="text" placeholder="Buscar produtos...">
        </form>
    </div>
    <div class="area-dados">
        <a href="<?= BASE_URL ?>/perfil"><img id="usuario" src="<?= BASE_URL ?>/assets/icones/perfil.png" alt="Ícone de perfil"></a>
        <img id="carrinho" src="<?= BASE_URL ?>/assets/icones/carrinho.png" alt="Ícone do carrinho">
    </div>
</section>
