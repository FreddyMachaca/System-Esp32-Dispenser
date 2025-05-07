<?php
require("../conexion.php");
header('Content-Type: application/json');
$dbh = new Conexion();

$response = ['status' => false, 'data' => []];

try {
    $query = "SELECT cod_maquina, ubicacion, descripcion 
              FROM maquinas 
              WHERE cod_estado = 1
              ORDER BY ubicacion ASC";
    
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $maquinas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($maquinas) > 0) {
        $response['status'] = true;
        $response['data'] = $maquinas;
    } else {
        $response['message'] = "No hay máquinas disponibles";
    }
    
} catch (Exception $e) {
    $response['message'] = "Error: " . $e->getMessage();
}

echo json_encode($response);