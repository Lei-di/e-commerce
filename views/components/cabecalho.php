<?php
// Garante que a sessão seja iniciada em qualquer página que inclua o cabeçalho
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<style>
    /* Adicionado para que os links não fiquem com a decoração padrão (sublinhado) */
    .cabecalho ul li a, .area-dados a {
        text-decoration: none;
        color: inherit;
    }
    .area-dados {
        display: flex;
        align-items: center;
        gap: 15px; /* Mantém o gap */
    }
    .link-sair {
        margin-left: -5px; /* Ajusta posição do "Sair" */
        margin-right: 15px; /* Espaço antes do carrinho */
        font-weight: 500;
        font-size: 15px;
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
        <li><a href="<?= BASE_URL ?>/?categoria=calçados" class="category-link" data-category="calçados"><h4>Calçados</h4></a></li>
    </ul>
    <div class="pesquisa">
        <img src="<?= BASE_URL ?>/assets/icones/lupa.png" alt="Ícone de busca">
        
        <form id="form-busca" action=""> 
            <input type="text" id="input-busca" name="busca" placeholder="Buscar produtos...">
        </form>
    </div>
    
    <div class="area-dados">
        
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <a href="<?= BASE_URL ?>/perfil" title="Meu Perfil">
                <img id="usuario" src="<?= BASE_URL ?>/assets/icones/perfil.png" alt="Ícone de perfil">
            </a>
            <a href="<?= BASE_URL ?>/logout" class="link-sair">Sair</a>
        
        <?php else: ?>
            <a href="<?= BASE_URL ?>/login" title="Fazer Login">
                <img id="usuario" src="<?= BASE_URL ?>/assets/icones/perfil.png" alt="Ícone de perfil">
            </a>
        <?php endif; ?>

        <img id="carrinho" src="<?= BASE_URL ?>/assets/icones/carrinho.png" alt="Ícone do carrinho">
    </div>
</section>