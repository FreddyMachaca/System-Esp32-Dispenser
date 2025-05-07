<?php
require("../conexion.php");
$dbh = new Conexion();

$response = ['status' => false];

try{ 
    $cod_producto   = $_POST['cod_producto'];
    $nombre         = $_POST['nombre'];
    $precio         = $_POST['precio'];
    $descripcion    = $_POST['descripcion'];

    $fecha = date('Y-m-d');
    $fecha_actualizacion = date('Y-m-d H:i:s');
    
    // Obtener el precio actual del producto
    $query_precio_actual = "SELECT precio FROM productos WHERE cod_producto = :cod_producto";
    $stmt_precio = $dbh->prepare($query_precio_actual);
    $stmt_precio->execute([':cod_producto' => $cod_producto]);
    $producto_actual = $stmt_precio->fetch(PDO::FETCH_ASSOC);
    
    // Actualizar el producto
    $query = "UPDATE productos SET 
              nombre = :nombre, 
              precio = :precio, 
              descripcion = :descripcion, 
              updated_at = :updated_at 
              WHERE cod_producto = :cod_producto";
              
    $stmt_update = $dbh->prepare($query);
    $stmt_update->execute([
        ':nombre'       => $nombre,
        ':precio'       => $precio,
        ':descripcion'  => $descripcion,
        ':updated_at'   => $fecha_actualizacion,
        ':cod_producto' => $cod_producto
    ]); 
    
    // Si el precio ha cambiado, actualizar historial de precios
    if ($producto_actual['precio'] != $precio) {
        // Cerrar el historial de precios anterior
        $query_cerrar = "UPDATE historial_precios 
                         SET fecha_fin = :fecha_fin, 
                             updated_at = :updated_at, 
                             cod_estado = 2 
                         WHERE cod_producto = :cod_producto 
                         AND cod_estado = 1 
                         AND fecha_fin IS NULL";
                         
        $stmt_cerrar = $dbh->prepare($query_cerrar);
        $stmt_cerrar->execute([
            ':fecha_fin'    => $fecha,
            ':updated_at'   => $fecha_actualizacion,
            ':cod_producto' => $cod_producto
        ]);
        
        // Insertar nuevo historial de precios
        $query_historial = "INSERT INTO historial_precios(cod_producto, precio, fecha_inicio, cod_estado, created_at) 
                            VALUES(:cod_producto, :precio, :fecha_inicio, :cod_estado, :created_at)";
                            
        $stmt_historial = $dbh->prepare($query_historial);
        $stmt_historial->execute([
            ':cod_producto'  => $cod_producto,
            ':precio'        => $precio,
            ':fecha_inicio'  => $fecha,
            ':cod_estado'    => 1,
            ':created_at'    => $fecha_actualizacion,
        ]);
    }
    
    $response['status'] = true;
    $response['message'] = "Producto actualizado correctamente";
    
} catch(Exception $e){
    $response['message'] = "Error al actualizar el producto: " . $e->getMessage();
}

echo json_encode($response);