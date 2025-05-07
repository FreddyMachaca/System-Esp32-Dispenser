<?php
require("../conexion.php");
header('Content-Type: application/json');
$dbh = new Conexion();

$response = ['status' => false, 'message' => '', 'data' => null];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recibir el código RFID y el número de slot
        $serial = isset($_POST['serial']) ? trim($_POST['serial']) : '';
        $slot_numero = isset($_POST['num']) ? intval($_POST['num']) : 0;
        $accion = isset($_POST['accion']) ? trim($_POST['accion']) : 'verificar'; 
        
        if (empty($serial)) {
            $response['message'] = "Error: No se recibió código RFID";
            echo json_encode($response);
            exit;
        }
        
        if ($slot_numero <= 0 || $slot_numero > 20) {
            $response['message'] = "Error: Número de slot inválido";
            echo json_encode($response);
            exit;
        }
        
        // 1. Verificar si existe el estudiante con la tarjeta RFID
        $query_estudiante = "SELECT e.cod_estudiante, CONCAT(e.nombre, ' ', e.paterno) AS nombre, 
                                  tr.cod_tarjeta, s.saldo_actual, s.deuda
                            FROM estudiantes e
                            INNER JOIN tarjetas_rfid tr ON tr.cod_estudiante = e.cod_estudiante
                            INNER JOIN saldos s ON s.cod_tarjeta = tr.cod_tarjeta
                            WHERE e.cod_estado = 1 AND tr.cod_estado = 1 
                            AND tr.codigo_rfid = :codigo_rfid";
        
        $stmt_estudiante = $dbh->prepare($query_estudiante);
        $stmt_estudiante->execute([':codigo_rfid' => $serial]);
        $estudiante = $stmt_estudiante->fetch(PDO::FETCH_ASSOC);
        
        if (!$estudiante) {
            $response['message'] = "Error: Tarjeta no registrada o inactiva";
            echo json_encode($response);
            exit;
        }
        
        // 2. Verificar si hay un producto en el slot seleccionado
        $query_producto = "SELECT pm.cod_producto_maquina, p.cod_producto, p.nombre, p.precio, 
                                 pm.cantidad, m.ubicacion
                           FROM productos_maquina pm
                           INNER JOIN productos p ON p.cod_producto = pm.cod_producto
                           INNER JOIN maquinas m ON m.cod_maquina = pm.cod_maquina
                           WHERE pm.slot_numero = :slot_numero
                           AND pm.cod_estado = 1 AND p.cod_estado = 1";
        
        $stmt_producto = $dbh->prepare($query_producto);
        $stmt_producto->execute([':slot_numero' => $slot_numero]);
        $producto = $stmt_producto->fetch(PDO::FETCH_ASSOC);
        
        if (!$producto) {
            $response['message'] = "Error: No hay producto asignado al slot ".$slot_numero;
            echo json_encode($response);
            exit;
        }
        
        // 3. Verificar si hay stock disponible
        if ($producto['cantidad'] <= 0) {
            $response['message'] = "Error: No hay stock disponible del producto";
            echo json_encode($response);
            exit;
        }
        
        // 4. Verificar si el estudiante tiene saldo suficiente
        $precio_producto = floatval($producto['precio']);
        $saldo_actual = floatval($estudiante['saldo_actual']);
        
        if ($saldo_actual < $precio_producto) {
            $response['message'] = "Error: Saldo insuficiente para comprar el producto";
            echo json_encode($response);
            exit;
        }
        
        // Si es solo verificación, retornar información
        if ($accion === 'verificar') {
            $response['status'] = true;
            $response['message'] = "Puede comprar el producto";
            $response['data'] = [
                'estudiante' => $estudiante['nombre'],
                'producto' => $producto['nombre'],
                'precio' => number_format($producto['precio'], 2) . " Bs.",
                'saldo_actual' => number_format($saldo_actual, 2) . " Bs.",
                'saldo_restante' => number_format($saldo_actual - $precio_producto, 2) . " Bs."
            ];
            echo json_encode($response);
            exit;
        }
        
        // 5. Si es compra, procesar la transacción
        if ($accion === 'comprar') {
            // Actualizar el saldo del estudiante
            $nuevo_saldo = $saldo_actual - $precio_producto;
            $fecha_actualizacion = date('Y-m-d H:i:s');
            $cod_estado = $nuevo_saldo > 0 ? 1 : 3; // 1: Activo, 3: Sin saldo
            
            $query_actualizar_saldo = "UPDATE saldos 
                                       SET saldo_actual = :nuevo_saldo, 
                                           updated_at = :updated_at, 
                                           cod_estado = :cod_estado
                                       WHERE cod_tarjeta = :cod_tarjeta";
            
            $stmt_saldo = $dbh->prepare($query_actualizar_saldo);
            $stmt_saldo->execute([
                ':nuevo_saldo' => $nuevo_saldo,
                ':updated_at' => $fecha_actualizacion,
                ':cod_estado' => $cod_estado,
                ':cod_tarjeta' => $estudiante['cod_tarjeta']
            ]);
            
            // Actualizar la cantidad de productos en el slot
            $nueva_cantidad = $producto['cantidad'] - 1;
            $query_actualizar_producto = "UPDATE productos_maquina 
                                         SET cantidad = :nueva_cantidad, 
                                             updated_at = :updated_at
                                         WHERE cod_producto_maquina = :cod_producto_maquina";
            
            $stmt_producto = $dbh->prepare($query_actualizar_producto);
            $stmt_producto->execute([
                ':nueva_cantidad' => $nueva_cantidad,
                ':updated_at' => $fecha_actualizacion,
                ':cod_producto_maquina' => $producto['cod_producto_maquina']
            ]);
            
            // Registrar la transacción en las tablas transacciones y detalle_transacciones
            // 1. Crear la transacción
            $query_transaccion = "INSERT INTO transacciones
                               (cod_tarjeta, fecha, total, cod_estado, created_at)
                               VALUES (:cod_tarjeta, :fecha, :total, :cod_estado, :created_at)";
            
            $stmt_transaccion = $dbh->prepare($query_transaccion);
            $stmt_transaccion->execute([
                ':cod_tarjeta' => $estudiante['cod_tarjeta'],
                ':fecha' => $fecha_actualizacion,
                ':total' => $precio_producto,
                ':cod_estado' => 1, // 1: Completada
                ':created_at' => $fecha_actualizacion
            ]);
            
            $cod_transaccion = $dbh->lastInsertId();
            
            // 2. Crear el detalle de la transacción
            $query_detalle = "INSERT INTO detalle_transacciones
                           (cod_transaccion, cod_producto_maquina, cantidad, precio_unitario, cod_estado, created_at)
                           VALUES (:cod_transaccion, :cod_producto_maquina, :cantidad, :precio_unitario, :cod_estado, :created_at)";
            
            $stmt_detalle = $dbh->prepare($query_detalle);
            $stmt_detalle->execute([
                ':cod_transaccion' => $cod_transaccion,
                ':cod_producto_maquina' => $producto['cod_producto_maquina'],
                ':cantidad' => 1,
                ':precio_unitario' => $precio_producto,
                ':cod_estado' => 1, // 1: Completado
                ':created_at' => $fecha_actualizacion
            ]);
            
            $response['status'] = true;
            $response['message'] = "Compra realizada con éxito";
            $response['data'] = [
                'estudiante' => $estudiante['nombre'],
                'producto' => $producto['nombre'],
                'precio' => number_format($producto['precio'], 2) . " Bs.",
                'saldo_anterior' => number_format($saldo_actual, 2) . " Bs.",
                'saldo_actual' => number_format($nuevo_saldo, 2) . " Bs."
            ];
        }
        
    } catch (Exception $e) {
        $response['message'] = "Error: " . $e->getMessage();
    }
}

echo json_encode($response);
?>