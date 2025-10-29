document.addEventListener("DOMContentLoaded", function() {
    const tabLinks = document.querySelectorAll(".tab-link");
    const tabContents = document.querySelectorAll(".tab-content");
    const wishlistGridSidebar = document.querySelector(".wishlist-grid-sidebar");

    // Função para mostrar a aba correta
    function showTab(tabId) {
        tabContents.forEach(content => {
            content.classList.remove("active");
        });
        document.getElementById(tabId).classList.add("active");
    }

    // Adicionar evento de clique aos links das abas
    tabLinks.forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            tabLinks.forEach(l => l.classList.remove("active"));
            this.classList.add("active");
            showTab(this.dataset.tab);
        });
    });

    // Preencher a lista de desejos com placeholders
    function populateWishlistPlaceholders(count) {
        for (let i = 0; i < count; i++) {
            const placeholder = document.createElement("div");
            placeholder.classList.add("wishlist-item-placeholder");
            wishlistGridSidebar.appendChild(placeholder);
        }
    }

    // Inicializar a página
    showTab("meu-perfil"); // Mostrar a aba Meu Perfil por padrão
    populateWishlistPlaceholders(6); // Preencher 6 placeholders na lista de desejos da sidebar
});

