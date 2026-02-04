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
    $id_actividad = $_POST['id_cultural'] ?? '';

    if (empty($id_actividad)) {
        die("ID de actividad no recibido");
    }

    // Iniciar transacción
    $pdo->beginTransaction();

    // Verificar si YA está inscrito
    $sqlExiste = "
        SELECT 1
        FROM actividades_alumnos_culturales
        WHERE correo = :usuario
        LIMIT 1
        FOR UPDATE
    ";
    $stmtExiste = $pdo->prepare($sqlExiste);
    $stmtExiste->execute([
        'usuario' => $usuario
    ]);

    if ($stmtExiste->fetch()) {
        $_SESSION['mensaje'] = "Ya estás inscrito en una actividad cultural";
        header("Location: ../registro-cultural.php");
        exit();
    }

    // Verificar capacidad disponible
    $sqlCapacidad = "
        SELECT capacidad
        FROM actividades_culturales
        WHERE id_cultural = :id
        FOR UPDATE
    ";
    $stmtCap = $pdo->prepare($sqlCapacidad);
    $stmtCap->execute(['id' => $id_actividad]);
    $actividad = $stmtCap->fetch();

    if (!$actividad) {
        $_SESSION['mensaje'] = "Actividad no encontrada";
        header("Location: ../registro-cultural.php");
        exit();
    }

    if ($actividad['capacidad'] <= 0) {
        $_SESSION['mensaje'] = "No hay cupos disponibles";
        header("Location: ../registro-cultural.php");
        exit();
    }

    // Insertar inscripción
    $sqlInsert = "
        INSERT INTO actividades_alumnos_culturales (correo, id_cultural)
        VALUES (:usuario, :id)
    ";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute([
        'usuario' => $usuario,
        'id' => $id_actividad
    ]);

    // Reducir capacidad
    $sqlUpdate = "
        UPDATE actividades_culturales
        SET capacidad = capacidad - 1
        WHERE id_cultural = :id
    ";
    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->execute(['id' => $id_actividad]);

    // Confirmar cambios
    $pdo->commit();

    $_SESSION['mensaje'] = "Te has registrado correctamente, ahora puedes verificarlo en actividades registradas";
    header("Location: ../registro-cultural.php");
    exit();

} catch (Exception $e) {

    // Revertir si algo falla
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Aquí puedes redirigir con mensaje si quieres
    $_SESSION['mensaje'] = $e->getMessage();
    header("Location: ../registro-cultural.php");
    exit();
}
?>