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
    // Verificar sesión 
    if (!isset($_SESSION["usuario"])) { 
        header("Location: ../index.html"); 
        exit(); 
    } 
    $pdo = new PDO($dsn, $user, $pass, $options);

    $sql_deportivas = " SELECT id_deportivo, nombre_actividad, lugar_actividad, capacidad, hora_de_inicio, hora_de_fin, dias_de_taller, maestro FROM actividades_deportivas"; 
    $stmDep = $pdo->prepare($sql_deportivas); 
    $stmDep->execute(); 
    $deportivas = $stmDep->fetchAll(PDO::FETCH_ASSOC); 

} catch(PDOException $e){
    die("Error en la conexión: " . $e->getMessage()); 
}
?>