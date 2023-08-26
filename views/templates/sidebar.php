<aside class="sidebar">

<div class="contenedor-sidebar">
<h2>UpTask</h2>
<div class="cerrar-menu">
        <img src="build/img/cross.png" alt="imagen cerrar menu" id="cerrar-menu">

    </div>


</div>
<nav class="sidebar-nav">
    <a class="<?php echo ($titulo === 'Proyectos') ? 'activo' : ''; ?>" href="/dashboard">Proyectos</a>
    <a  class="<?php echo ($titulo === 'Crear Proyectos') ? 'activo' : ''; ?>" href="/crear-proyectos">Crear proyectos</a>
    <a class="<?php echo ($titulo === 'Tu Perfil') ? 'activo' : ''; ?>" href="/perfil">Ver perfil</a>
    
</nav>
<div class="cerrar-sesion-mobile">
    <a href="/logout" class="cerrar-sesion">Cerrar sesion</a>
</div>
</aside>


