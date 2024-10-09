<?php
require "../config/Conexion.php";

class Actividad {
    // Constructor
    public function __construct() {}

    // Método para insertar una nueva actividad con `alumn_id`
    public function insertar($nombre, $descripcion, $block_id, $fecha_limite, $alumn_id) {
        $sql = "INSERT INTO actividad (nombre, descripcion, block_id, fecha_limite) 
                VALUES ('$nombre', '$descripcion', '$block_id', '$fecha_limite');";
        $rspta = ejecutarConsulta_retornarID($sql); // Obtener el ID de la actividad insertada

        if ($rspta) {
            // Insertar en la tabla de relación con alumnos
            $sql_alumno = "INSERT INTO actividad_alumnos (id_actividad, alumn_id) 
                           VALUES ('$rspta', '$alumn_id')";
            ejecutarConsulta($sql_alumno);
        }

        return $rspta ? $rspta : false;
    }

    // Método para editar una actividad y actualizar el `alumn_id`
    public function editar($id_actividad, $nombre, $descripcion, $block_id, $fecha_limite, $alumn_id) {
        $sql = "UPDATE actividad 
                SET nombre='$nombre', descripcion='$descripcion', block_id='$block_id', fecha_limite='$fecha_limite' 
                WHERE id_actividad='$id_actividad'";
        $rspta = ejecutarConsulta($sql);

        if ($rspta) {
            // Actualizar en la tabla de relación con alumnos
            $sql_alumno = "UPDATE actividad_alumnos 
                           SET alumn_id='$alumn_id' 
                           WHERE id_actividad='$id_actividad'";
            ejecutarConsulta($sql_alumno);
        }

        return $rspta ? $rspta : false;
    }

    // Método para mostrar los detalles de una actividad específica
    public function mostrar($id_actividad) {
        $sql = "SELECT * FROM actividad WHERE id_actividad='$id_actividad'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar actividades por `block_id`
    public function listar($block_id) {
        $sql = "SELECT id_actividad, nombre, descripcion, block_id, fecha_limite, is_active
                FROM actividad
                WHERE block_id = '$block_id'";
        return ejecutarConsulta($sql);
    }

    // Método para desactivar una actividad
    public function desactivar($id_actividad) {
        $sql = "UPDATE actividad SET is_active='0' WHERE id_actividad='$id_actividad'";
        return ejecutarConsulta($sql);
    }

    // Método para activar una actividad
    public function activar($id_actividad) {
        $sql = "UPDATE actividad SET is_active='1' WHERE id_actividad='$id_actividad'";
        return ejecutarConsulta($sql);
    }

    // Método para listar los alumnos activos de un grupo específico
    public function listarAlumnosPorGrupo($team_id) {
        $sql = "SELECT alumn_id, CONCAT(nombre, ' ', apellido) AS nombre 
                FROM alumn 
                WHERE is_active = 1 AND team_id = '$team_id'";
        return ejecutarConsulta($sql);
    }

    // Función para listar actividades dentro de un rango de fechas
    public function listar_actividades_rango($fecha_inicio, $fecha_fin) {
        $sql = "SELECT nombre AS nombre_actividad, descripcion, fecha_actividad 
                FROM actividad 
                WHERE fecha_actividad BETWEEN '$fecha_inicio' AND '$fecha_fin'
                ORDER BY fecha_actividad ASC";
        return ejecutarConsulta($sql);
    }
}
?>
