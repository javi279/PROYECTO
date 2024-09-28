<?php
require "../config/Conexion.php";

class Actividad {

    // Constructor
    public function __construct() {
    }

    // Método para insertar una nueva actividad
    public function insertar($nombre, $descripcion, $block_id) {
        $sql = "INSERT INTO actividad (nombre, descripcion, block_id) 
                VALUES ('$nombre', '$descripcion', '$block_id')";
        return ejecutarConsulta($sql);
    }

    // Método para editar una actividad
    public function editar($id_actividad, $nombre, $descripcion, $block_id) {
        $sql = "UPDATE actividad 
                SET nombre='$nombre', descripcion='$descripcion', block_id='$block_id' 
                WHERE id_actividad='$id_actividad'"; // Cambia 'id' por 'id_actividad'
        return ejecutarConsulta($sql);
    }

    // Método para mostrar los detalles de una actividad
    public function mostrar($id_actividad) {
        $sql = "SELECT * FROM actividad WHERE id_actividad='$id_actividad'"; // Cambia 'id' por 'id_actividad'
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar las actividades relacionadas a un proyecto (block_id)
    public function listar($block_id) {
        $sql = "SELECT a.id_actividad, a.nombre, a.descripcion, c.nombre AS proyecto, a.is_active
                FROM actividad a 
                JOIN curso c ON a.block_id = c.idcurso 
                WHERE a.block_id='$block_id'"; // Cambia 'idcurso' por 'block_id'
        return ejecutarConsulta($sql);
    }

    // Método para desactivar una actividad
    public function desactivar($id_actividad) {
        $sql = "UPDATE actividad SET is_active='0' WHERE id_actividad='$id_actividad'"; // Cambia 'id' por 'id_actividad'
        return ejecutarConsulta($sql);
    }

    // Método para activar una actividad
    public function activar($id_actividad) {
        $sql = "UPDATE actividad SET is_active='1' WHERE id_actividad='$id_actividad'"; // Cambia 'id' por 'id_actividad'
        return ejecutarConsulta($sql);
    }
}
?>
