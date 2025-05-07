<?php
require("../conexion.php");
$dbh = new Conexion();

$response = ['status' => false];

try{ 
    $slot_numero = $_POST['slot_numero'];
    $cod_producto = $_POST['producto'];
    $cantidad = $_POST['cantidad'];
    $cod_maquina = isset($_POST['maquina']) && !empty($_POST['maquina']) ? $_POST['maquina'] : null;
    
    $fecha_actualizacion = date('Y-m-d H:i:s');
    
    if ($cod_maquina === null) {
        $query_maquina = "SELECT cod_maquina FROM maquinas WHERE cod_estado = 1 LIMIT 1";
        $stmt_maquina = $dbh->prepare($query_maquina);
        $stmt_maquina->execute();
        $maquina = $stmt_maquina->fetch(PDO::FETCH_ASSOC);
        
        if ($maquina) {
            $cod_maquina = $maquina['cod_maquina'];
        } else {
            // Si no hay máquinas, creamos una temporal para el dispensador
            $query_insert = "INSERT INTO maquinas(ubicacion, descripcion, cod_estado, created_at) 
                             VALUES('Dispensador General', 'Dispensador automático de productos', 1, :created_at)";
            $stmt_insert = $dbh->prepare($query_insert);
            $stmt_insert->execute([':created_at' => $fecha_actualizacion]);
            $cod_maquina = $dbh->lastInsertId();
        }
    }
    
    // Verificar si ya existe una asignación para este slot en esta máquina
    $query_verificar = "SELECT cod_producto_maquina 
                        FROM productos_maquina 
                        WHERE cod_maquina = :cod_maquina 
                        AND slot_numero = :slot_numero 
                        AND cod_estado = 1";
                        
    $stmt_verificar = $dbh->prepare($query_verificar);
    $stmt_verificar->execute([
        ':cod_maquina' => $cod_maquina,
        ':slot_numero' => $slot_numero
    ]);
    
    $producto_existente = $stmt_verificar->fetch(PDO::FETCH_ASSOC);
    
    if ($producto_existente) {
        $query = "UPDATE productos_maquina 
                  SET cod_producto = :cod_producto, 
                      cantidad = :cantidad, 
                      updated_at = :updated_at 
                  WHERE cod_producto_maquina = :cod_producto_maquina";
                  
        $stmt = $dbh->prepare($query);
        $stmt->execute([
            ':cod_producto' => $cod_producto,
            ':cantidad' => $cantidad,
            ':updated_at' => $fecha_actualizacion,
            ':cod_producto_maquina' => $producto_existente['cod_producto_maquina']
        ]);
        
        $response['message'] = "Producto actualizado en el dispensador correctamente";
    } else {
        // Crear una nueva asignación
        $query = "INSERT INTO productos_maquina(cod_producto, cod_maquina, cantidad, slot_numero, cod_estado, created_at) 
                  VALUES(:cod_producto, :cod_maquina, :cantidad, :slot_numero, :cod_estado, :created_at)";
        $stmt = $dbh->prepare($query);
        $stmt->execute([
            ':cod_producto' => $cod_producto,
            ':cod_maquina' => $cod_maquina,
            ':cantidad' => $cantidad,
            ':slot_numero' => $slot_numero,
            ':cod_estado' => 1,
            ':created_at' => $fecha_actualizacion
        ]);
        
        $response['message'] = "Producto asignado al dispensador correctamente";
    }
    
    $response['status'] = true;
    
} catch(Exception $e){
    $response['message'] = "Error al asignar el producto: " . $e->getMessage();
}

echo json_encode($response);