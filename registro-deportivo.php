<?php
    include "php/act-deportivas.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/d_registros.css">
    <link rel="stylesheet" href="css/d_admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <div id="topbar">
        <img src="imagenes/logo_ita.png" alt="logo_ita" id="logo">
        <h2 style="color: azure;">Actividades Extraescolares</h2>
        <button class="regresar" style="color: azure;" onclick="window.location.href='/alumno.html'"><h5>Regresar</h5></button>
    </div>
    <?php if (!empty($_SESSION['mensaje'])): ?>
        <div class="alert alert-warning alert-dismissible fade show text-center mx-5 mt-3" role="alert">
            <?= htmlspecialchars($_SESSION['mensaje']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>
    <div>
        <h1 style="margin-top: 3%;">Actividades Deportivas Disponibles</h1>
    </div>
    <div style="margin-top: 2%;" id="talleres">
        <table class="table table-hover">
            <tr id="fila-inicio">
                <td class="primer-fila">Nombre de Actividad</td>
                <td class="primer-fila">Lugar de Actividad</td>
                <td class="primer-fila">Disponibles</td>
                <td class="primer-fila">Hora de Inicio</td>
                <td class="primer-fila">Hora de fin</td>
                <td class="primer-fila">Dias de taller</td>
                <td class="primer-fila">Maestro</td>
                <td class="primer-fila">Accion</td>
            </tr>
            <?php foreach ($deportivas as $dep): ?>
            <tr id="filas-tabla">
                <td><?= htmlspecialchars($dep["nombre_actividad"]) ?></td>
                <td><?= htmlspecialchars($dep["lugar_actividad"]) ?></td>
                <td><?= htmlspecialchars($dep["capacidad"]) ?></td>
                <td><?= htmlspecialchars($dep["hora_de_inicio"]) ?></td>
                <td><?= htmlspecialchars($dep["hora_de_fin"]) ?></td>
                <td><?= htmlspecialchars($dep["dias_de_taller"]) ?></td>
                <td><?= htmlspecialchars($dep["maestro"]) ?></td>
                <td><input type="submit"
                    value="Inscribirse"
                    id="btn-inscribirse"
                    class="btn btn-primary btn-md"
                    data-bs-toggle="modal"
                    data-bs-target="#exampleModal"
                    data-id="<?= htmlspecialchars($dep["id_deportivo"]) ?>"
                    data-actividad="<?= htmlspecialchars($dep["nombre_actividad"]) ?>"
                    data-lugar="<?= htmlspecialchars($dep["lugar_actividad"]) ?>"
                    data-inicio="<?= htmlspecialchars($dep["hora_de_inicio"]) ?>"
                    data-fin="<?= htmlspecialchars($dep["hora_de_fin"]) ?>"
                    data-dias="<?= htmlspecialchars($dep["dias_de_taller"]) ?>"></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="exampleModalLabel">Nuevo Taller</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Actividad seleccionada:</strong> <span id="actividadSeleccionada"></span></p>
                <p><strong>Lugar de la Actividad:</strong> <span id="actividadLugar"></span></p>
                <p><strong>Horario de Inicio:</strong> <span id="actividadInicio"></span></p>
                <p><strong>Horario de Fin:</strong> <span id="actividadFin"></span></p>
                <p><strong>Dias del Taller:</strong> <span id="actividadDias"></span></p>
                <input type="hidden" id="actividadInput" name="nombre_actividad">
                <input type="hidden" id="actividadId" name="id_deportivo">
            </div>
        <div class="modal-footer">
            <form action="php/registro-alumno-dep.php" method="POST">
                <input type="hidden" id="actividadIdInput" name="id_deportivo">
                <button style="width: 110pt;" type="submit" class="btn btn-success">Inscribirse</button>
                <button style="width: 110pt;" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </form>
        </div>
        </div>
        </div>
    </div>
    <?php if (empty($deportivas)): ?>
            <tr>
                <td colspan="10" style="text-align:center; color:red;">
                    No se encontraron actividades deportivas registradas.
                </td>
            </tr>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Detecta si existe ?error=1 en la URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get("error") === "1") {
        var myModal = new bootstrap.Modal(document.getElementById('modalError'));
        myModal.show();
    }
    </script>
    <script>
    const modal = document.getElementById('exampleModal');

    modal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget; // botón que abrió el modal
    const id = button.getAttribute('data-id');
    const actividad = button.getAttribute('data-actividad');
    const lugar = button.getAttribute('data-lugar');
    const inicio = button.getAttribute('data-inicio');
    const fin = button.getAttribute('data-fin');
    const dias = button.getAttribute('data-dias');

    document.getElementById('actividadSeleccionada').textContent = actividad;
    document.getElementById('actividadLugar').textContent = lugar;
    document.getElementById('actividadInicio').textContent = inicio;
    document.getElementById('actividadFin').textContent = fin;
    document.getElementById('actividadDias').textContent = dias;

    document.getElementById('actividadIdInput').value = id;
    });
</script>
</body>
</html>
