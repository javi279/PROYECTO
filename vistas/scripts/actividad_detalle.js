var tabla;

function init() {
    listar();
    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    });
}

// Función listar
function listar() {
    var team_id = $("#team_id").val();
    tabla = $('#tbllistado').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf'],
        "ajax": {
            url: '../ajax/actividad_detalle.php?op=listar',
            data: { team_id: team_id },
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

// Función para verificar y asignar
function verificar(id_beneficiario) {
    var id_actividad = $("#id_actividad").val();

    $.post("../ajax/actividad_detalle.php?op=verificar", { id_beneficiario: id_beneficiario, id_actividad: id_actividad }, function (data, status) {
        bootbox.alert(data);  // Mostrar mensaje de éxito o error
    });
}

// Función para guardar o editar
function guardaryeditar(e) {
    e.preventDefault(); 
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/actividad_detalle.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            bootbox.alert(datos);
            tabla.ajax.reload();
        }
    });

    limpiar();
}

function limpiar() {
    $("#id_actividad_detalle").val("");
    $("#id_actividad").val("");
    $("#id_beneficiario").val("");
}


function mostrarBeneficiarios() {
    var idactividad = $("#idactividad").val(); // Obtén el valor del input hidden

    $.post("../ajax/actividad_detalle.php?op=listar", { idactividad: idactividad }, function(data) {
        var beneficiarios = JSON.parse(data); // Convertir la respuesta JSON a un objeto
        var html = "";

        // Verificar si la respuesta contiene datos
        if (beneficiarios.length > 0) {
            beneficiarios.forEach(function(beneficiario) {
                html += "<tr>" +
                            "<td>" + beneficiario.nombre + "</td>" +
                            "<td>" + beneficiario.apellido + "</td>" +
                            "<td><button class='btn btn-danger' onclick='eliminarBeneficiario(" + beneficiario.id + ")'>Eliminar</button></td>" +
                        "</tr>";
            });
        } else {
            html = "<tr><td colspan='3'>No hay beneficiarios asignados a esta actividad.</td></tr>";
        }

        $("#tbllistado tbody").html(html); // Actualizar la tabla con los datos recibidos
    });
}



function asignarBeneficiario(id_beneficiario) {
    var idactividad = $("#idactividad").val();

    $.post("../ajax/actividad_detalle.php?op=asignarBeneficiario", { idactividad: idactividad, id_beneficiario: id_beneficiario }, function(data) {
        alert(data); // Mostrar mensaje de éxito o error
        listar(); // Refrescar la lista de beneficiarios
    });
}



init();
