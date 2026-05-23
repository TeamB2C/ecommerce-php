<script src="<?php echo asset_url('js/menu-lateral.js'); ?>"></script>
<script>
    const perfilLink = document.getElementById('perfil-link');
    const perfilDropdown = document.getElementById('perfil-dropdown');

    if (perfilLink && perfilDropdown) {
        perfilLink.addEventListener('click', function(event) {
            if ('<?php echo $usuario['logado'] ? 'true' : 'false'; ?>' === 'true') {
                event.preventDefault();
                event.stopPropagation();
                perfilDropdown.classList.toggle('ativo');
            }
        });

        perfilDropdown.addEventListener('click', function(event) {
            event.stopPropagation();
        });

        document.addEventListener('click', function(event) {
            if (!perfilLink.contains(event.target) && !perfilDropdown.contains(event.target)) {
                perfilDropdown.classList.remove('ativo');
            }
        });
    }

    function toggleMenu() {
        const menuLateral = document.getElementById('menuLateral');
        const overlay = document.getElementById('overlay');
        menuLateral.classList.toggle('ativo');
        overlay.classList.toggle('ativo');
    }
</script>
