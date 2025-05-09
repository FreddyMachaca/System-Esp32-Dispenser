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
            <h4 class="mb-2">Estudiante</h4>
            <ol class="breadcrumb">

                <li class="breadcrumb-item text-t">
                    <a href="./lista.php" class="router-link-active" style="color: black;">Lista de Estudiante</a>
                </li>
                <li class="breadcrumb-item text-t">
                    <b>Registrar Estudiante</b>
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form method="POST" id="formulario-usuario-registro">
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <!-- DATOS GENERALES -->
                                <div class="form-group col-lg-12 mb-2"
                                    style="background-color: #f2f2f2; padding: 10px; border-radius: 5px; display: flex; align-items: center;">
                                    <div style="flex-grow: 1;">
                                        <h5 class="card-title mb-1 font-medium-2" style="margin-bottom: 0;">
                                            <i class="fas fa-user text-success"></i> Datos Personales
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-md-2">

                                </div>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-lg-4 mb-3" title="Asignar tarjeta">
                                            <label class="form-label fw-bold fs-6 text-primary" for="codigo_rfid">
                                                <i class="fa fa-receipt me-2"></i>Codigo de Tarjeta:
                                            </label>
                                            <div class="input-group">
                                                <input type="text" id="codigo_rfid" class="form-control border-primary" value=""
                                                disabled />
                                                <button class="btn btn-primary" type="button" id="buscar-tarjeta">
                                                    <i class="ti ti-line-scan me-2"></i>Buscar
                                                </button>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-4 mb-3">
                                            <span class="text-danger">*</span>
                                            <label for="" class="text-show">Nombre:</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre">
                                        </div>
                                        <div class="form-group col-lg-4 mb-3">
                                            <span class="text-danger">*</span>
                                            <label for="" class="text-show">Apellido Paterno:</label>
                                            <input type="text" class="form-control" id="paterno" name="paterno">
                                        </div>
                                        <div class="form-group col-lg-4 mb-3">
                                            <span class="text-danger">*</span>
                                            <label for="" class="text-show">Apellido Materno:</label>
                                            <input type="text" class="form-control materno" id="materno" name="materno">
                                        </div>
                                        <div class="form-group col-lg-4 mb-3">
                                            <span class="text-danger">*</span>
                                            <label for="" class="text-show">CI:</label>
                                            <input type="text" class="form-control ci" id="ci" name="ci">
                                        </div>

                                        <div class="form-group col-lg-4 mb-3">
                                            <span class="text-danger">*</span>
                                            <label for="" class="text-show">Correo Electronico:</label>
                                            <input type="text" class="form-control" id="correo" name="correo">
                                        </div>
                                        <div class="form-group col-lg-4 mb-3">
                                            <span class="text-danger">*</span>
                                            <label for="" class="text-show">Celular/Telefono:</label>
                                            <input type="text" class="form-control" id="celular" name="celular">
                                        </div>
                                        <div class="form-group col-lg-4 mb-3">
                                            <span class="text-danger">*</span>
                                            <label for="" class="text-show">Fecha de Nacimiento:</label>
                                            <input type="date" class="form-control" id="fecha_nac" name="fecha_nac">
                                        </div>
                                        <div class="form-group col-lg-4 mb-3">
                                            <span class="text-danger">*</span>
                                            <label for="" class="text-show">Género:</label><br>
                                            <?php
                                            // Obtener géneros
                                            $query = "SELECT g.cod_genero, g.descripcion FROM genero g ORDER BY g.cod_genero ASC";
                                            $stmt = $dbh->prepare($query);
                                            $stmt->execute();
                                            $generos = $stmt->fetchAll();
                                            foreach ($generos as $genero) {
                                        ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="genero"
                                                    id="genero_<?=$genero['cod_genero']?>"
                                                    value="<?=$genero['cod_genero']?>">
                                                <label class="form-check-label"
                                                    for="genero_<?=$genero['cod_genero']?>"><?=$genero['descripcion']?></label>
                                            </div>
                                            <?php
                                            }
                                        ?>
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
// Script JS
require("../shared_new/script_js.php");
?>
<script>
$(document).ready(function() {
    //* Cerrar formulario
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

    /************************
     * TODO: Guarda Registro
     ************************/
    var validatorPedido = $("#formulario-usuario-registro").validate({
        rules: {
            nombre: {
                required: true,
                minlength: 2
            },
            paterno: {
                required: true,
                minlength: 2
            },
            materno: {
                required: true,
                minlength: 2
            },
            ci: {
                required: true,
                minlength: 5
            },
            correo: {
                required: true,
                email: true
            },
            celular: {
                required: true,
                minlength: 8
            },
            fecha_nac: {
                required: true
            },
            genero: {
                required: true
            },
        },
        messages: {
            nombre: {
                required: "Debe ingresar su nombre.",
                minlength: "El nombre debe tener al menos 2 caracteres."
            },
            paterno: {
                required: "Debe ingresar su apellido paterno.",
                minlength: "El apellido paterno debe tener al menos 2 caracteres."
            },
            materno: {
                required: "Debe ingresar su apellido materno.",
                minlength: "El apellido materno debe tener al menos 2 caracteres."
            },
            ci: {
                required: "Debe ingresar su número de CI.",
                minlength: "El CI debe tener al menos 5 caracteres."
            }, 
            correo: {
                required: "Debe ingresar un correo electrónico.",
                email: "Debe ingresar un correo válido."
            },
            celular: {
                required: "Debe ingresar un número de celular o teléfono.",
                minlength: "El número debe tener al menos 8 dígitos."
            },
            fecha_nac: {
                required: "Debe ingresar su fecha de nacimiento."
            },
            genero: {
                required: "Debe seleccionar su género."
            }
        },

        errorElement: 'span',
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
            let formData = new FormData();

            // Agregar valores al FormData usando los ID correctos
            formData.append('nombre', $('#nombre').val());
            formData.append('paterno', $('#paterno').val());
            formData.append('materno', $('#materno').val());
            formData.append('codigo_rfid', $('#codigo_rfid').val()); 
            formData.append('ci', $('#ci').val()); 
            formData.append('correo', $('#correo').val());
            formData.append('celular', $('#celular').val());
            formData.append('fecha_nac', $('#fecha_nac').val());
            formData.append('genero', $('input[name="genero"]:checked').val());

            console.log(formData);

            $.ajax({
                url: './ajax_registrar_estudiante.php',
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
                            location.href = './lista.php';
                        });
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
        }

    });

    $(document).on('click', '#buscar-tarjeta', function() {
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
                url: "../Esp32/obtenerSerialRegistrar.php",
                method: "POST",
                dataType: "json",
                success: function(response) {
                    if (cancelado) return;
                    if (response.status) {
                        clearTimeout(timeout);
                        cancelado = true;
                        Swal.close();
                        var serial = response.serial;
                        $('#codigo_rfid').val(serial); 

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

});
</script>
<?php
// Footer HTML
require("../shared_new/footer.php");
?>