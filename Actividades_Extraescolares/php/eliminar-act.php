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
    $id_actividadD = $_POST['id_deportivo'] ?? '';
    $id_actividadC = $_POST['id_cultural'] ?? '';

    // 🔒 Iniciar transacción
    $pdo->beginTransaction();

    if (empty($id_actividadD) && empty($id_actividadC)) {
        $_SESSION['mensaje'] = "ID de actividad no recibido";
        header("Location: ../admin.php");
        exit();
    }else{
        if(empty($id_actividadC)){
            $sqlEliminar = "DELETE FROM actividades_admins_deportivas WHERE id_deportivo = :id";
            $stmEliminar = $pdo->prepare($sqlEliminar);
            $stmEliminar->execute([
                'id' => $id_actividadD
            ]);
            $sqlEliminarDep = "DELETE FROM actividades_deportivas WHERE id_deportivo = :id";
            $stmtEliminarDep = $pdo->prepare($sqlEliminarDep);
            $stmtEliminarDep->execute([
                'id' => $id_actividadD
            ]);

        }else{
            $sqlEliminar = "DELETE FROM actividades_admins_culturales WHERE id_cultural = :id";
            $stmEliminar = $pdo->prepare($sqlEliminar);
            $stmEliminar->execute([
                'id' => $id_actividadC
            ]);
            $sqlEliminarCult = "DELETE FROM actividades_culturales WHERE id_cultural = :id";
            $stmtEliminarCult = $pdo->prepare($sqlEliminarCult);
            $stmtEliminarCult->execute([
                'id' => $id_actividadC
            ]);
        }
    }

    $pdo->commit();

    $_SESSION['mensaje'] = "Actividad Eliminada correctamente";
    header("Location: ../admin.php");
    exit();


}catch(Exception $e){

    // ❌ Revertir si algo falla
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Aquí puedes redirigir con mensaje si quieres
    $_SESSION['mensaje'] = $e->getMessage();
    header("Location: ../admin.php");
    exit();
}
?>