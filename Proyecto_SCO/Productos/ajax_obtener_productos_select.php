<?php
require("../conexion.php");
header('Content-Type: application/json');
$dbh = new Conexion();

$response = ['status' => false, 'data' => []];

try {
    $query = "SELECT cod_producto, nombre, precio 
              FROM productos 
              WHERE cod_estado = 1
              ORDER BY nombre ASC";
    
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($productos) > 0) {
        $response['status'] = true;
        $response['data'] = $productos;
    } else {
        $response['message'] = "No hay productos disponibles";
    }
    
} catch (Exception $e) {
    $response['message'] = "Error: " . $e->getMessage();
}

echo json_encode($response);