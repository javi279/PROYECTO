<?php 
session_start();
require_once "../modelos/Proyecto.php";

$proyecto = new Proyecto();

$idproyecto = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
$fecha_inicio = isset($_POST["fecha_inicio"]) ? limpiarCadena($_POST["fecha_inicio"]) : "";
$fecha_fin = isset($_POST["fecha_fin"]) ? limpiarCadena($_POST["fecha_fin"]) : "";
$archivo_pdf = isset($_POST["archivo_pdf"]) ? limpiarCadena($_POST["archivo_pdf"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (!file_exists($_FILES['archivo_pdf']['tmp_name']) || !is_uploaded_file($_FILES['archivo_pdf']['tmp_name'])) {
            $archivo_pdf = $_POST["archivo_pdfactual"];
        } else {
            $ext = explode(".", $_FILES["archivo_pdf"]["name"]);
            if ($_FILES['archivo_pdf']['type'] == "application/pdf") {
                $archivo_pdf = round(microtime(true)) . '.' . end($ext);
                move_uploaded_file($_FILES["archivo_pdf"]["tmp_name"], "../files/proyectos/" . $archivo_pdf);
            }
        }

        if (empty($idproyecto)) {
            $rspta = $proyecto->insertar($nombre,$descripcion,$fecha_inicio,$fecha_fin,$archivo_pdf);
            echo $rspta ? "Proyecto registrado correctamente" : "No se pudo registrar el proyecto";
        } else {
            $rspta = $proyecto->editar($idproyecto,$nombre,$descripcion,$fecha_inicio,$fecha_fin,$archivo_pdf);
            echo $rspta ? "Proyecto actualizado correctamente" : "No se pudo actualizar el proyecto";
        }
        break;

        case 'listar':
            $rspta = $proyecto->listar(); // Función listar en el modelo
            $data = Array();
        
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0" => '<button class="btn btn-warning" onclick="mostrar(' . $reg->id . ')"><i class="fa fa-pencil"></i></button>'.
                           ' <button class="btn btn-danger" onclick="eliminar(' . $reg->id . ')"><i class="fa fa-trash"></i></button>',
                    "1" => $reg->nombre,
                    "2" => $reg->descripcion,
                    "3" => $reg->fecha_inicio,
                    "4" => $reg->fecha_fin,
                    "5" => '<a href="../files/proyectos/'.$reg->archivo_pdf.'" target="_blank">Ver PDF</a>',
                    "6" => ($reg->estado)?'<span class="label bg-green">Activo</span>':'<span class="label bg-red">Inactivo</span>'
                );
            }
            $results = array(
                "sEcho" => 1, // Información para el datatables
                "iTotalRecords" => count($data), // Enviamos el total de registros al datatable
                "iTotalDisplayRecords" => count($data), // Enviamos el total de registros a visualizar
                "aaData" => $data);
            echo json_encode($results);
        break;

    case 'mostrar':
        $rspta = $proyecto->mostrar($idproyecto);
        echo json_encode($rspta);
        break;

    case 'desactivar':
        $rspta = $proyecto->desactivar($idproyecto);
        echo $rspta ? "Proyecto desactivado correctamente" : "No se pudo desactivar el proyecto";
        break;
}
?>