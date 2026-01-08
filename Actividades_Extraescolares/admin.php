<?php
include "php/admin-taller.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talleres</title>
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

<h1 style="margin-top: 3%;">Crear Taller</h1>
<button class="opciones" onclick="window.location.href='taller-deportivo.html'">Deportivo</button>
<button class="opciones" onclick="window.location.href='taller-cultural.html'">Cultural</button>

<h1 style="margin-top: 3%;">Talleres Creados</h1>

<div id="talleres">
<table class="table table-hover">
<tr id="fila-inicio">
    <td>Actividad</td>
    <td>Lugar</td>
    <td>Capacidad</td>
    <td>Inicio</td>
    <td>Fin</td>
    <td>Días</td>
    <td>Maestro</td>
    <td>Tipo</td>
    <td>Inscritos</td>
    <td>Editar</td>
    <td>Eliminar</td>
</tr>

<!-- ================= DEPORTIVOS ================= -->
<?php foreach ($deportivas as $dep): ?>
<tr id="filas-tabla">
    <td><?= htmlspecialchars($dep["nombre_actividad"]) ?></td>
    <td><?= htmlspecialchars($dep["lugar_actividad"]) ?></td>
    <td><?= htmlspecialchars($dep["capacidad"]) ?></td>
    <td><?= htmlspecialchars($dep["hora_de_inicio"]) ?></td>
    <td><?= htmlspecialchars($dep["hora_de_fin"]) ?></td>
    <td><?= htmlspecialchars($dep["dias_de_taller"]) ?></td>
    <td><?= htmlspecialchars($dep["maestro"]) ?></td>
    <td style="color:blue; font-size: large;">Deportivo</td>
    <td>
        <a href="lista-alumnos-act.php?id=<?= $dep["id_deportivo"] ?>&tipo=deportivo">
            Lista
        </a>
    </td>
    <td>
        <a href="#"
           data-bs-toggle="modal"
           data-bs-target="#exampleModalEdit"
           data-id="<?= $dep["id_deportivo"] ?>"
           data-tipo="deportivo"
           data-actividad="<?= htmlspecialchars($dep["nombre_actividad"]) ?>"
           data-lugar="<?= htmlspecialchars($dep["lugar_actividad"]) ?>"
           data-capacidad="<?= htmlspecialchars($dep["capacidad"]) ?>"
           data-inicio="<?= htmlspecialchars($dep["hora_de_inicio"]) ?>"
           data-fin="<?= htmlspecialchars($dep["hora_de_fin"]) ?>"
           data-dias="<?= htmlspecialchars($dep["dias_de_taller"]) ?>"
           data-maestro="<?= htmlspecialchars($dep["maestro"]) ?>"
        >Editar</a>
    </td>

    <td>
        <a href="#"
           data-bs-toggle="modal"
           data-bs-target="#exampleModal"
           data-iddep="<?= $dep["id_deportivo"] ?>"
           data-actividad="<?= htmlspecialchars($dep["nombre_actividad"]) ?>"
           data-lugar="<?= htmlspecialchars($dep["lugar_actividad"]) ?>"
           data-inicio="<?= htmlspecialchars($dep["hora_de_inicio"]) ?>"
           data-fin="<?= htmlspecialchars($dep["hora_de_fin"]) ?>"
           data-dias="<?= htmlspecialchars($dep["dias_de_taller"]) ?>"
        >Eliminar</a>
    </td>
</tr>
<?php endforeach; ?>

<!-- ================= CULTURALES ================= -->
<?php foreach ($culturales as $cul): ?>
<tr id="filas-tabla">
    <td><?= htmlspecialchars($cul["nombre_actividad"]) ?></td>
    <td><?= htmlspecialchars($cul["lugar_actividad"]) ?></td>
    <td><?= htmlspecialchars($cul["capacidad"]) ?></td>
    <td><?= htmlspecialchars($cul["hora_de_inicio"]) ?></td>
    <td><?= htmlspecialchars($cul["hora_de_fin"]) ?></td>
    <td><?= htmlspecialchars($cul["dias_de_taller"]) ?></td>
    <td><?= htmlspecialchars($cul["maestro"]) ?></td>
    <td style="color:green;">Cultural</td>
    <td>
        <a href="lista-alumnos-act.php?id=<?= $cul["id_cultural"] ?>&tipo=cultural">
            Lista
        </a>
    </td>
    <td>
        <a href="#"
           data-bs-toggle="modal"
           data-bs-target="#exampleModalEdit"
           data-id="<?= $cul["id_cultural"] ?>"
           data-tipo="cultural"
           data-actividad="<?= htmlspecialchars($cul["nombre_actividad"]) ?>"
           data-lugar="<?= htmlspecialchars($cul["lugar_actividad"]) ?>"
           data-capacidad="<?= htmlspecialchars($cul["capacidad"]) ?>"
           data-inicio="<?= htmlspecialchars($cul["hora_de_inicio"]) ?>"
           data-fin="<?= htmlspecialchars($cul["hora_de_fin"]) ?>"
           data-dias="<?= htmlspecialchars($cul["dias_de_taller"]) ?>"
           data-maestro="<?= htmlspecialchars($cul["maestro"]) ?>"
        >Editar</a>
    </td>

    <td>
        <a href="#"
           data-bs-toggle="modal"
           data-bs-target="#exampleModal"
           data-idcult="<?= $cul["id_cultural"] ?>"
           data-actividad="<?= htmlspecialchars($cul["nombre_actividad"]) ?>"
           data-lugar="<?= htmlspecialchars($cul["lugar_actividad"]) ?>"
           data-inicio="<?= htmlspecialchars($cul["hora_de_inicio"]) ?>"
           data-fin="<?= htmlspecialchars($cul["hora_de_fin"]) ?>"
           data-dias="<?= htmlspecialchars($cul["dias_de_taller"]) ?>"
        >Eliminar</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
