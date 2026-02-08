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

    try{
        // Verificar sesión
        if (!isset($_SESSION["usuario"])) { 
            header("Location: ../index.html"); 
            exit(); 
        }

        $usuario = $_SESSION["usuario"];

        $alumno   = $_GET["usuario"] ?? null;
        $tipo = $_GET["tipo"] ?? null;
        $id_act = $_GET["act"] ?? null;
        $pdo = new PDO($dsn, $user, $pass, $options); 

        if($tipo == "deportivo"){ 
            $sql_deportivas = " SELECT DISTINCT
                        a.id_deportivo,
                        a.nombre_actividad,
                        a.lugar_actividad,
                        a.capacidad,
                        a.hora_de_inicio,
                        a.hora_de_fin,
                        a.dias_de_taller,
                        a.maestro
                        FROM actividades_deportivas a
                        INNER JOIN actividades_admins_deportivas d 
                            ON a.id_deportivo = d.id_deportivo
                        WHERE d.usuario = :usuario
                            AND a.id_deportivo <> :id
                        ORDER BY a.id_deportivo"; 
            $stmDep = $pdo->prepare($sql_deportivas); 
            $stmDep->execute(["usuario" => $_SESSION["usuario"], "id" => $id_act]); 
            $actividades = $stmDep->fetchAll(PDO::FETCH_ASSOC);
        }else{
            if($tipo == "cultural"){
                $sql_culturales = " SELECT DISTINCT
                        a.id_cultural,
                        a.nombre_actividad,
                        a.lugar_actividad,
                        a.capacidad,
                        a.hora_de_inicio,
                        a.hora_de_fin,
                        a.dias_de_taller,
                        a.maestro
                        FROM actividades_culturales a
                        INNER JOIN actividades_admins_culturales c 
                            ON a.id_cultural = c.id_cultural
                        WHERE c.usuario = :usuario
                            AND a.id_cultural <> :id
                        ORDER BY a.id_cultural"; 
                $stmCul = $pdo->prepare($sql_culturales); 
                $stmCul->execute(["usuario" => $_SESSION["usuario"], "id" => $id_act]); 
                $actividades = $stmCul->fetchAll(PDO::FETCH_ASSOC);
            }else{
                if (!$id_act || !$tipo) {
                    die("Actividades no encontradas");
                }
            }
        }
    }catch(Exception $e){
        die("Error en la conexión: " . $e->getMessage()); 
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/d_admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div id="topbar">
        <img src="imagenes/logo_ita.png" id="logo">
        <h2 style="color: azure;">Actividades Extraescolares</h2>
        <button class="salir" onclick="window.location.href='php/cerrar-sesion.php'">
            <h5>Cerrar Sesión</h5>
        </button>
    </div>
    
    <h1 style="margin-top: 3%;">Actividades Disponibles</h1>
    <div id="talleres" style="margin-top: 2%;">
        <table class="table table-hover">
        <form action="">
            <tr id="fila-inicio">
                <td>Actividad</td>
                <td>Lugar</td>
                <td>Capacidad</td>
                <td>Inicio</td>
                <td>Fin</td>
                <td>Días</td>
                <td>Maestro</td>
                <td>Acción</td>
            </tr>
            <?php foreach ($actividades as $act): 
                $idSeleccionado = ($tipo === 'deportivo')
                    ? $act['id_deportivo']
                    : $act['id_cultural'];
            ?>
            <tr id="filas-tabla">
                <td><?= htmlspecialchars($act["nombre_actividad"]) ?></td>
                <td><?= htmlspecialchars($act["lugar_actividad"]) ?></td>
                <td><?= htmlspecialchars($act["capacidad"]) ?></td>
                <td><?= htmlspecialchars($act["hora_de_inicio"]) ?></td>
                <td><?= htmlspecialchars($act["hora_de_fin"]) ?></td>
                <td><?= htmlspecialchars($act["dias_de_taller"]) ?></td>
                <td><?= htmlspecialchars($act["maestro"]) ?></td>
                <td><a href="" data-bs-toggle="modal" data-bs-target="#exampleModal"
                data-tipo="<?= $tipo ?>"
                data-alumno="<?= $alumno ?>"
                data-act-inicial = "<?= $id_act ?>"
                data-act-nueva="<?= $idSeleccionado ?>">Seleccionar</a></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($actividades)): ?>
            <tr>
                <td colspan="10" style="text-align:center; color:red;">
                    No se encontraron otras actividades deportivas disponibles creadas por tu cuenta para cambiar al usuario
                </td>
            </tr>
            <?php endif; ?>
        </form>
    </table>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1">
        <div class="modal-dialog">
        <div class="modal-content">

        <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Cambiar de taller</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            <h5>¿Estas seguro que quieres cambiar al alumno <?= $alumno ?> a la actividad seleccionada?</h5>
        </div>

        <div class="modal-footer">
        <form action="php/cambiar-alumno-act.php" method="POST">
        <input type="hidden" id="actividadTipo" name="tipo">
        <input type="hidden" id="actividadAlumno" name="usuario">
        <input type="hidden" id="actividadIdInicial" name="id_inicial">
        <input type="hidden" id="actividadIdNueva" name="id_nueva">
        <button style="width: 110pt;" type="submit" class="btn btn-danger">Aceptar</button>
        <button style="width: 110pt;" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // ===== MODAL ELIMINAR (SIN CAMBIOS) =====
    document.getElementById('exampleModal')
    .addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('actividadTipo').value = button.dataset.tipo;
        document.getElementById('actividadAlumno').value = button.dataset.alumno;
        document.getElementById('actividadIdInicial').value = button.dataset.actInicial;
        document.getElementById('actividadIdNueva').value = button.dataset.actNueva;
    });
</script>
</body>
</html>