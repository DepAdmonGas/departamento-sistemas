<?php
$idRegistro = $_GET['idticket'];
?>

<div class="modal-header">
  <h5 class="modal-title">Agregar Tarea</h5>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">

  <h5># Ticket: 0<?= $idRegistro; ?></h5>

  <h6 class="mt-3">Descripción de la tarea:</h6>

  <textarea class="form-control rounded-0" id="ActividadDescripcion" onkeyup="EditarDescripcion(this,<?=$idRegistro?>)"></textarea>

  <h6 class="mt-2">Archivo:</h6>
  <input class="form-control rounded-0" type="file" id="EvidenciaArchivo">

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-primary" onclick="finalizarTarea(<?= $idRegistro ?>)">Agregar</button>
</div>