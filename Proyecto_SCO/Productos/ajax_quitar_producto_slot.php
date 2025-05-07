<?php
require("../conexion.php");
$dbh = new Conexion();

$response = ['status' => false];

try{ 
    $slot_numero = $_POST['slot_numero'];
    $fecha_actualizacion = date('Y-m-d H:i:s');
    
    // Buscar el registro que corresponde al slot
    $query_verificar = "SELECT cod_producto_maquina 
                        FROM productos_maquina 
                        WHERE slot_numero = :slot_numero 
                        AND cod_estado = 1";
                        
    $stmt_verificar = $dbh->prepare($query_verificar);
    $stmt_verificar->execute([':slot_numero' => $slot_numero]);
    $producto_existente = $stmt_verificar->fetch(PDO::FETCH_ASSOC);
    
    if (!$producto_existente) {
        $response['message'] = "No se encontró ningún producto asignado a este slot.";
        echo json_encode($response);
        exit;
    }
    
    // Desactivar el producto en el slot
    $query = "UPDATE productos_maquina 
              SET cod_estado = 2, 
                  updated_at = :updated_at 
              WHERE cod_producto_maquina = :cod_producto_maquina";
              
    $stmt = $dbh->prepare($query);
    $stmt->execute([
        ':updated_at' => $fecha_actualizacion,
        ':cod_producto_maquina' => $producto_existente['cod_producto_maquina']
    ]);
    
    $response['status'] = true;
    $response['message'] = "Producto retirado del slot correctamente";
    
} catch(Exception $e){
    $response['message'] = "Error al quitar el producto: " . $e->getMessage();
}

echo json_encode($response);