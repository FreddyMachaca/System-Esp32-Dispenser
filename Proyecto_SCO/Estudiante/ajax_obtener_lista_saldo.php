<?php 
require("../conexion.php");
header('Content-Type: application/json');
$dbh = new Conexion();
 
$query = "SELECT e.cod_estudiante, CONCAT(e.nombre, ' ', e.paterno, ' ', e.materno) nombre, e.ci, e.celular, s.saldo_actual, s.deuda, s.cod_estado, esa.nombre estado, esa.color
            FROM estudiantes e  
            INNER JOIN estados es on es.cod_estado = e.cod_estado
            INNER JOIN tarjetas_rfid tr on tr.cod_estudiante = e.cod_estudiante 
            LEFT JOIN saldos s on s.cod_tarjeta = tr.cod_tarjeta
            LEFT JOIN estado_saldo esa on esa.cod_estado = s.cod_estado
            WHERE e.cod_estado = 1 and tr.cod_estado = 1;";
 
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
        $sub_array[] = $fila["saldo_actual"]; 
        $sub_array[] = $fila["deuda"]; 
        $sub_array[] = '<span class="badge bg-label-'.$fila['color'].' me-1">' . $fila["estado"] . '</span>';  
        
        $sub_array[] = '<button class="btn btn-success btn-sm btn_borde me-1 recargar-saldo" title="Recargar Saldo" id="'.$fila['cod_estudiante'].'">
                            <i class="ti ti-businessplan"></i>
                        </button>';  
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