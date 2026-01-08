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
    try { 
        // Verificar sesión 
        if (!isset($_SESSION["usuario"])) { 
            header("Location: ../index.html"); 
            exit(); 
            } 
    $pdo = new PDO($dsn, $user, $pass, $options); 
    /* ----------------------------------------------------------------------------------- 
    ACTIVIDADES DEPORTIVAS 
    ------------------------------------------------------------------------------------ */ 
    $sql_deportivas = " SELECT a.nombre_actividad, a.lugar_actividad, a.capacidad, a.hora_de_inicio, a.hora_de_fin, a.dias_de_taller, a.maestro FROM actividades_deportivas a JOIN actividades_alumnos_deportivas d ON a.id_deportivo = d.id_deportivo WHERE d.usuario = :usuario "; 
    $stmDep = $pdo->prepare($sql_deportivas); 
    $stmDep->execute(["usuario" => $_SESSION["usuario"]]); 
    $deportivas = $stmDep->fetchAll(PDO::FETCH_ASSOC); 
    /* ----------------------------------------------------------------------------------- 
    ACTIVIDADES CULTURALES 
    ------------------------------------------------------------------------------------ */ 
    $sql_culturales = " SELECT a.nombre_actividad, a.lugar_actividad, a.capacidad, a.hora_de_inicio, a.hora_de_fin, a.dias_de_taller, a.maestro FROM actividades_culturales a JOIN actividades_alumnos_culturales c ON a.id_cultural = c.id_cultural WHERE c.usuario = :usuario "; 
    $stmCul = $pdo->prepare($sql_culturales); 
    $stmCul->execute(["usuario" => $_SESSION["usuario"]]); 
    $culturales = $stmCul->fetchAll(PDO::FETCH_ASSOC); 
    } catch (PDOException $e) { 
        die("Error en la conexión: " . $e->getMessage()); 
    } 
        
?> 