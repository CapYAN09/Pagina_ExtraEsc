<?php
// editar-act.php
// Conexion
session_start();

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
// Verificar sesión
    if (!isset($_SESSION["usuario"])) { 
        header("Location: ../index.html"); 
        exit(); 
    }
    $pdo = new PDO($dsn, $user, $pass, $options);
    
// Validar que llegue por POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../admin.php");
    exit;
}

// ===== RECIBIR DATOS =====
$id        = $_POST["id"]        ?? null;
$tipo      = $_POST["tipo"]      ?? null;
$actividad = $_POST["actividad"] ?? null;
$lugar     = $_POST["lugar"]     ?? null;
$capacidad = $_POST["capacidad"] ?? null;
$inicio    = $_POST["inicio"]    ?? null;
$fin       = $_POST["fin"]       ?? null;
$dias      = $_POST["dias"]      ?? null;
$maestro   = $_POST["maestro"]   ?? null;

// Validación mínima
if (!$id || !$tipo) {
    die("Datos incompletos.");
}

// ===== DEFINIR TABLA Y CAMPO ID =====
if ($tipo === "deportivo") {
    $tabla = "actividades_deportivas";
    $campoId = "id_deportivo";
} elseif ($tipo === "cultural") {
    $tabla = "actividades_culturales";
    $campoId = "id_cultural";
} else {
    die("Tipo de actividad inválido.");
}

// ===== SQL =====
$sql = "
UPDATE $tabla SET
    nombre_actividad = :actividad,
    lugar_actividad  = :lugar,
    capacidad        = :capacidad,
    hora_de_inicio   = :inicio,
    hora_de_fin      = :fin,
    dias_de_taller   = :dias,
    maestro          = :maestro
WHERE $campoId = :id
";

// ===== EJECUTAR =====
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":actividad" => $actividad,
        ":lugar"     => $lugar,
        ":capacidad" => $capacidad,
        ":inicio"    => $inicio,
        ":fin"       => $fin,
        ":dias"      => $dias,
        ":maestro"   => $maestro,
        ":id"        => $id
    ]);

    // Redirigir al listado
    header("Location: ../admin.php?edit=ok");
    exit;

} catch (PDOException $e) {
    die("Error al editar: " . $e->getMessage());
}