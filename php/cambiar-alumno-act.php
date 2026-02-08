<?php
session_start(); 

// Conexión 
$host = 'localhost';
$db = 'actextita'; 
$user = 'root'; 
$pass = ''; 
$charset = 'utf8mb4'; 
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
 
$options = [ 
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
    PDO::ATTR_EMULATE_PREPARES => false, 
    ]; 

    try{
        // Verificar sesión
        if (!isset($_SESSION["usuario"])) { 
            header("Location: ../index.html"); 
            exit(); 
        }

        $pdo = new PDO($dsn, $user, $pass, $options);

        $usuario = $_SESSION["usuario"];
        $id_actividadInicial = $_POST['id_inicial'] ?? '';
        $tipo_act = $_POST['tipo'] ?? '';
        $id_seleccionado = $_POST['id_nueva'];
        $alumno = $_POST['usuario'];

        // Iniciar transacción
        $pdo->beginTransaction();

        if($tipo_act == "deportivo"){ 
            $sqlEliminarInicial = "DELETE FROM actividades_alumnos_deportivas WHERE correo = :usuario AND id_deportivo = :id";
                $stmEliminar = $pdo->prepare($sqlEliminarInicial);
                $stmEliminar->execute([
                'usuario' => $alumno,
                'id' => $id_actividadInicial
                ]);
                // Aumentar capacidad
                $sqlUpdate = "
                    UPDATE actividades_deportivas
                    SET capacidad = capacidad + 1
                    WHERE id_deportivo = :id
                ";
                $stmtUpdate = $pdo->prepare($sqlUpdate);
                $stmtUpdate->execute(['id' => $id_actividadInicial]);
                // Insertar nuevo dato
                $sqlInsertar = "INSERT INTO actividades_alumnos_deportivas(correo,id_deportivo) VALUES (:usuario, :id)";
                $stmt = $pdo->prepare($sqlInsertar);
                $stmt->execute(['usuario' => $alumno, 'id' => $id_seleccionado]);
                //Actualizar la tabla de actividades deportivas
                $sqlUpdateNueva = "
                    UPDATE actividades_deportivas
                    SET capacidad = capacidad - 1
                    WHERE id_deportivo = :id
                ";
                $stmtUpdateNuevo = $pdo->prepare($sqlUpdateNueva);
                $stmtUpdateNuevo->execute(['id' => $id_seleccionado]);
        }else{
            if($tipo_act == "cultural"){
                $sqlEliminarInicial = "DELETE FROM actividades_alumnos_culturales WHERE correo = :usuario AND id_cultural = :id";
                $stmEliminar = $pdo->prepare($sqlEliminarInicial);
                $stmEliminar->execute([
                'usuario' => $alumno,
                'id' => $id_actividadInicial
                ]);
                // Aumentar capacidad
                $sqlUpdate = "
                    UPDATE actividades_culturales
                    SET capacidad = capacidad + 1
                    WHERE id_cultural = :id
                ";
                $stmtUpdate = $pdo->prepare($sqlUpdate);
                $stmtUpdate->execute(['id' => $id_actividadInicial]);
                // Insertar nuevo dato
                $sqlInsertar = "INSERT INTO actividades_alumnos_culturales(correo,id_cultural) VALUES (:usuario, :id)";
                $stmt = $pdo->prepare($sqlInsertar);
                $stmt->execute(['usuario' => $alumno, 'id' => $id_seleccionado]);
                // Actualizar la tabla de actividades deportivas
                $sqlUpdateNueva = "
                    UPDATE actividades_culturales
                    SET capacidad = capacidad - 1
                    WHERE id_cultural = :id
                ";
                $stmtUpdateNuevo = $pdo->prepare($sqlUpdateNueva);
                $stmtUpdateNuevo->execute(['id' => $id_seleccionado]);
            }else{
                if (!$id_actividadSeleccionada || !$tipo_act) {
                    die("Accion No Realizada");
                }
        }
    }

    $pdo->commit();

    $_SESSION['cambiar_alumno'] = "Has cambiado al alumno de taller correctamente";
    header("Location: ../lista-alumnos-act.php?id=".$id_seleccionado."&tipo=".$tipo_act);
    exit();


    }catch(Exception $e){
        // Revertir si algo falla
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        // Aquí puedes redirigir con mensaje si quieres
        $_SESSION['cambiar_alumno'] = $e->getMessage();
        header("Location: ../lista-alumnos-act.php?id=".$id_seleccionado."&tipo=".$tipo_act);
        exit();
    }

?>