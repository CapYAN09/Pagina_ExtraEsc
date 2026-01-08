<?php
session_start();

// Conexion
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
    $id_actividad = $_POST['id'] ?? '';
    $tipo_act = $_POST['tipo'] ?? '';
    $alumno = $_POST['usuario_aln'];
    $id_ActInicial = $_POST['id_Act'];

    if (!$id_actividad || !$tipo_act || !$alumno) {
        die("Datos incompletos");
    }

    // 🔒 Iniciar transacción
    $pdo->beginTransaction();

    if($tipo_act == "deportivo"){ 
            $sqlEliminar = "DELETE FROM actividades_alumnos_deportivas WHERE id = :id AND usuario = :usuario";
            $stmEliminar = $pdo->prepare($sqlEliminar);
            $stmEliminar->execute([
                'id' => $id_actividad,
                'usuario' => $alumno
            ]);
            // 4️⃣ Aumentar capacidad
            $sqlUpdate = "
                UPDATE actividades_deportivas
                SET capacidad = capacidad + 1
                WHERE id_deportivo = :id
            ";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->execute(['id' => $id_ActInicial]);
        }else{
            if($tipo_act == "cultural"){
                $sqlEliminar = "DELETE FROM actividades_alumnos_culturales WHERE id = :id AND usuario = :usuario";
                $stmEliminar = $pdo->prepare($sqlEliminar);
                $stmEliminar->execute([
                'id' => $id_actividad,
                'usuario' => $alumno
                ]);
                // 4️⃣ Aumentar capacidad
                $sqlUpdate = "
                    UPDATE actividades_culturales
                    SET capacidad = capacidad + 1
                    WHERE id_cultural = :id
                ";
                $stmtUpdate = $pdo->prepare($sqlUpdate);
                $stmtUpdate->execute(['id' => $id_ActInicial]);
            }else{
                if (!$id_actividad || !$tipo_act) {
                    die("Accion No Realizada");
                }
        }
    }

    $pdo->commit();

    $_SESSION['mensaje_eliminar_alumno'] = "Alumno Eliminado correctamente";
    header("Location: ../lista-alumnos-act.php?id=".$id_ActInicial."&tipo=".$tipo_act);
    exit();


}catch(Exception $e){

    // ❌ Revertir si algo falla
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Aquí puedes redirigir con mensaje si quieres
    $_SESSION['mensaje_eliminar_alumno'] = $e->getMessage();
    header("Location: ../lista-alumnos-act.php?id=".$id_ActInicial."&tipo=".$tipo_act);
    exit();
}
?>