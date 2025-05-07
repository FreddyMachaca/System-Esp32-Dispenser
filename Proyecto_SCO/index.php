<!DOCTYPE html>
<html lang="es" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="assets_vuexy/" data-template="vertical-menu-template"
    data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>PRINCIPAL ia</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets_vuexy/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="assets_vuexy/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="assets_vuexy/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="assets_vuexy/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->

    <link rel="stylesheet" href="assets_vuexy/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets_vuexy/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />

    <link rel="stylesheet" href="assets_vuexy/css/demo.css" />

    <!-- JQUERY -->
    <script src="assets_vuexy/vendor/libs/jquery/jquery.js"></script>

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets_vuexy/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="assets_vuexy/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets_vuexy/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="assets_vuexy/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="assets_vuexy/vendor/libs/bootstrap-select/bootstrap-select.css" />
    <link rel="stylesheet" href="assets_vuexy/vendor/libs/sweetalert2/sweetalert2.css" />

    <link rel="stylesheet" href="assets_vuexy/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="assets_vuexy/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="assets_vuexy/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="assets_vuexy/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />

    <!-- Row Group CSS -->
    <link rel="stylesheet" href="assets_vuexy/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" />

    <!-- Helpers -->
    <script src="assets_vuexy/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <!-- <script src="assets_vuexy/vendor/js/template-customizer.js"></script> -->

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets_vuexy/js/config.js"></script>

    <link rel="stylesheet" href="assets_vuexy/jquery/jquery-ui/jquery-ui.min.css">

    <!-- Validación de formulario -->
    <!-- Extensón para la validación de Formularios -->
    <script src="assets_vuexy/jquery/jquery.validate.min.js"></script>
    <script src="assets_vuexy/jquery/additional-methods.min.js"></script>
    <!-- CSS de toastr -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

    <!-- JS de toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <style>
    /* Estilos personalizados para las notificaciones */
    .toast {
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .toast-success {
        background-color: #28a745;
        border-left: 4px solid #218838;
    }

    .toast-error {
        background-color: #dc3545;
        border-left: 4px solid #c82333;
    }

    .toast-info {
        background-color: #17a2b8;
        border-left: 4px solid #138496;
    }

    .toast-warning {
        background-color: #ffc107;
        border-left: 4px solid #e0a800;
    }

    /* Animaciones personalizadas */
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }

        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    /* Aplicar las animaciones */
    .toast-show {
        animation: slideInRight 0.5s forwards;
    }

    .toast-hide {
        animation: slideOutRight 0.5s forwards;
    }
    </style>
</head>

<body>
    <!-- Login -->

    <?php
        require("./Principal/principal.php");
    ?>

    <!-- Login -->



    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets_vuexy/vendor/libs/popper/popper.js"></script>
    <script src="assets_vuexy/vendor/js/bootstrap.js"></script>
    <script src="assets_vuexy/vendor/libs/node-waves/node-waves.js"></script>
    <script src="assets_vuexy/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets_vuexy/vendor/libs/hammer/hammer.js"></script>
    <script src="assets_vuexy/vendor/libs/i18n/i18n.js"></script>
    <script src="assets_vuexy/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="assets_vuexy/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets_vuexy/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="assets_vuexy/vendor/libs/select2/select2.js"></script>
    <script src="assets_vuexy/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
    <script src="assets_vuexy/vendor/libs/sweetalert2/sweetalert2.js"></script>


    <!-- Main JS -->
    <script src="assets_vuexy/js/main.js"></script>
    <!-- Page JS -->
    <script src="assets_vuexy/js/forms-selects.js"></script>
    <script src="assets_vuexy/js/extended-ui-sweetalert2.js"></script>



    <!-- Validacion y envio de datos -->
 
</body>

</html>