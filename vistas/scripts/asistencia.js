var tabla;

// Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    });

    $("#formulario_asis").on("submit", function (e) {
        guardaryeditar_asis(e);
    });

    $("#imagenmuestra").hide();
}

// Función para verificar el estado de asistencia de un alumno
function verificar(id) {
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    var idgrupo = $("#idgrupo").val();

    $.post("../ajax/asistencia.php?op=verificar", { fecha_asistencia: today, alumn_id: id, idgrupo: idgrupo },
        function (data, status) {
            data = JSON.parse(data);
            if (data == null && $("#tipo_asistencia").val() != "") {
                $("#getCodeModal").modal('show');
                $.post("../ajax/alumnos.php?op=mostrar", { idalumno: id },
                    function (data, status) {
                        data = JSON.parse(data);
                        $("#alumn_id").val(data.id);
                    });
            } else if (data != null && $("#tipo_asistencia").val() != "") {
                $("#getCodeModal").modal('show');
                $.post("../ajax/asistencia.php?op=verificar", { fecha_asistencia: today, alumn_id: id, idgrupo: idgrupo },
                    function (data, status) {
                        data = JSON.parse(data);
                        $("#idasistencia").val(data.id);
                        $("#alumn_id").val(data.alumn_id);
                        $("#tipo_asistencia").val(data.kind_id);
                        $("#tipo_asistencia").selectpicker('refresh');
                    });
            } else if ($("#tipo_asistencia").val() == "") {
                alert('Selecciona un tipo de asistencia');
            }
        })
    limpiar();
}

// Función para limpiar el formulario
function limpiar() {
    $("#idasistencia").val("");
    $("#alumn_id").val("");
    $("#tipo_asistencia").val("");
    $("#tipo_asistencia").selectpicker('refresh');

    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $("#fecha_asistencia").val(today);
    $('#getCodeModal').modal('hide');
}

// Función para mostrar el formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
    } else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
}

// Función para cancelar el formulario
function cancelarform() {
    limpiar();
    mostrarform(false);
}

// Función para listar las asistencias con columnas específicas
function listar() {
    var team_id = $("#idgrupo").val();
    tabla = $('#tbllistado').dataTable({
        "aProcessing": true, // Activar procesamiento del DataTable
        "aServerSide": true, // Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip', // Definir los elementos del control de la tabla
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../ajax/asistencia.php?op=listar',
            data: { idgrupo: team_id },
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "columns": [
            { "data": "name", "title": "Nombre" },  // Columna para el nombre del alumno
            { "data": "lastname", "title": "Apellido" },  // Columna para el apellido del alumno
            { "data": "phone", "title": "Teléfono" },  // Columna para el teléfono del alumno
            {
                "data": "tipo_asistencia",
                "title": "Estado de Asistencia",
                "render": function (data, type, row) {
                    // Renderizar el estado de asistencia en lugar de un botón
                    var estado = "";
                    switch (data) {
                        case "1": estado = "Asistencia"; break;
                        case "2": estado = "Tardanza"; break;
                        case "3": estado = "Faltante"; break;
                        case "4": estado = "Permiso"; break;
                        default: estado = "Sin Registro"; break;
                    }
                    return estado;
                }
            }
        ],
        "bDestroy": true, // Destruir la tabla existente al recargar
        "iDisplayLength": 10, // Paginación
        "order": [[0, "asc"]] // Ordenar por nombre de alumno (columna, orden)
    }).DataTable();
}

// Función para guardar o editar la asistencia
function guardaryeditar_asis(e) {
    e.preventDefault(); // No se activará la acción predeterminada
    $("#btnGuardar_asis").prop("disabled", false);
    var formData = new FormData($("#formulario_asis")[0]);

    $.ajax({
        url: "../ajax/asistencia.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            bootbox.alert(datos);
            mostrarform(false);
            tabla.ajax.reload();
        }
    });

    limpiar();
}

init();
