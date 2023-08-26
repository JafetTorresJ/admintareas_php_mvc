<?php 

namespace Model;

class Proyecto extends ActiveRecord{
    protected static $tabla = 'proyectos';
    protected static $columnasDB =['id', 'propietarioId', 'proyecto', 'url'];


    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->propietarioId = $args['propietarioId'] ?? '';
        $this->proyecto = $args['proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
    }
    public function validarProyecto(){
        if(!$this->proyecto){
            self::$alertas['error'][] = 'El nombre del proyecto es obligatorio';
        }
        return self::$alertas;
    }
}