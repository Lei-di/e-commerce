<?php include 'components/cabecalho.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
    <link rel="stylesheet" href="css/perfil.css">
    <link rel="stylesheet" href="css/global/cabecalho.css">
</head>
<body>
    <section class="cabecalho">
        <div class="logo">
            <a href="pagina_principal.html">ELEGANCIA</a>
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
            <img src="../assets/icones/lupa.png" alt="">

            <form action="">
                <input type="text" placeholder="Buscar produtos...">
            </form>
        </div>

        <div class="area-dados">
            <a href="perfil.html"><img id="usuario" src="../assets/icones/perfil.png" alt=""></a>
            <img id="carrinho" src="../assets/icones/carrinho.png" alt="">
        </div>
    </section>

    <main class="profile-main-content">
        <div class="profile-sidebar-wrapper">
            <aside class="profile-sidebar">
                <nav class="profile-nav">
                    <h2>MINHA CONTA</h2>
                    <ul>
                        <li><a href="#" class="tab-link active" data-tab="meu-perfil">Meu Perfil +</a></li>
                        <li><a href="#" class="tab-link" data-tab="meus-pedidos">Meus Pedidos +</a></li>
                        <li><a href="#" class="tab-link" data-tab="lista-enderecos">Lista de Endereços +</a></li>
                        <li><a href="#" class="tab-link" data-tab="opcoes-pagamento">Opções de pagamento +</a></li>
                    </ul>
                </nav>
            </aside>
            <div class="wishlist-section">
                <h2>LISTA DE DESEJOS</h2>
                <div class="wishlist-grid-sidebar">
                    <!-- Placeholders para a lista de desejos na sidebar -->
                </div>
                <a href="#" class="mais-link">+ MAIS</a>
            </div>
        </div>

        <section class="profile-content-area">
            <div id="meu-perfil" class="tab-content active">
                <h3>MEU PERFIL</h3>
                <p>Informações do seu perfil.</p>
            </div>
            <div id="meus-pedidos" class="tab-content">
                <h3>MEUS PEDIDOS</h3>
                <p>Nenhum pedido encontrado.</p>
            </div>
            <div id="lista-enderecos" class="tab-content">
                <h3>LISTA DE ENDEREÇOS</h3>
                <p>Nenhum endereço cadastrado.</p>
            </div>
            <div id="opcoes-pagamento" class="tab-content">
                <h3>OPÇÕES DE PAGAMENTO</h3>
                <p>Nenhuma opção de pagamento cadastrada.</p>
            </div>
        </section>
    </main>

    <script src="../js/perfil.js"></script>
</body>
</html>

