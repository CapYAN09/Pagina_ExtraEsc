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
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Obtener datos enviados desde index.html
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';
    $_SESSION['usuario'] = $usuario;
    // ---------------------------
    // 1️⃣ Buscar en tabla admins
    // ---------------------------
    $sqlAdmins = "SELECT * FROM admins WHERE usuario = :usuario AND contraseña = :password";
    $stmt = $pdo->prepare($sqlAdmins);
    $stmt->execute([
        'usuario' => $usuario,
        'password' => $password
    ]);

    if ($stmt->fetch()) {
        // Usuario es administrador → ir a admin.html
        header("Location: ../admin.php");
        exit();
    }

    // -------------------------------------
    // Buscar en tabla alumnos
    // -------------------------------------
    $sqlAlumnos = "SELECT * FROM alumnos WHERE correo = :usuario AND contraseña = :password";
    $stmt = $pdo->prepare($sqlAlumnos);
    $stmt->execute([
        'correo' => $usuario,
        'password' => $password
    ]);

    if ($stmt->fetch()) {
        // Usuario es alumno rezagado → ir a alumno.html
        header("Location: ../alumno.html");
        exit();
    }

    // Si llega aquí, no existe el usuario
    header("Location: ../index.php?error=1");
    exit();

} catch (PDOException $e) {
    header("Location: ../index.php?error=1");
    exit();
}

?>