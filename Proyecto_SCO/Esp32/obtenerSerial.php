<?php
require("../conexion.php");
$dbh = new Conexion();
$archivo = "./codigo_rfid.tmp";

$response = ['status' => false]; 

if (file_exists($archivo)) {
    $codigo = trim(file_get_contents($archivo));
    
    if (!empty($codigo)) {
        try {
            $query = "SELECT e.cod_estudiante, tr.codigo_rfid
                      FROM estudiantes e  
                      INNER JOIN tarjetas_rfid tr ON tr.cod_estudiante = e.cod_estudiante 
                      WHERE tr.cod_estado = 1 AND tr.codigo_rfid = :cod_codigo_rfid";
            
            $stmt = $dbh->prepare($query);
            $stmt->execute([
                ':cod_codigo_rfid' => $codigo,
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $response['status'] = true;
                $response['cod_estudiante'] = $result['cod_estudiante'];
                $response['serial'] = $codigo;
            } 
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
 
        file_put_contents($archivo, "");
    }
}

echo json_encode($response);
