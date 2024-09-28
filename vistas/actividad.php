<?php
// Activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: login.html");
} else {
    require 'header.php';

    if ($_SESSION['grupos'] == 1) {
?>
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h1 class="box-title">Selecciona un Proyecto</h1>
                            <div class="box-tools pull-right">
                                <a href="../vistas/vista_grupo.php?idgrupo=<?php echo $_GET["idgrupo"]; ?>">
                                    <button class="btn btn-success"><i class='fa fa-arrow-circle-left'></i> Volver</button>
                                </a>
                                <input type="hidden" id="idgrupo" name="idgrupo" value="<?php echo $_GET["idgrupo"]; ?>">
                            </div>
                        </div>
                        <!-- box-header -->

                        <!-- Lista desplegable de proyectos (del select principal) -->
                        <div class="form-inline col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <select name="curso" id="curso" class="form-control selectpicker" data-live-search="true" required>
                                <option value="">Seleccione un proyecto</option>
                            </select>
                        </div>

                        <!-- Formulario para agregar/editar actividades -->
                        <div class="panel-body">
                            <form action="" name="formulario" id="formulario" method="POST">
                                <div class="form-group">
                                    <input type="hidden" id="idactividad" name="idactividad">
                                    <input type="hidden" id="idcurso" name="idcurso">
                                    <label for="nombre">Nombre(*):</label>
                                    <input class="form-control" type="text" id="nombre" name="nombre" required>
                                </div>
                                <div class="form-group">
                                    <label for="descripcion">Descripción(*):</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                                    <button class="btn btn-danger" type="button" id="btnCancelar" onclick="limpiar();"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                                </div>
                            </form>
                        </div>

                        <!-- Tabla de listado de actividades -->
                        <div class="panel-body table-responsive" id="listadoregistros">
                            <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                                <thead>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Proyecto</th>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Proyecto</th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

<?php 
    } else {
        require 'noacceso.php'; 
    }
    require 'footer.php';
?>

<script src="scripts/actividad.js"></script>

<script>
    // Función para inicializar la página y cargar los datos
    $(document).ready(function () {
        cargarProyectos(); // Cargar los proyectos en el select principal
        listar(); // Mostrar las actividades en la tabla
    });

    // Al cambiar el select del proyecto (curso), recargar la tabla
    $("#curso").change(function () {
        var idcurso = $("#curso").val(); // Obtener el id del proyecto seleccionado
        $("#idcurso").val(idcurso); // Asignarlo al campo oculto del formulario
        listar(); // Recargar la tabla de actividades
    });
</script>

<?php 
}
ob_end_flush();
?>
