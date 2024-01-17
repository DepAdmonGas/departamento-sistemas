<?php 
$idRegistro = $_GET['idRegistro'];
?>

<div class="modal-header">
<h5 class="modal-title">Agregar actividad</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body"> 

<h5># 0<?=$idRegistro;?></h5>

<h6 class="mt-2">Descripción de la actividad:</h6>
<textarea class="form-control rounded-0" id="ActividadDescripcion" ></textarea>

<h6 class="mt-2">Archivo:</h6>
<input type="file" id="ActividadArchivo">

</div>

<div class="modal-footer">
<button type="button" class="btn btn-primary" onclick="ActividadAgregar(<?=$idRegistro;?>)">Agregar</button>
</div>