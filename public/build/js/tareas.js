(function(){
    obtenerTareas();
    let tareas =[];
    let filtradas = [];
//boton para mostrar el Modal de agregar tarea
const nuevaTareaBtn = document.querySelector('#agregar-tarea');

nuevaTareaBtn.addEventListener('click' , function(){
    mostrarFormulario();
});

// Filtros de busqueda
const filtros = document.querySelectorAll('#filtros input[type="radio"]');
filtros.forEach(radio =>{
radio.addEventListener('input', filtrarTareas);
});
function filtrarTareas(e){

    const filtro = e.target.value;

    if(filtro !== ''){
        filtradas = tareas.filter(tarea => tarea.estado == filtro)
    }else{
      filtradas = [];
    }
   mostrarTareas();
   
}
 
async function obtenerTareas(){
    try{
        const id = obtenerProyecto();
        const url = `/api/tareas?id=${id}`;
        const respuesta = await fetch(url);
        const resultado = await respuesta.json();
      
      
        tareas = resultado.tareas;
        mostrarTareas();
      
    }catch(error){
       console.log(error);
    }
}
function mostrarTareas(){
  limpiarTareas();
  totalPendientes();
  totalCompletadas();
  const arrayTareas = filtradas.length ? filtradas : tareas;
  

   if(arrayTareas.length === 0){
    const contenedorTareas = document.querySelector('#listado-tareas');

    const textoNoTareas = document.createElement('LI');
    textoNoTareas.textContent = 'No hay tareas aun';
    textoNoTareas.classList.add('no-tareas');

    contenedorTareas.appendChild(textoNoTareas);
    return;

   }
   const estados = {
    0: 'Pendiente',
    1: 'Completa'
   }
   arrayTareas.forEach(tarea => {
  const contenedorTarea = document.createElement('LI');
  contenedorTarea.dataset.tareaId = tarea.id;
  contenedorTarea.classList.add('tarea');

  const nombreTarea = document.createElement('P');
  nombreTarea.textContent = tarea.nombre;
  nombreTarea.ondblclick = function(){
    mostrarFormulario(editar = true, {...tarea} );
  }

  const opcionesDiv= document.createElement('DIV');
  opcionesDiv.classList.add('opciones');

  //crear los botones

  const btnEstadoTarea = document.createElement('BUTTON');
  btnEstadoTarea.classList.add('estado-tarea');
  btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
  btnEstadoTarea.textContent = estados[tarea.estado];
  btnEstadoTarea.dataset.estadoTarea = tarea.estado;
  btnEstadoTarea.ondblclick = function(){
    cambiarEstadoTarea({...tarea})
  }
  const btnEliminarTarea = document.createElement('BUTTON');
  btnEliminarTarea.classList.add('eliminar-tarea');
  btnEliminarTarea.dataset.idTarea = tarea.id;
  btnEliminarTarea.textContent = 'Eliminar';
  btnEliminarTarea.ondblclick = function(){
    confirmarTarea({...tarea});
  }

  opcionesDiv.appendChild(btnEstadoTarea);
  opcionesDiv.appendChild(btnEliminarTarea);

  contenedorTarea.appendChild(nombreTarea);
  contenedorTarea.appendChild(opcionesDiv);

   const listadoTareas = document.querySelector('#listado-tareas');
   listadoTareas.appendChild(contenedorTarea);

   });

}

function totalPendientes(){
    const totalPendientes = tareas.filter(tarea => tarea.estado === "0")
    const pendientesRadio = document.querySelector('#pendientes');
    if(totalPendientes.length === 0){
        pendientesRadio.disabled = true;
    }else{
        pendientesRadio.disabled = false;
    }
}

function totalCompletadas(){

    const totalCompletadas = tareas.filter(tarea => tarea.estado === "1");
    const completadasRadio = document.querySelector('#completadas');

    if(totalCompletadas.length === 0){
        completadasRadio.disabled = true;
    }else{
    completadasRadio.disabled = false;
    }

}

function mostrarFormulario(editar = false, tarea = {}){
   console.log(tarea);
   const modal = document.createElement('DIV');
   modal.classList.add('modal');

  modal.innerHTML=`
  
  <form class="formulario nueva-tarea">
  <legend>${editar ? 'Editar nombre de la tarea' : 'Añade una nueva tarea'}</legend>
  <div class="campo">
  <label>Tarea</label>

  <input 
  type="text"
  name="tarea"
  placeholder=" ${tarea.nombre ? 'edita el nombre d la tarea' : 'Añadir Tarea al Proyecto Actual'} "
  id="tarea"
  value="${tarea.nombre ? tarea.nombre : ''}"
    />
    </div>
    <div class="opciones">
    <input type="submit" class="submit-nueva-tarea" value="${editar ? 'Guardar el cambio' : 'Agregar Tarea'}"/>
    <button type="button" class="cerrar-modal">Cancelar</button>
    </div>
  </form>
  
  
  `;
  setTimeout(() => {
  const formulario = document.querySelector('.formulario');
  formulario.classList.add('animar');
  }, 1000);

modal.addEventListener('click', function(e){
    e.preventDefault();
    if(e.target.classList.contains('cerrar-modal')){
        const formulario = document.querySelector('.formulario');
  formulario.classList.add('cerrar');
        
        setTimeout(() => {
            modal.remove();
        }, 500);
    }

    if(e.target.classList.contains('submit-nueva-tarea')){
        const nombreTarea = document.querySelector('#tarea').value.trim();

        if(nombreTarea === ''){
            //mostrar una alerta de error si el usuario no ha puesto un nombre a la tarea
          mostrarAlerta('El nombre de la tarea es obligatorio y no puede ir vacio', 'error', 
          document.querySelector('.formulario legend'));
    
            return;
        }

        if(editar){
            tarea.nombre = nombreTarea;
           actualizarTarea(tarea);
        }else{
            agregarTarea(nombreTarea);
        }
     
    }

})


document.querySelector('.dashboard').appendChild(modal);
}


     //muestra un mensaje en la interfaz
    function mostrarAlerta(mensaje, tipo, referencia){
          //previene la creacion de multiples alertas
        const alertaPrevia = document.querySelector('.alerta');

        if(alertaPrevia){
            alertaPrevia.remove();
        }

       const alerta = document.createElement('DIV');
       alerta.classList.add('alerta', tipo);
       alerta.textContent = mensaje;
       

       //Inserta la alerta entre el legend y el div con la clase de Campo
       referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);
       

       //eliminar la alerta despues de 5 segundos
       setTimeout(() => {
        alerta.remove();
       }, 5000);
      
     

    }
    //Consulta el servidor para añadir una nueva tarea al proyecto actual

   async function agregarTarea(tarea){
          //construir la peticion
          const datos = new FormData();
          datos.append('nombre', tarea);
          datos.append('proyectoId', obtenerProyecto());
          
         

       

          try{
            //url a la que le vamos a enviar la peticion

             const url =`/api/tarea`;
             const respuesta =  await fetch(url, {
                method: 'POST',
                body: datos
      

             });
             const resultado = await respuesta.json();
             console.log(resultado);

             mostrarAlerta( resultado.mensaje, resultado.tipo, 
             document.querySelector('.formulario legend'));
            
             if(resultado.tipo === 'exito'){
                const modal = document.querySelector('.modal');
                setTimeout(() =>{
                    modal.remove();
                    
                }, 2000);

                //agregar el objeto de tareas al global de tareas
                const tareaObj ={

                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoId: resultado.proyectoId
                }
                tareas = [...tareas, tareaObj];
                mostrarTareas();
             }
            

          }catch(error){
            console.log(error);

          }
    }
    function cambiarEstadoTarea(tarea){
      
        
        const nuevoEstado = tarea.estado === "1" ? "0" : "1";
         
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);
    

        
    }
    async function actualizarTarea(tarea){
         
      
        const {estado, id , nombre, proyecto} =tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());
         
       try{

        const url = '/api/tarea/actualizar';

        const respuesta = await fetch(url, {
          method: 'POST',
          body: datos
        });
      const resultado = await respuesta.json();
     // console.log(resultado);
      if(resultado.respuesta.tipo === 'exito'){
      Swal.fire(
        resultado.respuesta.mensaje,
        resultado.respuesta.mensaje,
        'success'
      );
const modal = document.querySelector('.modal');
if(modal){
    modal.remove();
}


        tareas = tareas.map(tareaMemoria => {
            if(tareaMemoria.id === id){
              tareaMemoria.estado = estado;
              tareaMemoria.nombre = nombre;
            }
            return tareaMemoria;
            
        });

        mostrarTareas();
      }

       }catch(error){

       }
    }
    function confirmarTarea(tarea){
        Swal.fire({
            title: '¿Seguro que quieres eliminar la tarea?',
          
            showCancelButton: true,
            confirmButtonText: 'Si',
           cancelButtonText: 'No'
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
             eliminarTarea(tarea);
           
            }
          })

    }

    async function eliminarTarea(tarea){
        const {estado, id , nombre} = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());
         
          try{
            const url = '/api/tarea/eliminar';
            const respuesta = await fetch(url,{
                method: 'POST',
                body: datos
            } );

           

            const resultado = await respuesta.json();
         if(resultado.resultado ){
           // mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.contenedor-nueva-tarea') );
       

        Swal.fire('Eliminado!', resultado.mensaje, 'success');   
       
           tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id ); 
        mostrarTareas();
        }
          }catch(error){

          }
    }
    function obtenerProyecto(){
        const proyectoParamas =  new URLSearchParams(window.location.search);

        const proyecto = Object.fromEntries(proyectoParamas.entries());
          
        return proyecto.id;
    }
    function limpiarTareas(){
        const listadoTareas = document.querySelector('#listado-tareas');
        while(listadoTareas.firstChild){
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
    }
}

)();