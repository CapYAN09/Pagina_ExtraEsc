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

try {
    // Verificar sesión
    /*if (!isset($_SESSION["usuario"])) { 
        header("Location: ../alumno.html"); 
        exit(); 
    }*/
    
    if (!isset($_SESSION['user_email'])) { 
        header("Location: ../alumno.html"); 
        exit(); 
    }

    $pdo = new PDO($dsn, $user, $pass, $options);

    //$usuario = $_SESSION["usuario"];
    $usuario = $_SESSION['user_email'];
    $id_actividad = $_POST['id_deportivo'] ?? '';

    if (empty($id_actividad)) {
        die("ID de actividad no recibido");
    }

    // Iniciar transacción
    $pdo->beginTransaction();

    // Verificar si YA está inscrito
    $sqlExiste = "
        SELECT 1
        FROM actividades_alumnos_deportivas
        WHERE correo = :usuario
        LIMIT 1
        FOR UPDATE
    ";
    $stmtExiste = $pdo->prepare($sqlExiste);
    $stmtExiste->execute([
        'usuario' => $usuario
    ]);

    if ($stmtExiste->fetch()) {
        $_SESSION['mensaje'] = "Ya estás inscrito en una actividad deportiva";
        header("Location: ../registro-deportivo.php");
        exit();
    }

    // Verificar capacidad disponible
    $sqlCapacidad = "
        SELECT capacidad
        FROM actividades_deportivas
        WHERE id_deportivo = :id
        FOR UPDATE
    ";
    $stmtCap = $pdo->prepare($sqlCapacidad);
    $stmtCap->execute(['id' => $id_actividad]);
    $actividad = $stmtCap->fetch();

    if (!$actividad) {
        $_SESSION['mensaje'] = "Actividad no encontrada";
        header("Location: ../registro-deportivo.php");
        exit();
    }

    if ($actividad['capacidad'] <= 0) {
        $_SESSION['mensaje'] = "No hay cupos disponibles";
        header("Location: ../registro-deportivo.php");
        exit();
    }

    // Insertar inscripción
    $sqlInsert = "
        INSERT INTO actividades_alumnos_deportivas (correo, id_deportivo)
        VALUES (:usuario, :id)
    ";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute([
        'usuario' => $usuario,
        'id' => $id_actividad
    ]);

    // Reducir capacidad
    $sqlUpdate = "
        UPDATE actividades_deportivas
        SET capacidad = capacidad - 1
        WHERE id_deportivo = :id
    ";
    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->execute(['id' => $id_actividad]);

    // Confirmar cambios
    $pdo->commit();

    $_SESSION['mensaje'] = "Te has registrado correctamente, ahora puedes verificarlo en actividades registradas";
    header("Location: ../registro-deportivo.php");
    exit();

} catch (Exception $e) {

    // Revertir si algo falla
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Aquí puedes redirigir con mensaje si quieres
    $_SESSION['mensaje'] = $e->getMessage();
    header("Location: ../registro-deportivo.php");
    exit();
}
?>