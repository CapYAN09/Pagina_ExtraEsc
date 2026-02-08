<?php
session_start();

$host = 'localhost';
$db   = 'actextita';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {

    if (!isset($_SESSION["usuario"])) {
        header("Location: ../index.html");
    }

    $pdo = new PDO($dsn, $user, $pass, $options);

    // Obtener datos enviados desde el formulario
    $actividad = $_POST['actividad'] ?? '';
    $lugar = $_POST['lugar'] ?? '';
    $capacidad = $_POST['capacidad'] ?? '';
    $inicio = $_POST['inicio'] ?? '';
    $fin = $_POST['fin'] ?? '';
    $dias = $_POST['dias'] ?? '';
    $maestro = $_POST['maestro'] ?? '';

    // Insertar actividad deportiva
    $sqlInsertActividad = "
        INSERT INTO actividades_deportivas
        (nombre_actividad, lugar_actividad, capacidad, hora_de_inicio, hora_de_fin, dias_de_taller, maestro)
        VALUES
        (:actividad, :lugar, :capacidad, :inicio, :fin, :dias, :maestro)
    ";

    $stmt = $pdo->prepare($sqlInsertActividad);
    $stmt->execute([
        'actividad' => $actividad,
        'lugar'     => $lugar,
        'capacidad' => $capacidad,
        'inicio'    => $inicio,
        'fin'       => $fin,
        'dias'      => $dias,
        'maestro'   => $maestro
    ]);

    // OBTENER ID DE LA ACTIVIDAD INSERTADA
    $idActividad = $pdo->lastInsertId();

    // Insertar quién creó la actividad y qué actividad fue
    $sqlInsertAdminActividad = "
        INSERT INTO actividades_admins_deportivas (usuario,id_deportivo)
        VALUES (:usuario,:id_actividad)
    ";

    $stmt2 = $pdo->prepare($sqlInsertAdminActividad);
    $stmt2->execute([
        'usuario'      => $_SESSION["usuario"],
        'id_actividad' => $idActividad
    ]);

    // Redirigir
    $_SESSION['mensaje'] = "Actividad creada correctamente";
    header("Location: ../admin.php");
    exit();

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $_SESSION['mensaje'] = $e->getMessage();
    header("Location: ../admin.php");
    exit();
}
?>