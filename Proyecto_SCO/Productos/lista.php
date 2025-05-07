<?php  
require("../shared_new/header.php");
require("../conexion.php");
$dbh = new Conexion();

// Consultar los productos asignados a cada slot
$query_slots = "SELECT pm.slot_numero, pm.cantidad, p.nombre, p.precio, p.cod_producto, m.ubicacion 
                FROM productos_maquina pm 
                INNER JOIN productos p ON p.cod_producto = pm.cod_producto 
                INNER JOIN maquinas m ON m.cod_maquina = pm.cod_maquina
                WHERE pm.cod_estado = 1";
$stmt_slots = $dbh->prepare($query_slots);
$stmt_slots->execute();
$productos_slots = $stmt_slots->fetchAll(PDO::FETCH_ASSOC);

$slots_asignados = [];
foreach ($productos_slots as $producto) {
    $slots_asignados[$producto['slot_numero']] = $producto;
}
?>

<!-- ****************************** -->
<!-- CONTENIDO -->
<div class="flex-grow-1 container-p-y container-fluid pt-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mt-2">
        <div class="d-flex flex-column justify-content-center gap-2 gap-sm-0">
            <h4 class="mb-2">Productos</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-t"><b>Lista de Productos</b>
                </li>
            </ol>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-2 mb-2">
            <a href="./registrar_producto.php" class="btn btn-primary btn-md">
                <i class="feather ti ti-plus"></i> Nuevo Producto
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-datatable table-responsive p-3">
                        <table class="table table-hover mr-5 ml-5" id="tabla">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de tarjetas de productos -->
    <div class="d-flex flex-column mt-4">
        <h4 class="mb-2">Dispensador de Productos</h4>
        <div class="row">
            <?php
            for ($i = 1; $i <= 20; $i++) {
                // Verificar si el slot tiene un producto asignado
                $tiene_producto = isset($slots_asignados[$i]);
            ?>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100 <?php echo $tiene_producto ? 'border border-primary' : ''; ?>">
                    <div class="card-body text-center">
                        <div class="avatar avatar-md mx-auto mb-3">
                            <span class="avatar-initial rounded-circle <?php echo $tiene_producto ? 'bg-success' : 'bg-primary'; ?>">
                                <i class="ti <?php echo $tiene_producto ? 'ti-shopping-cart' : 'ti-package'; ?> fs-3"></i>
                            </span>
                        </div>
                        <h5 class="card-title">Slot <?php echo $i; ?></h5>
                        
                        <?php if ($tiene_producto): ?>
                            <div class="mb-3">
                                <h6 class="mb-1"><?php echo $slots_asignados[$i]['nombre']; ?></h6>
                                <p class="mb-1">Precio: <?php echo number_format($slots_asignados[$i]['precio'], 2); ?> Bs.</p>
                                <p class="mb-2">Cantidad: <?php echo $slots_asignados[$i]['cantidad']; ?> unidades</p>
                                <small class="text-muted"><?php echo $slots_asignados[$i]['ubicacion']; ?></small>
                            </div>
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-warning asignar-producto" data-slot="<?php echo $i; ?>">
                                    <i class="ti ti-edit me-1"></i> Editar
                                </button>
                                <button type="button" class="btn btn-danger quitar-producto" data-slot="<?php echo $i; ?>">
                                    <i class="ti ti-trash me-1"></i>
                                </button>
                            </div>
                        <?php else: ?>
                            <p class="card-text">No hay producto asignado a este slot</p>
                            <button type="button" class="btn btn-primary asignar-producto" data-slot="<?php echo $i; ?>">
                                Asignar Producto
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
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

<!-- Modal para asignar producto a slot -->
<div class="modal fade" id="modalAsignarProducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" id="formulario-asignar" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header bg-primary pb-3">
                    <h5 class="modal-title text-white" id="modalTitle">Asignar Producto a Slot</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="slot_numero" name="slot_numero">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="producto" class="form-label">Producto</label>
                            <select class="form-select" id="producto" name="producto" required>
                                <option value="" selected disabled>Seleccione un producto</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" required min="1" value="1">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="maquina" class="form-label">Máquina (Opcional)</label>
                            <select class="form-select" id="maquina" name="maquina">
                                <option value="" selected disabled>Seleccione una máquina</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary guardar-asignacion">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
require("../shared_new/script_js.php");
?>
<script>
$(document).ready(function() {
    var tabla = $('#tabla').DataTable({
        "ajax": {
            url: "./ajax_obtener_lista_productos.php",
            type: "POST"
        },
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        paging: true,
        searching: true,
        ordering: false,
        info: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
    });

    // Manejo del modal para asignar producto a slot
    $(document).on('click', '.asignar-producto', function() {
        let slotNumero = $(this).data('slot');
        $('#slot_numero').val(slotNumero);
        $('#modalTitle').text('Asignar Producto al Slot ' + slotNumero);
        
        cargarProductos();
        cargarMaquinas();
        
        $('#modalAsignarProducto').modal('show');
    });

    $(document).on('click', '.guardar-asignacion', function() {
        if (!$('#producto').val() || !$('#cantidad').val()) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El producto y la cantidad son obligatorios',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        let formData = new FormData(document.getElementById('formulario-asignar'));
        
        $.ajax({
            url: './ajax_asignar_producto_maquina.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Asignación exitosa!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        $('#modalAsignarProducto').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la petición AJAX:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error del servidor',
                    text: 'No se pudo procesar la solicitud. Inténtelo nuevamente más tarde.',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });

    // Función para cargar productos disponibles
    function cargarProductos() {
        $.ajax({
            url: './ajax_obtener_productos_select.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#producto').empty();
                $('#producto').append('<option value="" selected disabled>Seleccione un producto</option>');
                if (response.status && response.data.length > 0) {
                    $.each(response.data, function(index, producto) {
                        $('#producto').append('<option value="' + producto.cod_producto + '">' + producto.nombre + ' - ' + producto.precio + ' Bs.</option>');
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error cargando productos:', error);
            }
        });
    }

    // Función para cargar máquinas disponibles
    function cargarMaquinas() {
        $.ajax({
            url: './ajax_obtener_maquinas_select.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#maquina').empty();
                $('#maquina').append('<option value="" selected disabled>Seleccione una máquina</option>');
                if (response.status && response.data.length > 0) {
                    $.each(response.data, function(index, maquina) {
                        $('#maquina').append('<option value="' + maquina.cod_maquina + '">' + maquina.ubicacion + '</option>');
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error cargando máquinas:', error);
            }
        });
    }

    // Eliminar producto
    $(document).on('click', '.eliminar-producto', function() {
        let cod_producto = $(this).attr("id");
        
        Swal.fire({
            title: '¿Está seguro que desea eliminar este producto?',
            text: "Esta acción no se puede revertir",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-danger me-3',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                let formData = new FormData();
                formData.append('cod_producto', cod_producto);

                $.ajax({
                    url: './ajax_eliminar_producto.php',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Producto eliminado!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 2000
                            });

                            $('#tabla').DataTable().ajax.reload(null, false); 
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'No se pudo eliminar el producto',
                                text: response.message,
                                confirmButtonText: 'Aceptar',
                                customClass: {
                                    confirmButton: 'btn btn-warning'
                                },
                                buttonsStyling: false
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error del servidor',
                            text: 'No se pudo procesar la solicitud. Inténtelo nuevamente más tarde.',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            }
        });
    });

    // Quitar producto de un slot
    $(document).on('click', '.quitar-producto', function() {
        let slotNumero = $(this).data('slot');
        
        Swal.fire({
            title: '¿Está seguro?',
            text: "¿Desea quitar el producto del slot " + slotNumero + "?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, quitar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-danger me-3',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                let formData = new FormData();
                formData.append('slot_numero', slotNumero);

                $.ajax({
                    url: './ajax_quitar_producto_slot.php',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Producto retirado!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                confirmButtonText: 'Aceptar',
                                customClass: {
                                    confirmButton: 'btn btn-warning'
                                },
                                buttonsStyling: false
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error en la petición AJAX:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error del servidor',
                            text: 'No se pudo procesar la solicitud. Inténtelo nuevamente más tarde.',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            }
        });
    });
});
</script>
<?php
require("../shared_new/footer.php");
?>