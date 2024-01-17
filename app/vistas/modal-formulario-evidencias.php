<?php 
$idRegistro = $_GET['idRegistro'];
?>

<div class="modal-header">
<h5 class="modal-title">Agregar evidencia</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body"> 

<h5># 0<?=$idRegistro;?></h5>

<h6 class="mt-2">Descripción evidencia:</h6>
<textarea class="form-control rounded-0" id="EvidenciaDescripcion" ></textarea>

<h6 class="mt-2">Archivo:</h6>
<input type="file" id="EvidenciaArchivo">

</div>

<div class="modal-footer">
<button type="button" class="btn btn-primary" onclick="EvidenciaAgregar(<?=$idRegistro;?>)">Agregar</button>
</div>