</div>

<!-- ================= MODAL EDITAR ================= -->
<div class="modal fade" id="exampleModalEdit" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<form action="php/editar-act.php" method="POST">

<div class="modal-header bg-primary text-white">
<h5 class="modal-title">Editar Actividad</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<input type="hidden" id="editId" name="id">
<input type="hidden" id="editTipo" name="tipo">

<label>Actividad</label>
<input class="form-control mb-2" id="editActividad" name="actividad">

<label>Lugar</label>
<input class="form-control mb-2" id="editLugar" name="lugar">

<label>Capacidad</label>
<input class="form-control mb-2" id="editCapacidad" name="capacidad">

<label>Hora inicio</label>
<input class="form-control mb-2" id="editInicio" name="inicio" type="time">

<label>Hora fin</label>
<input class="form-control mb-2" id="editFin" name="fin" type="time">

<label>Días</label>
<input class="form-control mb-2" id="editDias" name="dias">

<label>Maestro</label>
<input class="form-control mb-2" id="editMaestro" name="maestro">
</div>

<div class="modal-footer">
<button style="width: 110pt;" type="submit" class="btn btn-success">Guardar cambios</button>
<button style="width: 110pt;" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
</div>

</form>

</div>
</div>
</div>

<!-- ================= MODAL ELIMINAR (SIN CAMBIOS) ================= -->
<div class="modal fade" id="exampleModal" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header bg-danger text-white">
<h5 class="modal-title">Eliminar Actividad</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<p><strong>Actividad:</strong> <span id="actividadSeleccionada"></span></p>
<p><strong>Lugar:</strong> <span id="actividadLugar"></span></p>
<p><strong>Inicio:</strong> <span id="actividadInicio"></span></p>
<p><strong>Fin:</strong> <span id="actividadFin"></span></p>
<p><strong>Días:</strong> <span id="actividadDias"></span></p>
<p><h6>¿Estas seguro que quieres eliminar este taller?</h6></p>
</div>

<div class="modal-footer">
<form action="php/eliminar-act.php" method="POST">
<input type="hidden" id="actividadIdInputD" name="id_deportivo">
<input type="hidden" id="actividadIdInputC" name="id_cultural">
<button style="width: 110pt;" type="submit" class="btn btn-danger">Eliminar</button>
<button style="width: 110pt;" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
</form>
</div>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ===== MODAL EDITAR =====
document.getElementById('exampleModalEdit')
.addEventListener('show.bs.modal', function (event) {
  const b = event.relatedTarget;

  editId.value = b.dataset.id;
  editTipo.value = b.dataset.tipo;
  editActividad.value = b.dataset.actividad;
  editLugar.value = b.dataset.lugar;
  editCapacidad.value = b.dataset.capacidad;
  editInicio.value = b.dataset.inicio;
  editFin.value = b.dataset.fin;
  editDias.value = b.dataset.dias;
  editMaestro.value = b.dataset.maestro;
});

// ===== MODAL ELIMINAR (SIN CAMBIOS) =====
document.getElementById('exampleModal')
.addEventListener('show.bs.modal', function (event) {

  const button = event.relatedTarget;

  actividadSeleccionada.textContent = button.dataset.actividad;
  actividadLugar.textContent = button.dataset.lugar;
  actividadInicio.textContent = button.dataset.inicio;
  actividadFin.textContent = button.dataset.fin;
  actividadDias.textContent = button.dataset.dias;

  actividadIdInputD.value = button.dataset.iddep || '';
  actividadIdInputC.value = button.dataset.idcult || '';
});
</script>
<?php if (!empty($_SESSION['mensaje'])): ?>
<div class="modal fade" id="modalError" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-success">
        <h5 class="modal-title">Aviso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">
        <p><?= htmlspecialchars($_SESSION['mensaje']) ?></p>
      </div>

      <div class="modal-footer justify-content-center">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Aceptar</button>
      </div>

    </div>
  </div>
</div>

<script>
    const modal = new bootstrap.Modal(document.getElementById('modalError'));
    modal.show();
</script>

<?php unset($_SESSION['mensaje']); ?>
<?php endif; ?>
</body>
</html>