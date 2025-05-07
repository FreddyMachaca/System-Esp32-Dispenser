<?php  
require("../shared_new/header.php");
require("../conexion.php"); 

$dbh = new Conexion();
 
?>

<!-- ****************************** -->
<!-- CONTENIDO -->
<div class="flex-grow-1 container-p-y container-fluid pt-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mt-2">
        <div class="d-flex flex-column justify-content-center gap-2 gap-sm-0">
            <h4 class="mb-2">Productos</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-t">
                    <a href="./lista.php" class="router-link-active" style="color: black;">Lista de Productos</a>
                </li>
                <li class="breadcrumb-item text-t">
                    <b>Registrar Producto</b>
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form method="POST" id="formulario-producto-registro">
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <!-- DATOS GENERALES -->
                                <div class="form-group col-lg-12 mb-2"
                                    style="background-color: #f2f2f2; padding: 10px; border-radius: 5px; display: flex; align-items: center;">
                                    <div style="flex-grow: 1;">
                                        <h5 class="card-title mb-1 font-medium-2" style="margin-bottom: 0;">
                                            <i class="fas fa-box text-success"></i> Datos del Producto
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-md-2">

                                </div>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label for="nombre" class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del producto" required>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="precio" class="form-label">Precio (Bs.) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0.01" placeholder="0.00" required>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <label for="descripcion" class="form-label">Descripción</label>
                                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción del producto"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="floating-buttons">
                                <button type="submit" class="btn btn-primary" id="guardarRegistro">
                                    Guardar
                                </button>
                                <button type="button" class="btn btn-danger" id="cancelar-btn">
                                    Cancelar
                                </button>
                            </div>

                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>
<!-- ****************************** -->
<style>
.btn_borde {
    padding-left: 5px;
    padding-right: 5px;

}
</style>
<?php
require("../shared_new/script_js.php");
?>
<script>
$(document).ready(function() {
    $('#cancelar-btn').on('click', function(event) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Si sales del formulario, se perderán los cambios realizados.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-primary me-3',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                window.location.href = './lista.php';
            }
        });
    });

    $('#formulario-producto-registro').on('submit', function(event) {
        event.preventDefault();
        
        if (!$('#nombre').val() || !$('#precio').val()) {
            Swal.fire({
                icon: 'error',
                title: 'Campos obligatorios',
                text: 'Los campos marcados con * son obligatorios.',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
            return;
        }

        const precio = parseFloat($('#precio').val());
        if (isNaN(precio) || precio <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Precio inválido',
                text: 'El precio debe ser un número mayor a 0.',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
            return;
        }

        let formData = new FormData(this);
        
        $.ajax({
            url: './ajax_registrar_producto.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Producto registrado!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(function() {
                        window.location.href = './lista.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonText: 'Aceptar',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error del servidor',
                    text: 'Ocurrió un error al procesar la solicitud.',
                    confirmButtonText: 'Aceptar',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
                console.error(xhr.responseText);
            }
        });
    });
});
</script>
<?php
require("../shared_new/footer.php");
?>