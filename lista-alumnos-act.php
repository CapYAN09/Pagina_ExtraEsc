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
        $id   = $_GET["id"]   ?? null;
        $tipo = $_GET["tipo"] ?? null;
        $pdo = new PDO($dsn, $user, $pass, $options); 

        if($tipo == "deportivo"){ 
            $sql_lista = "SELECT id, correo, id_deportivo FROM actividades_alumnos_deportivas WHERE id_deportivo = $id"; 
            $stmLista = $pdo->prepare($sql_lista); 
            $stmLista->execute(); 
            $alumnos = $stmLista->fetchAll(PDO::FETCH_ASSOC);
            $datosAct = "SELECT nombre_actividad, hora_de_inicio, hora_de_fin FROM actividades_deportivas WHERE id_deportivo = $id";
            $stmData= $pdo->prepare($datosAct); 
            $stmData->execute(); 
            $actividad = $stmData->fetchAll(PDO::FETCH_ASSOC);
        }else{
            if($tipo == "cultural"){
                $sql_lista = "SELECT id, correo, id_cultural FROM actividades_alumnos_culturales WHERE id_cultural = $id"; 
                $stmLista = $pdo->prepare($sql_lista); 
                $stmLista->execute(); 
                $alumnos = $stmLista->fetchAll(PDO::FETCH_ASSOC);
                $datosAct = "SELECT nombre_actividad, hora_de_inicio, hora_de_fin FROM actividades_culturales WHERE id_cultural = $id";
                $stmData= $pdo->prepare($datosAct); 
                $stmData->execute(); 
                $actividad = $stmData->fetchAll(PDO::FETCH_ASSOC);
            }else{
                if (!$id || !$tipo) {
                    die("Actividad no válida");
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
    <?php if (!empty($_SESSION['mensaje_eliminar_alumno'])): ?>
        <div class="alert alert-warning alert-dismissible fade show text-center mx-5 mt-3" role="alert">
            <?= htmlspecialchars($_SESSION['mensaje_eliminar_alumno']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php unset($_SESSION['mensaje_eliminar_alumno']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['cambiar_alumno'])): ?>
        <div class="alert alert-warning alert-dismissible fade show text-center mx-5 mt-3" role="alert">
            <?= htmlspecialchars($_SESSION['cambiar_alumno']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php unset($_SESSION['cambiar_alumno']); ?>
    <?php endif; ?>
    <?php foreach ($actividad as $act): ?>
        <h1 style="margin-top: 3%;">Alumnos inscritos en <?= htmlspecialchars($act["nombre_actividad"]) ?> de <?= htmlspecialchars($act["hora_de_inicio"]) ?> a <?= htmlspecialchars($act["hora_de_fin"]) ?></h1>
    <?php endforeach; ?>
    <div id="talleres" style="margin-top: 2%;">
        <table class="table table-hover">
        <form action="">
            <tr id="fila-inicio">
                <td class="primer-fila">ID Registro</td>
                <td class="primer-fila">Num_Control</td>
                <td class="primer-fila">Cambiar</td>
                <td class="primer-fila">Eliminar</td>
            </tr>
            <?php foreach ($alumnos as $aln): ?>
            <tr id="filas-tabla">
                <td><?= $aln["id"] ?></td>
                <td><?= htmlspecialchars($aln["correo"]) ?></td>
                <td><a href="actualizar-act-alumno.php?usuario=<?= $aln["correo"] ?>&tipo=<?= $tipo ?>&act=<?= $id ?>">Cambiar</a></td>
                <td><a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal"
                data-act="<?= $id ?>"
                data-id="<?= $aln["id"] ?>"
                data-usuario="<?= $aln["correo"] ?>"
                data-tipo="<?= $tipo ?>">Eliminar</a></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($alumnos)): ?>
            <tr id="filas-tabla">
                <td colspan="10" style="text-align:center; color:red;">
                    No se encontraron alumnos registrados en esta actividad
                </td>
            </tr>
            <?php endif; ?>
        </form>
    </table>
    <div class="modal fade" id="exampleModal" tabindex="-1">
        <div class="modal-dialog">
        <div class="modal-content">

        <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Eliminar Alumno</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            <h5>¿Estas seguro que quieres eliminar al alumno </h5>
            <h5><strong><span id="actividadUsuario"></span></strong> de esta actividad?</h5>
        </div>

        <div class="modal-footer">
        <form action="php/eliminar-act-alumno.php" method="POST">
        <input type="hidden" id="actividadTipo" name="tipo">
        <input type="hidden" id="actividadAct" name="id_Act">
        <input type="hidden" id="actividadId" name="id">
        <input type="hidden" id="actividadUsuarioInput" name="usuario_aln">
        <button style="width: 110pt;" type="submit" class="btn btn-danger">Eliminar</button>
        <button style="width: 110pt;" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </form>
    </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // ===== MODAL ELIMINAR (SIN CAMBIOS) =====
    document.getElementById('exampleModal')
    .addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    document.getElementById('actividadUsuario').textContent = button.dataset.usuario;
    document.getElementById('actividadUsuarioInput').value = button.dataset.usuario;
    document.getElementById('actividadAct').value = button.dataset.act;
    document.getElementById('actividadTipo').value = button.dataset.tipo;
    document.getElementById('actividadId').value = button.dataset.id;
    });
</script>
</body>
</html>