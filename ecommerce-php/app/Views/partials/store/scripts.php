<script src="<?php echo asset_url('js/menu-lateral.js'); ?>"></script>
<script>
    const perfilLink = document.getElementById('perfil-link');
    const perfilDropdown = document.getElementById('perfil-dropdown');

    if (perfilLink && perfilDropdown) {
        perfilLink.addEventListener('click', function(event) {
            if ('<?php echo $usuario['logado'] ? 'true' : 'false'; ?>' === 'true') {
                event.preventDefault();
                perfilDropdown.classList.toggle('show');
            }
        });

        document.addEventListener('click', function(event) {
            if (!perfilLink.contains(event.target) && !perfilDropdown.contains(event.target)) {
                perfilDropdown.classList.remove('show');
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