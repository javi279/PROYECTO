<?php 
require_once "../modelos/Actividad_detalle.php";
if (strlen(session_id()) < 1) 
    session_start(); 

$actividad_detalle = new Actividad_detalle();

$id_actividad = isset($_POST["id_actividad"]) ? limpiarCadena($_POST["id_actividad"]) : "";
$alumn_id = isset($_POST["alumn_id"]) ? limpiarCadena($_POST["alumn_id"]) : "";
$user_id = $_SESSION["idusuario"]; // Usuario en sesión

switch ($_GET["op"]) {
    case 'asignarBeneficiario':
        $idactividad = $_POST['idactividad'];
        $id_beneficiario = $_POST['id_beneficiario'];
    
        // Verificar si ya existe el beneficiario para la actividad
        $rspta = $actividad_detalle->verificar($id_beneficiario, $idactividad);
        
        if ($rspta == null) {
            // Si no existe, lo asignamos
            $rspta = $actividad_detalle->insertar($idactividad, $id_beneficiario);
            echo $rspta ? "Beneficiario asignado exitosamente" : "No se pudo asignar el beneficiario";
        } else {
            echo "El beneficiario ya está asignado a esta actividad";
        }
        break;
    
    case 'listar':
        require_once "../modelos/Alumnos.php";
        $alumnos = new Alumnos();
    
        $idactividad = isset($_POST['idactividad']) ? $_POST['idactividad'] : '';  // Asegúrate de capturar el id de la actividad correctamente
        
        // Comprobar que se reciba correctamente el ID de la actividad
        if (!empty($idactividad)) {
            // Obtener la lista de beneficiarios por actividad
            $rspta = $alumnos->listarPorActividad($idactividad);
            
            // Preparar un array para almacenar los datos
            $response = array();
            while ($reg = $rspta->fetch_object()) {
                $response[] = array(
                    "id" => $reg->id,
                    "nombre" => $reg->name,
                    "apellido" => $reg->lastname
                );
            }

            // Devolver la lista de beneficiarios como JSON
            echo json_encode($response);
        } else {
            // Si no se recibe el ID de la actividad, devolver un mensaje vacío
            echo json_encode(array());
        }
        break;

        
}
?>
