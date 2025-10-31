<style>
    /* Adicionado para que os links não fiquem com a decoração padrão (sublinhado) */
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
        <li><a href="<?= BASE_URL ?>/?categoria=novidades" class="category-link" data-category="novidades"><h4>Novidades</h4></a></li>
        <li><a href="<?= BASE_URL ?>/?categoria=feminino" class="category-link" data-category="feminino"><h4>Feminino</h4></a></li>
        <li><a href="<?= BASE_URL ?>/?categoria=masculino" class="category-link" data-category="masculino"><h4>Masculino</h4></a></li>
        <li><a href="<?= BASE_URL ?>/?categoria=acessorios" class="category-link" data-category="acessorios"><h4>Acessórios</h4></a></li>
    </ul>
    <div class="pesquisa">
        <img src="<?= BASE_URL ?>/assets/icones/lupa.png" alt="Ícone de busca">
        
        <form id="form-busca" action=""> 
            
            <input type="text" id="input-busca" name="busca" placeholder="Buscar produtos...">
        
        </form>
    </div>
    <div class="area-dados">
        <a href="<?= BASE_URL ?>/perfil"><img id="usuario" src="<?= BASE_URL ?>/assets/icones/perfil.png" alt="Ícone de perfil"></a>
        <img id="carrinho" src="<?= BASE_URL ?>/assets/icones/carrinho.png" alt="Ícone do carrinho">
    </div>
</section>