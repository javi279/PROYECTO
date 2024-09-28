var tabla;

// Función que se ejecuta al inicio
function init() {
    var team_id = $("#idgrupo").val();
    listar();

    // Cargamos los proyectos (curso) en el select de la vista principal
    $.post("../ajax/cursos.php?op=selectCursos", { idgrupo: team_id }, function (r) {
        $("#curso").html(r);
        $('#curso').selectpicker('refresh');
    });

    // Guardar o editar actividades
    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    });
}

// Al cambiar el select del curso (proyecto)
$("#curso").change(function () {
    var idcurso = $("#curso").val();
    $("#idcurso").val(idcurso);
    listar();
});

// Función para limpiar los formularios
function limpiar() {
    $("#id_actividad").val("");
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#curso").selectpicker('refresh');
    $('#modalActividad').modal('hide');
}

// Función para listar actividades vinculadas al proyecto
function listar() {
    var team_id = $("#idgrupo").val();
    var block_id = $("#block_id").val();
    
    tabla = $('#tbllistado').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../ajax/actividad.php?op=listar',
            data: { idcurso: block_id },
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
    }).DataTable();
}

// Función para guardar o editar
function guardaryeditar(e) {
    e.preventDefault();
    $("#btnGuardar").prop("disabled", false);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/actividad.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            bootbox.alert(datos);
            tabla.ajax.reload();
            limpiar();
        }
    });
}

// Función para mostrar el formulario
function mostrarform(flag) {
    $("#listadoregistros").toggle(!flag);
    $("#formulario")[0].reset();
    $("#modalActividad").modal('show');
}

// Función para cargar proyectos (cursos) en el modal
function cargarProyectos() {
    $.ajax({
        url: '../ajax/cursos.php?op=selectCursos',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            var selectModal = $('#curso_modal');
            selectModal.empty(); // Limpia el select del modal

            // Agregar opción por defecto
            selectModal.append('<option value="">Seleccione un curso</option>');

            // Verificar si se obtuvieron datos
            if (data.length > 0) {
                $.each(data, function (index, item) {
                    selectModal.append($('<option>', { 
                        value: item.idcurso,
                        text: item.nombre 
                    }));
                });
            } else {
                console.error("No se encontraron proyectos para cargar.");
            }

            // Refrescar el selectpicker
            selectModal.selectpicker('refresh');
        },
        error: function (error) {
            console.error("Error al cargar proyectos:", error.responseText);
        }
    });
}


// Cargar proyectos al abrir el modal
$('#modalActividad').on('show.bs.modal', function () {
    cargarProyectos(); // Carga los proyectos al abrir el modal
});

// Inicializar la funcionalidad
init();
