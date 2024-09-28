<?php 
require_once "../modelos/Actividad.php";

if (strlen(session_id()) < 1) 
    session_start(); 

$actividad = new Actividad();

$id_actividad = isset($_POST["id_actividad"]) ? limpiarCadena($_POST["id_actividad"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
$block_id = isset($_POST["idcurso"]) ? limpiarCadena($_POST["idcurso"]) : ""; // Asegúrate de que el nombre sea correcto

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($id_actividad)) {
            $rspta = $actividad->insertar($nombre, $descripcion, $block_id); 
            echo $rspta ? "Actividad registrada correctamente" : "No se pudo registrar la actividad";
        } else {
            $rspta = $actividad->editar($id_actividad, $nombre, $descripcion, $block_id);
            echo $rspta ? "Actividad actualizada correctamente" : "No se pudo actualizar la actividad"; 
        }
        break;

    case 'desactivar':
        $rspta = $actividad->desactivar($id_actividad);
        echo $rspta ? "Actividad desactivada correctamente" : "No se pudo desactivar la actividad";
        break;

    case 'activar':
        $rspta = $actividad->activar($id_actividad);
        echo $rspta ? "Actividad activada correctamente" : "No se pudo activar la actividad";
        break;

    case 'mostrar':
        $rspta = $actividad->mostrar($id_actividad);
        echo json_encode($rspta);
        break;

    case 'listar':
        // Asegúrate de que el idcurso se pasa correctamente
        $block_id = isset($_POST['idcurso']) ? limpiarCadena($_POST['idcurso']) : ""; // Define idcurso
        $rspta = $actividad->listar($id_actividad); // Usa el block_id

        $data = array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => ($reg->is_active) 
                    ? '<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->id_actividad.')"><i class="fa fa-pencil"></i></button>' 
                    . ' <button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->id_actividad.')"><i class="fa fa-close"></i></button>' 
                    : '<button class="btn btn-primary btn-xs" onclick="activar('.$reg->id_actividad.')"><i class="fa fa-check"></i></button>',
                "1" => $reg->nombre,
                "2" => $reg->descripcion,
                "3" => $reg->proyecto
            );
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        echo json_encode($results);
        break;
}
?>
