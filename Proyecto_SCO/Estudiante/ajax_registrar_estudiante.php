<?php
    require("../conexion.php");
    $dbh = new Conexion();
    
   
    try{ 
        $nombre     =   $_POST['nombre'];
        $paterno    =   $_POST['paterno'];
        $materno    =   $_POST['materno'];
        $ci         =   $_POST['ci'];
        $codigo_rfid=   $_POST['codigo_rfid'];
        $correo     =   $_POST['correo'];
        $celular    =   $_POST['celular'];
        $fecha_nac  =   $_POST['fecha_nac'];
        $genero     =   $_POST['genero']; 

        $fecha = date('Y-m-d');
        $fecha_actualizacion = date('Y-m-d H:i:s');
        //! Registro en la tabla estudiantes   
        $query = "INSERT INTO estudiantes(nombre, paterno, materno, ci, correo, celular, fecha_nacimiento, cod_genero, cod_estado, created_at) 
                    VALUES(:nombre, :paterno, :materno, :ci, :correo, :celular, :fecha_nac, :genero, :cod_estado, :created_at)";
        $stmt_registro = $dbh->prepare($query);
        $stmt_registro->execute([
            ':nombre'       =>  $nombre,
            ':paterno'      =>  $paterno,
            ':materno'      =>  $materno,
            ':ci'           =>  $ci,
            ':correo'       =>  $correo,
            ':celular'      =>  $celular,
            ':fecha_nac'    =>  $fecha_nac,
            ':genero'       =>  $genero,
            ':cod_estado'   =>  1,
            ':created_at'   =>  $fecha,
        ]); 
        //! verificar si hay tarjeta
        if(!empty($codigo_rfid)) {
            
            $cod_estudiante = $dbh->lastInsertId();
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
        }
        
        
        $response['status']  = true;
        $response['message'] = 'Estudiante registrado correctamente.';
    
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
    // RESULTADO JSON
    echo json_encode($response);
?>