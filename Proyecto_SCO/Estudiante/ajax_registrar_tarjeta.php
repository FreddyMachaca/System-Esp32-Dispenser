<?php
    require("../conexion.php");
    $dbh = new Conexion();
    
   
    try{ 
        $cod_estudiante     =   $_POST['cod_estudiante'];
        $codigo_rfid        =   $_POST['codigo_tarjeta'];
         

        $fecha = date('Y-m-d');
        $fecha_actualizacion = date('Y-m-d H:i:s');
        //Registro en la tabla estudiantes   
       
        $query_tarjeta = "INSERT INTO tarjetas_rfid(cod_estudiante, codigo_rfid, cod_estado, created_at) 
        VALUES(:cod_estudiante, :cod_rfid, :cod_estado, :created_at)";
        $stmt_registro = $dbh->prepare($query_tarjeta);
        $stmt_registro->execute([
            ':cod_estudiante'   =>  $cod_estudiante,
            ':cod_rfid'         =>  $codigo_rfid, 
            ':cod_estado'       =>  1,
            ':created_at'       =>  $fecha,
        ]); 
        $cod_tarjeta = $dbh->lastInsertId();
        $query_tarjeta = "INSERT INTO saldos(cod_tarjeta, saldo_actual, deuda, cod_estado, created_at) 
        VALUES(:cod_tarjeta, :saldo_actual, :deuda, :cod_estado, :created_at)";
        $stmt_registro = $dbh->prepare($query_tarjeta);
        $stmt_registro->execute([
            ':cod_tarjeta'      =>  $cod_tarjeta,
            ':saldo_actual'     =>  0, 
            ':deuda'            =>  0,
            ':cod_estado'       =>  3,
            ':created_at'       =>  $fecha,
        ]);  
        
        
        $response['status']  = true;
        $response['message'] = 'Tarjeta registrado correctamente.';
    
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
    // RESULTADO JSON
    echo json_encode($response);
?>