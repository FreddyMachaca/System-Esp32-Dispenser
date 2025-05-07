<?php
require("../conexion.php");
$dbh = new Conexion();

$response = ['status' => false];

try{ 
    $nombre         = $_POST['nombre'];
    $precio         = $_POST['precio'];
    $descripcion    = $_POST['descripcion'];

    $fecha = date('Y-m-d');
    $fecha_actualizacion = date('Y-m-d H:i:s');
    
    // Inserción del producto
    $query = "INSERT INTO productos(nombre, precio, descripcion, cod_estado, created_at) 
              VALUES(:nombre, :precio, :descripcion, :cod_estado, :created_at)";
    $stmt_registro = $dbh->prepare($query);
    $stmt_registro->execute([
        ':nombre'       => $nombre,
        ':precio'       => $precio,
        ':descripcion'  => $descripcion,
        ':cod_estado'   => 1,
        ':created_at'   => $fecha_actualizacion,
    ]); 

    $cod_producto = $dbh->lastInsertId();
    
    // Registrar el historial de precios
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
    
    $response['status'] = true;
    $response['message'] = "Producto registrado correctamente";
    
} catch(Exception $e){
    $response['message'] = "Error al registrar el producto: " . $e->getMessage();
}

echo json_encode($response);