<?php 
require("../conexion.php");
header('Content-Type: application/json');
$dbh = new Conexion();
 
$query = "SELECT p.cod_producto, p.nombre, p.precio, p.descripcion, p.cod_estado, e.nombre as estado, e.color
          FROM productos p 
          INNER JOIN estados e ON e.cod_estado = p.cod_estado
          WHERE p.cod_estado = 1 
          ORDER BY p.cod_producto ASC";

try {
    $stmt = $dbh->prepare($query);
    $resultado = $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $datos = array();
    foreach ($resultado as $fila) {
        $sub_array = array(); 
        $sub_array[] = $fila["cod_producto"]; 
        $sub_array[] = $fila["nombre"]; 
        $sub_array[] = number_format($fila["precio"], 2, '.', ',') . " Bs.";
        
        $descripcion = $fila["descripcion"];
        if (strlen($descripcion) > 50) {
            $descripcion = substr($descripcion, 0, 50) . '...';
        }
        $sub_array[] = $descripcion;
        
        $sub_array[] = '<span class="badge bg-label-'.$fila['color'].' me-1">' . $fila["estado"] . '</span>';
        
        // Acciones: editar y eliminar
        $acciones = '<div class="d-flex align-items-center">';
        $acciones .= '<a href="./editar_producto.php?id='.$fila['cod_producto'].'" class="btn btn-primary btn-sm btn_borde me-1" title="Editar"><i class="ti ti-edit"></i></a>';
        $acciones .= '<button class="btn btn-danger btn-sm btn_borde me-1 eliminar-producto" title="Eliminar" id="'.$fila['cod_producto'].'"><i class="ti ti-trash"></i></button>';
        $acciones .= '</div>';
        
        $sub_array[] = $acciones;
        
        $datos[] = $sub_array;
    }

    $salida = array(
        "data" => $datos
    );

    echo json_encode($salida);

} catch (PDOException $e) {
    $response = [
        'status' => false,
        'message' => 'Error en la consulta: ' . $e->getMessage()
    ];
    echo json_encode($response);
}
?>