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
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h1 class="box-title">Beneficiarios asignados a la actividad</h1>
                            <div class="box-tools pull-right">
                                <button class="btn btn-success" onclick="mostrarBeneficiarios()">
                                    <i class="fa fa-plus-circle"></i> Mostrar Beneficiarios
                                </button>
                                <!-- Campo oculto para almacenar el id de la actividad -->
                                <input type="hidden" id="idactividad" name="idactividad" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
                            </div>
                        </div>
                        
                        <!-- Panel para mostrar beneficiarios relacionados -->
                        <div class="panel-body table-responsive" id="listadoregistros">
                            <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                                <thead>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Acciones</th>
                                </thead>
                                <tbody>
                                    <!-- Aquí se insertarán las filas dinámicamente -->
                                </tbody>
                                <tfoot>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Acciones</th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>

<?php 
    } else {
        require 'noacceso.php'; 
    }
    require 'footer.php';
?>
<!-- Script de la vista -->
<script src="scripts/actividad_detalle.js"></script>

<?php
}
ob_end_flush();
?>
