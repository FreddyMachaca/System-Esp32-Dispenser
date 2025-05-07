<?php 
header('Content-Type: application/json'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    if (isset($_POST['serial'])) {
        $codigo = trim($_POST['serial']);

        file_put_contents("codigo_rfid.tmp", $codigo);
        
        echo "Código recibido: $codigo";
    } else {
        echo "No se recibió ningún código.";
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Metodo de solicitud no valido']);
}
?>