<div class="contenedor olvide">
<?php include_once __DIR__ . '/../templates/nombre-sitio.php' ; ?>
<div class="contenido-sm">
    <p class="descripcion-pagina">Agrega tu email para poder reestablecer tu Password</p>
    <?php include_once __DIR__ . '/../templates/alertas.php' ; ?>
    <form class="formulario" method="POST" action="/olvide" novalidate >
   

    <div class="campo">
            <label for="email">Email</label>
            <input type="email"
            id="email"
            placeholder ="Tu email"
            name="email"
            >
            </div>
           
           

        <input type="submit" class="boton" value="enviar indicaciones">
    </form>
    <div class="acciones">
        <a href="/"> ¿Ya tienes cuenta? Iniciar Sesion</a>
        <a href="/crear"> ¿Aun no tienes una cuenta? Crea Una</a>
    
</div>

</div>