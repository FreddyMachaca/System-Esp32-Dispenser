<?php  
require("../shared_new/header.php");
 
?>

<!-- ****************************** -->
<!-- CONTENIDO -->
<div class="flex-grow-1 container-p-y container-fluid pt-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mt-2">
        <div class="d-flex flex-column justify-content-center gap-2 gap-sm-0">
            <h4 class="mb-2">Saldo de Estudiantes</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-t"><b>Lista de Estudiantes</b>
                </li>
            </ol>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-2 mb-2">
            <button class="btn btn-primary btn-md buscar">
                <i class="feather ti ti-device-tablet-search"></i> Buscar por Tarjeta
            </button>
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
                                    <th>Nombre completo</th>
                                    <th>CI</th>
                                    <th>Celular</th>
                                    <th>Saldo actual</th>
                                    <th>saldo pendiente</th>
                                    <th>estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalRecargarSaldo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" id="formulario" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header bg-primary pb-3">
                        <h5 class="modal-title text-white" id="exampleModalLabel1"><b>Nuevo Cliente</b></h5>
                        <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" id="cod_estudiante">
                            <div class="col mb-4">
                                <label for="nombre" class="form-label fs-7 fw-bold">
                                    Nombre Completo <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="nombre" name="nombre" class="form-control"
                                    placeholder="Ingrese el nombre del cliente" autocomplete="off" disabled>
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col mb-4">
                                <label for="saldo" class="form-label fs-7 fw-bold">
                                    Saldo <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="saldo" name="saldo" class="form-control" disabled>
                            </div>
                            <div class="col mb-4">
                                <label for="deuda" class="form-label fs-7 fw-bold">Deuda</label>
                                <input type="text" id="deuda" name="deuda" class="form-control" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col mb-4">
                                <label for="recargar" class="form-label fs-7 fw-bold">
                                    Recargar Saldo <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="recargar" name="recargar" class="form-control"
                                    placeholder="Ingrese la cantidad a recargar" >
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary registrar-saldo">Guardar</button>
                    </div>
                </div>
            </form>
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
// Script JS
require("../shared_new/script_js.php");
?>
<script>
$(document).ready(function() {
    var tabla = $('#tabla').DataTable({
        "ajax": {
            url: "./ajax_obtener_lista_saldo.php",
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


    $(document).on('click', '.buscar', function() {
        let tiempoEspera = 30000; // 30 segundos
        let tiempoInicio = Date.now();
        let intervalo, timeout;
        let cancelado = false;        Swal.fire({
            title: '<span class="text-primary"><i class="feather ti ti-device-watch"></i> Escaneando RFID</span>',
            html: `
                <div class="card-scan-rfid">
                    <div class="rfid-animation">
                        <div class="waves"></div>
                        <div class="waves"></div>
                        <div class="waves"></div>
                        <div class="card-icon">
                            <i class="fas fa-id-card fa-2x"></i>
                        </div>
                    </div>
                    <div class="scan-text">
                        <p>Acerque la tarjeta al lector</p>
                        <div class="countdown-bar">
                            <div class="progress-bar"></div>
                        </div>
                        <span class="countdown-text">Tiempo restante: <b>30</b> segundos</span>
                    </div>
                </div>
                <style>
                    .card-scan-rfid {
                        padding: 20px;
                        border-radius: 10px;
                        position: relative;
                    }
                    .rfid-animation {
                        position: relative;
                        display: flex;
                        justify-content: center;
                        margin-bottom: 15px;
                        height: 150px;
                    }
                    .card-icon {
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        z-index: 2;
                        background: white;
                        border-radius: 50%;
                        width: 60px;
                        height: 60px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        box-shadow: 0 0 15px rgba(0,0,0,0.1);
                        color: #5a8dee;
                    }
                    .waves {
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        width: 60px;
                        height: 60px;
                        border-radius: 50%;
                        background-color: rgba(90, 141, 238, 0.3);
                        animation: pulse 2s infinite;
                        z-index: 1;
                    }
                    .waves:nth-child(2) {
                        animation-delay: 0.5s;
                    }
                    .waves:nth-child(3) {
                        animation-delay: 1s;
                    }
                    @keyframes pulse {
                        0% {
                            transform: translate(-50%, -50%) scale(1);
                            opacity: 1;
                        }
                        100% {
                            transform: translate(-50%, -50%) scale(3);
                            opacity: 0;
                        }
                    }
                    .scan-text {
                        text-align: center;
                        margin-top: 10px;
                    }
                    .scan-text p {
                        font-size: 1rem;
                        margin-bottom: 10px;
                        color: #5a5a5a;
                    }
                    .countdown-bar {
                        height: 6px;
                        background-color: #e9ecef;
                        border-radius: 10px;
                        margin: 10px 0;
                        overflow: hidden;
                    }
                    .progress-bar {
                        height: 100%;
                        background-color: #5a8dee;
                        border-radius: 10px;
                        width: 100%;
                        animation: countdown 30s linear forwards;
                    }
                    @keyframes countdown {
                        0% { width: 100%; }
                        100% { width: 0%; }
                    }
                    .countdown-text {
                        font-size: 0.9rem;
                        color: #5a5a5a;
                    }
                </style>
            `,
            showConfirmButton: false,
            showCancelButton: false,
            allowEscapeKey: false,
            width: '400px',
            padding: '20px',
            background: '#ffffff',
            backdrop: 'rgba(0,0,123,0.2)',
            customClass: {
                container: 'swal2-container-zindex',
                popup: 'animated fadeInUp'
            },
            didOpen: () => {
                const b = Swal.getHtmlContainer().querySelector('b');
                let tiempoInicio = Date.now();
                let tiempoEspera = 30000;
                intervalo = setInterval(() => {
                    const restante = Math.ceil((tiempoEspera - (Date.now() -
                        tiempoInicio)) / 1000);
                    b.textContent = restante;
                }, 500);
                esperarTarjeta();
            },
            willClose: () => {
                clearInterval(intervalo);
                clearTimeout(timeout);
                cancelado = true;
            }
        });


        //* Si se agota el tiempo detiene todo
        timeout = setTimeout(() => {
            cancelado = true;
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Tiempo agotado',
                text: 'No se recibió el código RFID a tiempo.',
                showConfirmButton: false,
                timer: 2000
            });
        }, tiempoEspera);

        function esperarTarjeta() {
            if (cancelado) return;
            $.ajax({
                url: "../Esp32/obtenerSerial.php",
                method: "POST",
                dataType: "json",
                success: function(response) {
                    if (cancelado) return;
                    if (response.status) {
                        clearTimeout(timeout);
                        cancelado = true;
                        Swal.close();
                        var cod_estudiante = response.cod_estudiante;
                        estudianteModal(cod_estudiante);

                    } else {
                        setTimeout(esperarTarjeta, 1000);
                    }
                },
                error: function() {
                    if (!cancelado) {
                        setTimeout(esperarTarjeta, 1000);
                    }
                }
            });
        }
    });

    $(document).on('click', '.recargar-saldo', function() { 
        let cod_estudiante = $(this).attr("id");
        estudianteModal(cod_estudiante);
    });

    $(document).on('click', '.registrar-saldo', function() { 
        let formData = new FormData();
        formData.append('cod_estudiante', $('#cod_estudiante').val());
        formData.append('saldo_recargar', $('#recargar').val());
        $.ajax({
                url: './ajax_registrar_saldo_estudiante.php',
                method: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Registro exitoso!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => { 
                            $('#formulario')[0].reset();
                            $('#modalRecargarSaldo').modal('toggle');
                        });
                        $('#tabla').DataTable().ajax.reload(null, false); 
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            confirmButtonText: 'Aceptar',
                            customClass: {
                                confirmButton: 'btn btn-warning waves-effect waves-light'
                            },
                            buttonsStyling: false
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la petición AJAX:', error);
                }
            });
        
    });

    function estudianteModal(cod_estudiante) {
        let formData = new FormData();
        formData.append('cod_estudiante', cod_estudiante);

        $.ajax({
            url: './ajax_obtener_saldo_estudiante.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status) {
                    const datos = response.data;
                    $('#cod_estudiante').val(datos.cod_estudiante);
                    $('#nombre').val(datos.nombre);
                    $('#saldo').val(datos.saldo_actual);
                    $('#deuda').val(datos.deuda);

                    $('#modalRecargarSaldo').modal('show');
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
            }
        });
    }



});
</script>
<?php
// Footer HTML
require("../shared_new/footer.php");
?>