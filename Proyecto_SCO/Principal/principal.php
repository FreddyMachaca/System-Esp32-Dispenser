<?php 
    /* require("../conexion.php"); 
    $dbh = new Conexion();

    $cod_usuario = $_COOKIE['user_id'];
    $query = "SELECT * FROM usuarios WHERE cod_usuario = :cod_usuario";
    $stmt = $dbh->prepare($query);
   
    $stmt->execute([':cod_usuario' => $cod_usuario]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC); */
?>
<!DOCTYPE html>
<html lang="es" class="dark-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="../assets_vuexy/" data-template="vertical-menu-template"
    data-style="dark">


<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>IA</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets_vuexy/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="../assets_vuexy/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="../assets_vuexy/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="../assets_vuexy/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets_vuexy/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets_vuexy/vendor/css/rtl/theme-default.css"
        class="template-customizer-theme-css" />

    <link rel="stylesheet" href="../assets_vuexy/css/demo.css" />



    <!-- Helpers -->
    <script src="../assets_vuexy/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="../assets_vuexy/vendor/js/template-customizer.js"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets_vuexy/js/config.js"></script> 
</head>

<body>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <sidebar id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="../Estudiante/lista.php" class="app-brand-link" target="contenedorPrincipal">
                        <span class="app-brand-logo demo">
                            <svg width="32" height="22" viewBox="0 0 32 22" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                                    fill="#7367F0" />
                                <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
                                    fill="#161616" />
                                <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
                                    fill="#161616" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                                    fill="#7367F0" />
                            </svg>
                        </span>
                        <span class="app-brand-text demo menu-text fw-bold">Vuexy</span>
                    </a>

                    <a href="../Estudiante/lista.php" class="layout-menu-toggle menu-link text-large ms-auto"
                        target="contenedorPrincipal">
                        <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
                        <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">

                    <!-- Estudiante -->
                    <li class="menu-item">
                        <a href="../Estudiante/lista.php" class="menu-link  menu-toggle" target="contenedorPrincipal">
                            <i class="menu-icon tf-icons ti ti-school"></i>
                            <div data-i18n="estudiante">Estudiante</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="../Estudiante/lista.php" class="menu-link" target="contenedorPrincipal">
                                    <i class="menu-icon tf-icons ti ti-smart-home"></i>
                                    <div data-i18n="lista_estudiante">Lista Estudiantes</div>
                                </a>
                                <a href="../Estudiante/lista_saldo.php" class="menu-link" target="contenedorPrincipal">
                                    <i class="menu-icon tf-icons ti ti-smart-home"></i>
                                    <div data-i18n="lista_estudiante">Recargar Saldo</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Maquina Expendedora -->
                    <li class="menu-item">
                        <a href="../Estudiante/lista.php" class="menu-link  menu-toggle" target="contenedorPrincipal">
                            <i class="menu-icon tf-icons ti ti-wash-machine"></i>
                            <div data-i18n="estudiante">Maquina Expendedora</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="../Estudiante/lista.php" class="menu-link" target="contenedorPrincipal">
                                    <i class="menu-icon tf-icons ti ti-smart-home"></i>
                                    <div data-i18n="lista_estudiante">Lista de Maquinas</div>
                                </a>
                                <a href="../Estudiante/lista.php" class="menu-link" target="contenedorPrincipal">
                                    <i class="menu-icon tf-icons ti ti-smart-home"></i>
                                    <div data-i18n="lista_estudiante">Productos en Maquinas</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Productos -->
                    <li class="menu-item">
                        <a href="../Estudiante/lista.php" class="menu-link" target="contenedorPrincipal">
                            <i class="menu-icon tf-icons ti ti-shopping-cart"></i>
                            <div data-i18n="estudiante">Productos</div>
                        </a> 
                    </li> 
                     
                    <!-- Dashboard -->
                    <li class="menu-item">
                        <a href="../Dashboard/dashboard.php" class="menu-link" target="contenedorPrincipal">
                            <i class="menu-icon tf-icons ti ti-chart-infographic"></i>
                            <div data-i18n="dashboards">Dashboards</div>
                        </a>
                    </li>

                    <!-- Apps & Pages -->
                    <li class="menu-header small">
                        <span class="menu-header-text" data-i18n="Apps & Pages">Apps &amp; Pages</span>
                    </li>
                </ul>
            </sidebar>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <navbar
                    class="layout-navbar navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme container-fluid"
                    id="layout-navbar">
                    <!-- Botón menú para pantallas pequeñas -->
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="ti ti-menu-2 ti-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center " id="navbar-collapse">
                        <ul class="navbar-nav flex-row align-items-center ms-auto">

                            <!-- Sucursales -->
                            <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0"
                                title="Cambiar de Sucursal">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="ti ti-layout-grid-add ti-md"></i>
                                </a>
                            </li>


                            <!-- Usuario -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="../Imagenes/pingui.png"
                                            style="object-fit: cover; height: 100% !important; width: 100% !important;"
                                            alt="Perfil" class="h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="../Imagenes/pingui.png"
                                                            style="object-fit: cover; height: 100% !important; width: 100% !important;"
                                                            alt="Perfil" class="h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-medium d-block">Hola, <?= htmlspecialchars($user['nombre']) ?></span>
                                                    <small class="text-muted">Administrador</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="perfil.html">
                                            <i class="ti ti-user-check me-2 ti-sm"></i>
                                            <span class="align-middle">Mi perfil</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="../logout.php">
                                            <i class="ti ti-logout me-2 ti-sm"></i>
                                            <span class="align-middle">Cerrar Sesión</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ Usuario -->
                        </ul>
                    </div>

                    <!-- Search Small Screens -->
                    <div class="navbar-search-wrapper search-input-wrapper d-none">
                        <input type="text" class="form-control search-input container-xxl border-0"
                            placeholder="Buscar..." aria-label="Buscar..." />
                        <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
                    </div>
                </navbar>
                <div class="content-wrapper">
                    <!-- Content -->
                    <iframe src="../Estudiante/lista.php" name="contenedorPrincipal" id="mainFrame"
                        style="width: 100%; height:100%"></iframe>
                    <!-- / Content -->
                </div>
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->


    <script src="../assets_vuexy/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets_vuexy/vendor/js/bootstrap.js"></script>
    <script src="../assets_vuexy/vendor/js/menu.js"></script>
    <script src="../assets_vuexy/js/main.js"></script>
 

    <script>
    $(document).ready(function() {
        // Manejar cierre de sesión
        $('a[href="../logout.php"]').on('click', function(e) {
            e.preventDefault();
            window.location.href = '../logout.php';
        });
    });
    </script>
</body>

</html>