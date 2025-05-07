<?php
require("../conexion.php");
$dbh = new Conexion();
$archivo = "./codigo_rfid.tmp";

$response = ['status' => false]; 

if (file_exists($archivo)) {
    $codigo = trim(file_get_contents($archivo));
    
    if (!empty($codigo)) {
        
        $response['status'] = true;
        $response['serial'] = $codigo; 
        file_put_contents($archivo, "");
    }
}

echo json_encode($response);
