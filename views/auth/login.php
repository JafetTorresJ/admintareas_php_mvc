<div class="contenedor crear">
<?php include_once __DIR__ . '/../templates/nombre-sitio.php' ; ?>
<div class="contenido-sm">


    <p class="descripcion-pagina">Inicia sesion en AdminTareas</p>
    <?php include_once __DIR__ . '/../templates/alertas.php' ; ?>
    <form class="formulario" method="POST" action="/" novalidate>
   

    <div class="campo">
            <label for="email">Email</label>
            <input type="email"
            id="email"
            placeholder ="Tu email"
            name="email"
            >
            </div>
            <div class="campo">
            <label for="password">Password</label>
            <input type="password"
            id="password"
            placeholder ="Tu Password"
            name="password"
            >
            </div>
       
        <input type="submit" class="boton" value="Iniciar sesion">
    </form>
    <div class="acciones">
        <a href="/crear"> ¿Aun no tienes una cuenta? crea una</a>
        <a href="/olvide"> ¿Olvidaste tu Password?</a>
    
</div>

</div>