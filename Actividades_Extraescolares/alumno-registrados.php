<?php
include "php/act-alumnos.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talleres Registrados</title>
    <link rel="stylesheet" href="css/d_registros.css">
    <link rel="stylesheet" href="css/d_admin.css">
    <style>
        h3{
            margin-left: 5%;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <div id="topbar">
        <img src="imagenes/logo_ita.png" alt="logo_ita" id="logo">
        <h2 style="color: azure;">Actividades Extraescolares</h2>
        <button class="regresar" style="color: azure;" onclick="window.location.href='alumno.html'"><h5>Regresar</h5></button>
    </div>
    <div>
        <h1 style="margin-top: 3%;">Talleres Registrados</h1>
        <br>
        <h3>Estas registrado en: </h3>
        <br>
    </div>
    <div>
        <h1>Deportivo</h1>
    </div>
    <div id="talleres" style="margin-top: 2%;">
        <table class="table table-hover">
            <tr id="fila-inicio">
                <td class="primer-fila">Nombre de Actividad</td>
                <td class="primer-fila">Lugar de Actividad</td>
                <td class="primer-fila">Hora de Inicio</td>
                <td class="primer-fila">Hora de fin</td>
                <td class="primer-fila">Dias de taller</td>
                <td class="primer-fila">Maestro</td>
            </tr>
            <!-- ACTIVIDADES DEPORTIVAS -->
            <?php foreach ($deportivas as $dep): ?>
            <tr id="filas-tabla">
                <td><?= htmlspecialchars($dep["nombre_actividad"]) ?></td>
                <td><?= htmlspecialchars($dep["lugar_actividad"]) ?></td>
                <td><?= htmlspecialchars($dep["hora_de_inicio"]) ?></td>
                <td><?= htmlspecialchars($dep["hora_de_fin"]) ?></td>
                <td><?= htmlspecialchars($dep["dias_de_taller"]) ?></td>
                <td><?= htmlspecialchars($dep["maestro"]) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($deportivas)): ?>
            <tr>
                <td colspan="10" style="text-align:center; color:red;">
                    No se encontraron actividades registradas.
                </td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
    <div>
        <br>
        <h1>Cultural</h1>
    </div>
    <div id="talleres" style="margin-top: 2%;">
        <table class="table table-hover">
            <tr id="fila-inicio">
                <td class="primer-fila">Nombre de Actividad</td>
                <td class="primer-fila">Lugar de Actividad</td>
                <td class="primer-fila">Hora de Inicio</td>
                <td class="primer-fila">Hora de fin</td>
                <td class="primer-fila">Dias de taller</td>
                <td class="primer-fila">Maestro</td>
            </tr>
            <!-- ACTIVIDADES CULTURALES -->
            <?php foreach ($culturales as $cul): ?>
            <tr id="filas-tabla">
                <td><?= htmlspecialchars($cul["nombre_actividad"]) ?></td>
                <td><?= htmlspecialchars($cul["lugar_actividad"]) ?></td>
                <td><?= htmlspecialchars($cul["hora_de_inicio"]) ?></td>
                <td><?= htmlspecialchars($cul["hora_de_fin"]) ?></td>
                <td><?= htmlspecialchars($cul["dias_de_taller"]) ?></td>
                <td><?= htmlspecialchars($cul["maestro"]) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($culturales)): ?>
            <tr id="filas-tabla">
                <td colspan="10" style="text-align:center; color:red;">
                    No se encontraron actividades registradas.
                </td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
    <p style="margin-left: 5%;">
        Si deseas cambiar de actividad ponte en contacto con el
        <a href="https://api.whatsapp.com/send/?phone=524492899612&text&type=phone_number&app_absent=0" target="_blank">
         administrador
        </a>
    </p>
    
</body>
</html>