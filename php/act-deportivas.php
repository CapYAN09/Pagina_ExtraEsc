<?php
session_start();

//Conexion
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
    $pdo = new PDO($dsn, $user, $pass, $options);
    // Verificar sesión 
    if(!isset($_SESSION['user_email'])){
        //
        /*if (!isset($_SESSION["usuario"])) { 
            header("Location: ../index.php"); 
            exit(); 
        }*/
        header("Location: ../index.php"); 
        exit(); 
    }else{
        $_SESSION["usuario"] = $_SESSION['user_email'];
        $usuario = $_SESSION["usuario"];
        $sql_deportivas = " SELECT correo FROM alumnos WHERE correo = :usuario"; 
        $stmt = $pdo->prepare($sql_deportivas);
        $stmt->execute([
            'usuario' => $usuario
        ]);
        if (!$stmt->fetch()) {
        // Usuario es alumno rezagado → ir a alumno.html
            $sql_Insert = "INSERT INTO alumnos(correo) VALUES (:usuario)";
            $stmtInsert = $pdo->prepare($sql_Insert);
            $stmtInsert->execute([
                'usuario' => $usuario
            ]);
        }
    }

    $sql_deportivas = " SELECT id_deportivo, nombre_actividad, lugar_actividad, capacidad, hora_de_inicio, hora_de_fin, dias_de_taller, maestro FROM actividades_deportivas"; 
    $stmDep = $pdo->prepare($sql_deportivas); 
    $stmDep->execute(); 
    $deportivas = $stmDep->fetchAll(PDO::FETCH_ASSOC); 

} catch(PDOException $e){
    die("Error en la conexión: " . $e->getMessage()); 
}
?>