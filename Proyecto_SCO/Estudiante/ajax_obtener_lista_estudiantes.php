<?php 
require("../conexion.php");
header('Content-Type: application/json');
$dbh = new Conexion();
 
$query = "SELECT e.cod_estudiante, CONCAT(e.nombre, ' ', e.paterno, ' ', e.materno) nombre, e.ci, e.celular, e.correo, e.fecha_nacimiento, g.descripcion, tr.codigo_rfid, es.nombre estado, es.cod_estado, es.color
            FROM estudiantes e 
            INNER JOIN genero g on g.cod_genero = e.cod_genero
            INNER JOIN estados es on es.cod_estado = e.cod_estado
            LEFT JOIN tarjetas_rfid tr on tr.cod_estudiante = e.cod_estudiante and tr.cod_estado = 1
            WHERE e.cod_estado = 1 
            ORDER BY e.cod_estudiante ASC;";


 
try {
    $stmt = $dbh->prepare($query);
    $resultado = $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $datos = array();
    foreach ($resultado as $fila) {
        $sub_array = array(); 
        $sub_array[] = $fila["cod_estudiante"]; 
        $sub_array[] = $fila["nombre"]; 
        $sub_array[] = $fila["ci"];
        $sub_array[] = $fila["celular"]; 
        $sub_array[] = $fila["correo"]; 
        $sub_array[] = $fila["fecha_nacimiento"]; 
        $sub_array[] = $fila["descripcion"]; 

        $tarjeta_estado = '<span class="badge bg-label-warning me-1">Sin Tarjeta</span>';

        $sub_array[] = $fila["codigo_rfid"] == ''? $tarjeta_estado: $fila["codigo_rfid"]; 
        $sub_array[] = '<span class="badge bg-label-'.$fila['color'].' me-1">' . $fila["estado"] . '</span>';  

        $botonEditar = '<a href="./editar_usuario.php?cod_estudiante=' . $fila['cod_estudiante'] . '" class="btn btn-info btn-sm editar btn_borde me-1" title="Editar" >
                            <i class="fa fa-edit"></i>
                        </a>';

        $botonEliminar = '<button class="btn btn-danger btn-sm btn_borde anular me-1" title="Deshabilitar usuario" id="' . $fila['cod_estudiante'] . '">
                            <i class="fa fa-times"></i> 
                        </button>';

        $botonHabilitar = '<button class="btn btn-success btn-sm btn_borde habilitar" title="Habilitar usuario" id="' . $fila['cod_estudiante'] . '">
                            <i class="fa fa-check"></i> 
                        </button>';
        $botonTarjeta = '<button class="btn btn-success btn-sm  btn_borde me-1 asignar-tarjeta" title="Asignar Tarjeta" id="'.$fila['cod_estudiante'].'">
                            <i class="ti ti-credit-card"></i>
                        </button>';
        $botonTarjetaEliminar = '<button class="btn btn-warning btn-sm btn_borde me-1 anular-tarjeta" data-cod-rfid="'.$fila['codigo_rfid'].'" id="'.$fila['cod_estudiante'].'" title="Anular Tarjeta" >
                                    <i class="ti ti-credit-card-off"></i>
                                </button>';
        $sinTarjeta = $fila['codigo_rfid'] == '' ? $botonTarjeta.$botonEditar : $botonTarjetaEliminar.$botonEditar;
        $sub_array[] = $fila['cod_estado'] == 1 ? $sinTarjeta.$botonEliminar : $sinTarjeta . $botonHabilitar;
        $datos[] = $sub_array;
    }

    $salida = array(
        "data" => $datos
    );

    echo json_encode($salida);

} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(array('error' => $e->getMessage()));
}