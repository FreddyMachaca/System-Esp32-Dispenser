<?php
require("../conexion.php");
$dbh = new Conexion();

$response = ['status' => false];

try{ 
    $cod_producto = $_POST['cod_producto'];
    $fecha_actualizacion = date('Y-m-d H:i:s');

    // Verificar si el producto está asignado a alguna máquina
    $query_verificar = "SELECT count(*) as total FROM productos_maquina WHERE cod_producto = :cod_producto AND cod_estado = 1";
    $stmt_verificar = $dbh->prepare($query_verificar);
    $stmt_verificar->execute([':cod_producto' => $cod_producto]);
    $resultado = $stmt_verificar->fetch(PDO::FETCH_ASSOC);

    if ($resultado['total'] > 0) {
        $response['message'] = "No se puede eliminar el producto porque está asignado a una o más máquinas.";
        echo json_encode($response);
        exit;
    }

    // Cambiar estado del producto a inactivo (2)
    $query_producto = "UPDATE productos SET cod_estado = 2, updated_at = :updated_at WHERE cod_producto = :cod_producto";
    $stmt_producto = $dbh->prepare($query_producto);
    $stmt_producto->execute([
        ':cod_producto' => $cod_producto,
        ':updated_at'   => $fecha_actualizacion
    ]);

    // Inactivar el historial de precios actual
    $query_historial = "UPDATE historial_precios SET fecha_fin = CURDATE(), updated_at = :updated_at, cod_estado = 2
                         WHERE cod_producto = :cod_producto AND cod_estado = 1 AND fecha_fin IS NULL";
    $stmt_historial = $dbh->prepare($query_historial);
    $stmt_historial->execute([
        ':cod_producto' => $cod_producto,
        ':updated_at'   => $fecha_actualizacion
    ]);

    $response['status'] = true;
    $response['message'] = "Producto eliminado correctamente";
    
} catch(Exception $e){
    $response['message'] = "Error al eliminar el producto: " . $e->getMessage();
}

echo json_encode($response);