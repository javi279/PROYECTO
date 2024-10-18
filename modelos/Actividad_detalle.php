<?php
require "../config/Conexion.php";

class Actividad_detalle {
    public function __construct() {}

    public function insertar($id_actividad, $id_beneficiario) {
        $sql = "INSERT INTO actividad_detalle (id_actividad, id_beneficiario) VALUES ('$id_actividad', '$id_beneficiario')";
        return ejecutarConsulta($sql);
    }

    public function editar($id_actividad_detalle, $id_actividad, $id_beneficiario) {
        $sql = "UPDATE actividad_detalle SET id_actividad='$id_actividad', id_beneficiario='$id_beneficiario' WHERE id_actividad_detalle='$id_actividad_detalle'";
        return ejecutarConsulta($sql);
    }

    public function verificar($id_beneficiario, $id_actividad) {
        $sql = "SELECT * FROM actividad_detalle WHERE id_beneficiario='$id_beneficiario' AND id_actividad='$id_actividad'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listar($team_id) {
        $sql = "SELECT a.id, a.name, a.lastname, a.phone, a.image 
                FROM alumn a 
                JOIN alumn_team at ON a.id = at.alumn_id
                WHERE at.team_id = '$team_id'";
        return ejecutarConsulta($sql);
    }
    
}
?>
