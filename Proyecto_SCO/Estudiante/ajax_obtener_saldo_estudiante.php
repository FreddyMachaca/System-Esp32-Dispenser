<?php
require("../conexion.php");
$dbh = new Conexion();

$response = ['status' => false];

try {
    $cod_estudiante = $_POST['cod_estudiante'];
    //obtener saldo de estudiante
    $query = "SELECT e.cod_estudiante, CONCAT(e.nombre, ' ', e.paterno, ' ', e.materno) AS nombre,
                     s.saldo_actual, s.deuda
              FROM estudiantes e
              INNER JOIN tarjetas_rfid tr ON tr.cod_estudiante = e.cod_estudiante
              INNER JOIN saldos s ON s.cod_tarjeta = tr.cod_tarjeta
              WHERE e.cod_estado = 1 AND tr.cod_estado = 1 AND e.cod_estudiante = :cod_estudiante";
    
    $stmt = $dbh->prepare($query);
    $stmt->execute([
        ':cod_estudiante' => $cod_estudiante
    ]);

    $datos = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($datos) {
        $response['status'] = true;
        $response['message'] = 'Datos obtenidos correctamente.';
        $response['data'] = $datos;
    } else {
        $response['message'] = 'No se encontró el estudiante.';
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
