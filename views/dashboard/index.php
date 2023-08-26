
<?php include_once __DIR__ . '/header-dashboard.php' ?>

<?php if(count($proyectos)=== 0) {?>
<p class="no-proyectos">No hay proyectos aun <a href="/crear-proyectos">Crea uno</a></p>
    <?php }else {?>
      <ul class="listado-proyectos">
         
      <?php foreach($proyectos as $proyecto) { ?>
        <li class="proyecto">
            <a href="/proyecto?id=<?php echo $proyecto->url;?>">
            <form action="/proyecto/eliminar" method="POST" >
            <input type="hidden" name="id" value="<?php echo $proyecto->id ?>">
            <input type="button" class="btn btn-eliminar" value="Eliminar"/>
           
            </form>
              
            <?php echo $proyecto->proyecto;?>        
        </a>
        </li>
        <?php } ?>

      </ul>

      <?php } ?>
<?php include_once __DIR__ . '/footer-dashboard.php' ?>
<?php
$script .=  
'<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="build/js/app.js"></script>
'

?>
