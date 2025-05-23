<?php
require("../conexion.php");
$dbh = new Conexion();

try {
    $cod_estudiante = $_POST['cod_estudiante'];
    $saldo_recargar = (float)$_POST['saldo_recargar']; 
    $fecha_actualizacion = date('Y-m-d H:i:s');

    //Obtener saldo, cod_tarjeta, deuda
    $query_verifica = "SELECT s.saldo_actual, s.deuda, tr.cod_tarjeta
                        FROM estudiantes e 
                        INNER JOIN tarjetas_rfid tr ON tr.cod_estudiante = e.cod_estudiante
                        INNER JOIN saldos s ON s.cod_tarjeta = tr.cod_tarjeta
                        WHERE e.cod_estudiante = :cod_estudiante AND tr.cod_estado = 1
                        LIMIT 1";
    $stmt_saldo = $dbh->prepare($query_verifica);
    $stmt_saldo->execute([':cod_estudiante' => $cod_estudiante]);
    $saldo_data = $stmt_saldo->fetch(PDO::FETCH_ASSOC);

    if (!$saldo_data) {
        throw new Exception("No se encontró una tarjeta activa para el estudiante.");
    }

    $cod_tarjeta   = $saldo_data['cod_tarjeta'];
    $saldo_actual  = (float)$saldo_data['saldo_actual']; 
    $deuda         = (float)$saldo_data['deuda']; 
 
    if ($deuda == 0) {
        $nuevo_saldo = $saldo_actual + $saldo_recargar;
        $nueva_deuda = 0;
        $cod_estado = 1;
        registrarSaldo($dbh, $nuevo_saldo, $nueva_deuda, $fecha_actualizacion, $cod_tarjeta, $cod_estado);
    } else {
        $nuevo_saldo = $saldo_actual + $saldo_recargar - $deuda;
        if ($nuevo_saldo == 0) {
            $nueva_deuda = 0;
            $cod_estado = 3;
            registrarSaldo($dbh, $nuevo_saldo, $nueva_deuda, $fecha_actualizacion, $cod_tarjeta, $cod_estado);
        }else if ($nuevo_saldo > 0) {
            $nueva_deuda = 0;
            $cod_estado = 1;
            registrarSaldo($dbh, $nuevo_saldo, $nueva_deuda, $fecha_actualizacion, $cod_tarjeta, $cod_estado);
        } else {
            $nueva_deuda = abs($nuevo_saldo);
            $cod_estado = 2;
            $nuevo_saldo = 0;
            registrarSaldo($dbh, $nuevo_saldo, $nueva_deuda, $fecha_actualizacion, $cod_tarjeta, $cod_estado);
        }
    } 
    $response['status']  = true;
    $response['message'] = 'Saldo actualizado correctamente.';

} catch (Exception $e) {
    $response['status'] = false;
    $response['message'] = $e->getMessage();
}
function registrarSaldo($dbh, $nuevo_saldo, $nueva_deuda, $fecha_actualizacion, $cod_tarjeta, $cod_estado){
    $stmt_update = $dbh->prepare("UPDATE saldos 
        SET saldo_actual = :saldo_actual, deuda = :deuda, updated_at = :updated_at, cod_estado = :cod_estado
        WHERE cod_tarjeta = :cod_tarjeta");
    $stmt_update->execute([
        ':saldo_actual' => $nuevo_saldo,
        ':deuda'        => $nueva_deuda,
        ':cod_estado'   => $cod_estado,
        ':updated_at'   => $fecha_actualizacion,
        ':cod_tarjeta'  => $cod_tarjeta
    ]);
}

echo json_encode($response);
?>
