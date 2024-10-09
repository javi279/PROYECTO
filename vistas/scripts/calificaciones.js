var tabla;

// Función que se ejecuta al inicio
function init() {
    var team_id = $("#idgrupo").val();
    listar();

    // Cargar los items al select cliente
    $.post("../ajax/cursos.php?op=selectCursos", { idgrupo: team_id }, function (r) {
        $("#curso").html(r);
        $('#curso').selectpicker('refresh');
    });

    // Guardar o editar al enviar el formulario
    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    });
}

// Capturamos el id del curso la hacer cambio en el select curso
$("#curso").change(function () {
    var idcurso = $("#curso").val();
    $("#idcurso").val(idcurso);
    listar();
});

// Función para verificar si ya se ingresó una calificación de un curso
function verificar(id) {
    var idcurso = $("#idcurso").val();

    $.post("../ajax/calificaciones.php?op=verificar", { alumn_id: id, idcurso: idcurso },
        function (data, status) {
            data = JSON.parse(data);
            if (data == null && $("#idcurso").val() != 0) {
                $("#getCodeModal").modal('show');
                $.post("../ajax/alumnos.php?op=mostrar", { idalumno: id },
                    function (data, status) {
                        data = JSON.parse(data);
                        $("#alumn_id").val(data.id);
                    });
            } else if (data != null && $("#idcurso").val() != 0) {
                $("#getCodeModal").modal('show');
                $.post("../ajax/calificaciones.php?op=verificar", { alumn_id: id, idcurso: idcurso },
                    function (data, status) {
                        data = JSON.parse(data);
                        $("#idcalificacion").val(data.id);
                        $("#alumn_id").val(data.alumn_id);
                        $("#valor").val(data.val);
                        $("#idcurso").val(data.block_id);
                    });
            } else if ($("#idcurso").val() == 0) {
                bootbox.alert('Selecciona un curso');
            }
        });
    limpiar();
}

// Función para limpiar el formulario
function limpiar() {
    $("#idcalificacion").val("");
    $("#alumn_id").val("");
    $("#valor").val("");
    $("#curso").selectpicker('refresh');
    $('#getCodeModal').modal('hide');
}

// Función para listar las calificaciones y personalizar las columnas
function listar() {
    var team_id = $("#idgrupo").val();
    tabla = $('#tbllistado').dataTable({
        "aProcessing": true, // Activamos el procedimiento del datatable
        "aServerSide": true, // Paginación y filtrado realizados por el server
        dom: 'Bfrtip', // Definimos los elementos del control de la tabla
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../ajax/calificaciones.php?op=listar',
            data: { idgrupo: team_id },
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "columns": [
            { "data": "nombre", "title": "Nombre" }, // Agregar columna para el nombre del alumno
            { "data": "lastname", "title": "Apellido" }, // Columna para el apellido del alumno
            { "data": "phone", "title": "Teléfono" }, // Columna para el teléfono del alumno
            { "data": "curso", "title": "Curso" }, // Columna del curso
            { "data": "valor", "title": "Calificación" } // Columna para la calificación
        ],
        "bDestroy": true, // Destruir la tabla existente al recargar
        "iDisplayLength": 10, // Paginación
        "order": [[0, "asc"]] // Ordenar por nombre (columna, orden)
    }).DataTable();
}

// Función para guardar o editar las calificaciones
function guardaryeditar(e) {
    e.preventDefault(); // No se activará la acción predeterminada
    $("#btnGuardar").prop("disabled", false);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/calificaciones.php?op=guardaryeditar",
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
