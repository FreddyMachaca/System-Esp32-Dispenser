<?php
    require("../conexion.php");
    $dbh = new Conexion();
    
   
    try{ 
        $cod_estudiante     =   $_POST['cod_estudiante'];
        $codigo_rfid        =   $_POST['codigo_tarjeta'];
         

        $fecha = date('Y-m-d');
        $fecha_actualizacion = date('Y-m-d H:i:s');
        //! Registro en la tabla estudiantes   
       
        $query_tarjeta = "UPDATE tarjetas_rfid SET cod_estado = :cod_estado, updated_at = :updated_at WHERE cod_estudiante = :cod_estudiante and codigo_rfid = :codigo_rfid";
        $stmt_registro = $dbh->prepare($query_tarjeta);
        $stmt_registro->execute([
            ':cod_estado'        =>  2,
            ':updated_at'        =>  $fecha_actualizacion, 
            ':cod_estudiante'    =>  $cod_estudiante,
            ':codigo_rfid'       =>  $codigo_rfid,
        ]); 
        
        
        $response['status']  = true;
        $response['message'] = 'Se anulo la tarjeta correctamente.';
    
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
    // RESULTADO JSON
    echo json_encode($response);
?>