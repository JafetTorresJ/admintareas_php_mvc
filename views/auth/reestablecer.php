<div class="contenedor reestablecer">
<?php include_once __DIR__ . '/../templates/nombre-sitio.php' ; ?>
<div class="contenido-sm">
    <p class="descripcion-pagina">Coloca tu nuevo password</p>
    <?php include_once __DIR__ . '/../templates/alertas.php' ; ?>
    
    <?php if($mostrar) { ?>
    <form class="formulario" method="POST">
   

    <div class="campo">
            <label for="password">Tu nuevo password</label>
            <input type="password"
            id="password"
            placeholder ="Tu nuevo password"
            name="password"
            >
            </div>
           
           

        <input type="submit" class="boton" value="guardar nuevo password">
    </form>
<?php  }?>

    <div class="acciones">
        <a href="/"> ¿Ya tienes cuenta? Iniciar Sesion</a>
        <a href="/crear"> ¿Aun no tienes una cuenta? Crea Una</a>
    
</div>

</div>